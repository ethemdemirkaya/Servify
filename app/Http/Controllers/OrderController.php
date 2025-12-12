<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function history(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Order::with(['diningTable', 'user', 'items.product']);

        // Tarih Filtreleri
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Durum Filtresi
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Arama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                    ->orWhere('customer_name', 'like', "%$search%");
            });
        }

        // Pagination ve Filtreleri Koru
        $orders = $query->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('orders.history', compact('orders'));
    }
    public function active()
    {
        // Yetki Kontrolü (Middleware ile de yapılabilir ama garanti olsun)
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'waiter', 'cashier'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // Aktif Siparişler: pending, preparing, ready, served durumları
        $orders = Order::with(['diningTable', 'user'])
            ->whereIn('status', ['pending', 'preparing', 'ready', 'served'])
            ->orderBy('created_at', 'desc') // En yeni en üstte
            ->get();

        // Özet Kartlar İçin İstatistikler
        $stats = [
            'total_active' => $orders->count(),
            'pending' => $orders->where('status', 'pending')->count(),
            'kitchen' => $orders->whereIn('status', ['preparing', 'ready'])->count(),
            'served' => $orders->where('status', 'served')->count(),
        ];

        return view('orders.active', compact('orders', 'stats'));
    }
}
