<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{
    public function index()
    {
        // Ödemeleri yeniden eskiye getir
        $payments = Payment::with(['order.table', 'order.user'])->latest()->get();

        // İstatistikler
        $totalRevenue = Payment::sum('amount'); // Toplam Ciro
        $todayRevenue = Payment::whereDate('created_at', Carbon::today())->sum('amount'); // Günlük Ciro

        // Ödeme Yöntemi Dağılımı
        $cashTotal = Payment::where('payment_method', 'cash')->sum('amount');
        $creditCardTotal = Payment::where('payment_method', 'credit_card')->sum('amount');

        return view('payments.index', compact('payments', 'totalRevenue', 'todayRevenue', 'cashTotal', 'creditCardTotal'));
    }

    // Manuel ödeme ekleme (Genelde POS'tan yapılır ama düzeltme için gerekebilir)
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card,online,other',
        ]);

        Payment::create($request->all());

        // Sipariş durumunu güncellemek isteyebilirsiniz (Opsiyonel mantık)
        // $order = Order::find($request->order_id);
        // $order->payment_status = 'paid';
        // $order->save();

        return redirect()->back()->with('success', 'Ödeme kaydı manuel olarak eklendi.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->back()->with('success', 'Ödeme kaydı silindi.');
    }
}
