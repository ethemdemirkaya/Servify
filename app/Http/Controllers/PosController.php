<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function index()
    {
        // Kategorileri ürünleriyle beraber çek (Eğer hiç kategori yoksa boş collection döner, null değil)
        $categories = Category::with(['products' => function($q) {
            $q->where('is_active', true);
        }])->get();

        // Masaları çek
        $tables = DiningTable::all();

        // Son siparişleri çek (Eğer hiç sipariş yoksa boş collection döner)
        $recentOrders = Order::with('user')->latest()->take(10)->get();

        return view('pos.index', compact('categories', 'tables', 'recentOrders'));
    }

    public function store(Request $request)
    {
        // EĞER SİPARİŞ ID VARSA (GÜNCELLEME / ÖDEME ALMA)
        if ($request->order_id) {
            $order = Order::find($request->order_id);
        }
        // SİPARİŞ ID YOKSA (YENİ SİPARİŞ)
        else {
            $order = new Order();
            $order->dining_table_id = $request->dining_table_id;
            $order->user_id = auth()->id();
            $order->status = 'pending';
        }

        // Ortak Alanlar
        $order->customer_name = $request->customer_name;

        // Ödeme alındıysa durumu güncelle
        if ($request->payment_method != 'pending') {
            $order->payment_status = 'paid';
            $order->status = 'completed'; // Siparişi kapat

            // MASAYI BOŞALT
            if ($order->dining_table_id) {
                $table = DiningTable::find($order->dining_table_id);
                $table->status = 'empty';
                $table->save();
            }
        } else {
            // Sadece mutfağa gönderildiyse
            if ($order->dining_table_id) {
                $table = DiningTable::find($order->dining_table_id);
                $table->status = 'occupied'; // Masayı dolu yap
                $table->save();
            }
        }

        $order->save();

        // --- Order Items Kayıt İşlemleri (Eski ürünleri silip yenilerini ekleyebilir veya güncelleyebilirsin) ---
        // Basit yöntem: Eski kalemleri sil, sepettekileri yeniden ekle (Güncelleme mantığı için)
        if($request->order_id) {
            OrderItem::where('order_id', $order->id)->delete();
        }

        $totalAmount = 0;
        foreach ($request->cart as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->unit_price = $item['price'];
            $orderItem->sub_total = $item['price'] * $item['quantity'];
            $orderItem->save();

            $totalAmount += $orderItem->sub_total;
        }

        $order->total_amount = $totalAmount;
        $order->save();

        return response()->json(['success' => true, 'message' => 'İşlem Başarılı']);
    }
    // PosController.php içine ekle

    public function getTableOrder($tableId)
    {
        // Masaya ait, ödenmemiş (unpaid) siparişi bul
        $order = Order::where('dining_table_id', $tableId)
            ->where('payment_status', 'unpaid') // Önemli: Sadece ödenmemişleri getir
            ->where('status', '!=', 'completed') // Tamamlanmamışları getir
            ->where('status', '!=', 'cancelled') // İptal edilmemişleri getir
            ->with('orderItems.product')
            ->first();

        if ($order) {
            $formattedCart = $order->orderItems->map(function($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product ? $item->product->name : 'Silinmiş Ürün', // Ürün silindiyse hata vermesin
                    'price' => (float)$item->unit_price,
                    'image' => asset($item->product->image ?? 'assets/images/ecommerce/png/1.png'),
                    'quantity' => $item->quantity
                ];
            });

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'customer_name' => $order->customer_name,
                'cart' => $formattedCart
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Bu masada aktif bir sipariş bulunamadı.']);
    }
}
