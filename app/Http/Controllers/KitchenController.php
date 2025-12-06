<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        // Özel Sıralama Mantığı:
        // 1. Durum Sıralaması: pending (1) -> preparing (2) -> ready (3) [Ready en sona]
        // 2. Aciliyet Sıralaması: Created_at (Eski siparişler en üste)

        $orders = Order::with(['items.product', 'items.variations', 'table'])
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->orderByRaw("FIELD(status, 'pending', 'preparing', 'ready') ASC") // Ready en sona gider
            ->orderBy('created_at', 'asc') // Kendi grupları içinde en eskiler en üstte (Acil olanlar)
            ->get();

        return view('orders.kitchen', compact('orders'));
    }
    // Sipariş durumunu güncelle (Örn: Hazırlanıyor'a çek veya Hazırla)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        // Eğer sipariş "Hazır" olduysa, içindeki ürünleri de "Hazır" yapabiliriz.
        if ($request->status == 'ready') {
            $order->items()->update(['status' => 'ready']);
        } elseif ($request->status == 'preparing') {
            $order->items()->update(['status' => 'cooking']);
        }

        return redirect()->back()->with('success', 'Sipariş durumu güncellendi.');
    }

    // Tek bir ürünün durumunu güncelle (Opsiyonel: Ürün bazlı tamamlama)
    public function updateItemStatus($id)
    {
        $item = OrderItem::findOrFail($id);
        // Durum döngüsü: waiting -> cooking -> ready
        if ($item->status == 'waiting') $item->status = 'cooking';
        elseif ($item->status == 'cooking') $item->status = 'ready';
        $item->save();

        return redirect()->back();
    }
}
