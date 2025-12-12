<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function($q) {
            $q->where('is_active', true);
        }, 'products.variations'])->get();

        $tables = DiningTable::all();
        $recentOrders = Order::with('user')->latest()->take(10)->get();

        return view('pos.index', compact('categories', 'tables', 'recentOrders'));
    }

    public function store(Request $request)
    {
        // 1. SİPARİŞ BAŞLIĞINI OLUŞTUR VEYA BUL
        if ($request->order_id) {
            $order = Order::find($request->order_id);
        } else {
            $order = new Order();
            $order->dining_table_id = $request->dining_table_id;
            $order->user_id = auth()->id();
            $order->status = 'pending';
        }

        $order->customer_name = $request->customer_name;

        // Sipariş Durumu Mantığı
        if ($request->payment_method != 'pending') {
            $order->payment_status = 'paid';
            $order->status = 'completed';
            if ($order->dining_table_id) {
                DiningTable::where('id', $order->dining_table_id)->update(['status' => 'empty']);
            }
        } else {
            if ($order->dining_table_id) {
                DiningTable::where('id', $order->dining_table_id)->update(['status' => 'occupied']);
            }
        }
        $order->save();

        // 2. AKILLI SENKRONİZASYON (SİLMEK YERİNE GÜNCELLEME)

        // Gelen sepet kalemlerinin ID'lerini topla (Eğer var olan bir kalemse ID'si vardır)
        $incomingItemIds = [];
        $totalAmount = 0;

        foreach ($request->cart as $item) {
            // Eğer 'order_item_id' varsa güncelle, yoksa yeni oluştur
            if (isset($item['order_item_id']) && $item['order_item_id']) {
                $orderItem = OrderItem::find($item['order_item_id']);

                // Sadece miktar veya fiyat değişmiş olabilir, statüsü (waiting/cooking) KORUNMALI
                if ($orderItem) {
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->sub_total = $item['price'] * $item['quantity'];
                    $orderItem->save();
                    $incomingItemIds[] = $orderItem->id;
                    $totalAmount += $orderItem->sub_total;
                    continue; // Döngünün başına dön, varyasyonları elleme (basitlik için)
                }
            }

            // Yeni Kalem Ekleme
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->unit_price = $item['price'];
            $orderItem->sub_total = $item['price'] * $item['quantity'];
            $orderItem->status = 'waiting'; // Yeni ürün mutfağa 'bekliyor' olarak düşer
            $orderItem->save();

            $incomingItemIds[] = $orderItem->id;
            $totalAmount += $orderItem->sub_total;

            // Varyasyonları Kaydet (Sadece yeni ürünler için)
            if (isset($item['variations']) && is_array($item['variations'])) {
                foreach ($item['variations'] as $var) {
                    DB::table('order_item_variations')->insert([
                        'order_item_id' => $orderItem->id,
                        'product_variation_id' => $var['id'],
                        'variation_name' => $var['name'],
                        'price' => $var['price'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 3. SEPETTEN SİLİNENLERİ VERİTABANINDAN DA SİL
        // (Eğer sipariş ID varsa ve gelen listede olmayan eski kayıtlar varsa sil)
        if ($request->order_id) {
            // Not: Mutfakta "hazırlanıyor" veya "hazır" olanları silmeyi engelleyebilirsiniz.
            // Şimdilik sadece listede olmayanları siliyoruz.
            OrderItem::where('order_id', $order->id)
                ->whereNotIn('id', $incomingItemIds)
                ->delete();
        }

        // Toplam tutarı güncelle
        $order->total_amount = $totalAmount;
        $order->save();

        // 4. ÖDEME KAYDI (Varsa)
        if ($request->payment_method != 'pending') {
            $existingPayment = Payment::where('order_id', $order->id)->first();
            if (!$existingPayment) {
                Payment::create([
                    'order_id'       => $order->id,
                    'amount'         => $totalAmount,
                    'payment_method' => $request->payment_method,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'İşlem Başarılı']);
    }

    public function getTableOrder($tableId)
    {
        $order = Order::where('dining_table_id', $tableId)
            ->where('payment_status', 'unpaid')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['orderItems.product', 'orderItems.variations'])
            ->first();

        if ($order) {
            $formattedCart = $order->orderItems->map(function($item) {
                $loadedVariations = $item->variations->map(function($v) {
                    return [
                        'id' => $v->product_variation_id,
                        'name' => $v->variation_name,
                        'price' => (float)$v->price
                    ];
                })->toArray();

                $varIds = array_column($loadedVariations, 'id');
                sort($varIds);
                $uniqueId = $item->product_id . ($varIds ? '_' . implode('_', $varIds) : '');

                return [
                    'id' => $item->product_id,
                    'order_item_id' => $item->id, // <--- KRİTİK: DB ID'sini Frontend'e gönderiyoruz
                    'unique_id' => $uniqueId,
                    'name' => $item->product ? $item->product->name : 'Silinmiş Ürün',
                    'price' => (float)$item->unit_price,
                    'base_price' => (float)($item->product->price ?? 0),
                    'image' => asset($item->product->image ?? 'assets/images/ecommerce/png/1.png'),
                    'quantity' => $item->quantity,
                    'status' => $item->status, // <--- Mutfak durumunu da gönderiyoruz
                    'variations' => $loadedVariations
                ];
            });

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'customer_name' => $order->customer_name,
                'cart' => $formattedCart
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Masada aktif sipariş yok.']);
    }
}
