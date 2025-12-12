<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
    public function index()
    {
        // Giderleri tarihe göre yeniden eskiye sırala
        $expenses = Expense::with('user')->latest()->get();

        // İstatistikler için hesaplamalar
        $totalExpense = Expense::sum('amount');
        $monthlyExpense = Expense::whereMonth('created_at', Carbon::now()->month)->sum('amount');
        $dailyExpense = Expense::whereDate('created_at', Carbon::today())->sum('amount');

        return view('expenses.index', compact('expenses', 'totalExpense', 'monthlyExpense', 'dailyExpense'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Expense::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'description' => $request->description,
            'user_id' => auth()->id(), // Oturum açan kullanıcı
        ]);

        return redirect()->route('expenses.index')->with('success', 'Gider başarıyla eklendi.');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Gider kaydı silindi.');
    }
}
