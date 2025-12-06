<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\DiningTable;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;
        $data = [];

        // --- TARİH TANIMLAMALARI ---
        $now = Carbon::now();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // 1. ADMIN DASHBOARD VERİLERİ
        if ($role === 'admin') {
            // A) TOPLAM GELİR VE BÜYÜME (Bu Ay vs Geçen Ay)
            $data['total_revenue'] = Order::where('payment_status', 'paid')->sum('total_amount');

            $revenueThisMonth = Order::where('payment_status', 'paid')->where('created_at', '>=', $startOfMonth)->sum('total_amount');
            $revenueLastMonth = Order::where('payment_status', 'paid')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('total_amount');
            $data['revenue_growth'] = $this->calculatePercentage($revenueThisMonth, $revenueLastMonth);

            // B) GÜNLÜK CİRO VE BÜYÜME (Bugün vs Dün)
            $data['daily_revenue'] = Order::where('payment_status', 'paid')->whereDate('created_at', $today)->sum('total_amount');
            $revenueYesterday = Order::where('payment_status', 'paid')->whereDate('created_at', $yesterday)->sum('total_amount');
            $data['daily_growth'] = $this->calculatePercentage($data['daily_revenue'], $revenueYesterday);

            // C) TOPLAM SİPARİŞ VE BÜYÜME (Bu Hafta vs Geçen Hafta)
            $data['total_orders'] = Order::count(); // Toplam (Lifetime)

            $ordersThisWeek = Order::where('created_at', '>=', $startOfWeek)->count();
            $ordersLastWeek = Order::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();
            $data['orders_growth'] = $this->calculatePercentage($ordersThisWeek, $ordersLastWeek);

            // D) KULLANICILAR VE BÜYÜME
            $data['total_users'] = User::count();
            $usersLastMonth = User::where('created_at', '<', $startOfMonth)->count();
            // Basit büyüme: Şu anki toplam vs ay başındaki toplam
            $data['users_growth'] = $this->calculatePercentage($data['total_users'], $usersLastMonth);

            // E) TABLOLAR İÇİN VERİLER
            $data['recent_orders'] = Order::with('user')->latest()->take(10)->get();

            $data['top_products'] = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->select('products.name', 'products.price', DB::raw('count(*) as total'))
                ->groupBy('products.id', 'products.name', 'products.price')
                ->orderBy('total', 'desc')
                ->take(5)
                ->get();
        }

        // 2. GARSON (WAITER) DASHBOARD VERİLERİ
        elseif ($role === 'waiter') {
            // Masaların anlık durumu
            $data['active_tables'] = DiningTable::where('status', 'occupied')->count();
            $data['empty_tables'] = DiningTable::where('status', 'empty')->count();

            // Bekleyen ve Hazır Siparişler
            $data['pending_orders'] = Order::where('status', 'pending')->count();
            $data['ready_orders'] = Order::where('status', 'ready')->count();

            // TRENDLER (Son saatteki hareketlilik)
            // Son 1 saatte boşalan masalar (Log olmadığı için created_at/updated_at ile simüle ediyoruz)
            $data['new_empty_last_hour'] = DiningTable::where('status', 'empty')->where('updated_at', '>=', $now->subHour())->count();
            $data['new_occupied_last_30min'] = DiningTable::where('status', 'occupied')->where('updated_at', '>=', $now->copy()->subMinutes(30))->count();
            $data['new_orders_last_15min'] = Order::where('status', 'pending')->where('created_at', '>=', $now->copy()->subMinutes(15))->count();
            $data['new_ready_last_20min'] = Order::where('status', 'ready')->where('updated_at', '>=', $now->copy()->subMinutes(20))->count();

            // Masaların listesi
            $data['tables'] = DiningTable::all();
        }

        // 3. ŞEF (CHEF) DASHBOARD VERİLERİ
        elseif ($role === 'chef') {
            // Bekleyen ürünler
            $data['pending_items'] = DB::table('order_items')
                ->whereIn('status', ['waiting', 'cooking'])
                ->count();

            // Son 30 dakikada gelen yeni ürünler (Aciliyet trendi için)
            $data['new_items_last_30min'] = DB::table('order_items')
                ->whereIn('status', ['waiting'])
                ->where('created_at', '>=', $now->copy()->subMinutes(30))
                ->count();

            // Kritik stok
            $data['low_stock_ingredients'] = DB::table('ingredients')
                ->whereColumn('stock_quantity', '<=', 'alert_limit')
                ->get();

            // Mutfak Listesi (Grid.js için)
            $data['kitchen_orders'] = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->leftJoin('dining_tables', 'orders.dining_table_id', '=', 'dining_tables.id')
                ->select('order_items.*', 'products.name as product_name', 'dining_tables.name as table_name')
                ->whereIn('order_items.status', ['waiting', 'cooking'])
                ->orderBy('order_items.created_at', 'asc') // Önce gelen önce çıkar
                ->get();
        }

        // 4. KASİYER (CASHIER) DASHBOARD VERİLERİ
        elseif ($role === 'cashier') {
            // NAKİT: Bugün vs Dün
            $data['today_cash'] = DB::table('payments')->where('payment_method', 'cash')->whereDate('created_at', $today)->sum('amount');
            $yesterdayCash = DB::table('payments')->where('payment_method', 'cash')->whereDate('created_at', $yesterday)->sum('amount');
            $data['cash_growth'] = $this->calculatePercentage($data['today_cash'], $yesterdayCash);

            // KREDİ KARTI: Bugün vs Dün
            $data['today_card'] = DB::table('payments')->where('payment_method', 'credit_card')->whereDate('created_at', $today)->sum('amount');
            $yesterdayCard = DB::table('payments')->where('payment_method', 'credit_card')->whereDate('created_at', $yesterday)->sum('amount');
            $data['card_growth'] = $this->calculatePercentage($data['today_card'], $yesterdayCard);

            // Bekleyen Ödemeler
            $data['pending_payments'] = Order::where('payment_status', 'unpaid')->where('status', '!=', 'cancelled')->count();
            // Son 1 saatte ödeme bekleyen yeni masalar
            $data['new_pending_last_hour'] = Order::where('payment_status', 'unpaid')
                ->where('status', 'served') // Servis edilmiş
                ->where('updated_at', '>=', $now->subHour())
                ->count();

            // Ödenmemiş Sipariş Listesi (Grid.js için)
            $data['unpaid_orders'] = Order::with(['diningTable'])
                ->where('payment_status', 'unpaid')
                ->where('status', '!=', 'cancelled')
                ->latest()
                ->get();
        }

        return view('dashboard.index', compact('data', 'role'));
    }

    /**
     * İki değer arasındaki yüzdelik değişimi hesaplar.
     */
    private function calculatePercentage($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0; // Eğer önceki 0 ise ve şu an değer varsa %100 artış kabul edelim.
        }

        $diff = $current - $previous;
        return round(($diff / $previous) * 100, 1);
    }
}
