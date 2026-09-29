<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        [$year, $mon] = array_map('intval', explode('-', $month) + [1 => 1]);

        $base = fn () => Transaction::join('categories', 'categories.id', '=', 'transactions.category_id');

        $monthly = fn () => $base()
            ->whereYear('transactions.transaction_date', $year)
            ->whereMonth('transactions.transaction_date', $mon);

        $sumMonth = fn (string $type) => $monthly()->where('categories.type', $type)->sum('transactions.amount');
        $sumAll   = fn (string $type) => $base()->where('categories.type', $type)->sum('transactions.amount');

        $income  = $sumMonth('income');
        $expense = $sumMonth('expense');
        $saving  = $sumMonth('saving');

        $totalIncome  = $sumAll('income');
        $totalExpense = $sumAll('expense');
        $totalSaving  = $sumAll('saving');
        // Saldo dompet = semua pemasukan - semua pengeluaran - uang yang ditabung
        $balance = $totalIncome - $totalExpense - $totalSaving;

        // Komposisi lingkaran: seluruh pemasukan terbagi jadi sisa saldo, tabungan, dan pengeluaran
        $remaining   = max($balance, 0);
        $denominator = $remaining + $totalSaving + $totalExpense;
        $pct = fn ($v) => $denominator > 0 ? round($v / $denominator * 100, 1) : 0;

        $composition = [
            ['label' => 'Saldo Dompet', 'value' => $remaining,    'percent' => $pct($remaining),    'color' => '#198754'],
            ['label' => 'Tabungan',     'value' => $totalSaving,   'percent' => $pct($totalSaving),  'color' => '#0d6efd'],
            ['label' => 'Pengeluaran',  'value' => $totalExpense,  'percent' => $pct($totalExpense), 'color' => '#dc3545'],
        ];

        $recent = Transaction::with('category')
            ->latest('transaction_date')->latest('id')
            ->take(5)->get();

        return view('dashboard', compact(
            'month', 'income', 'expense', 'saving', 'balance',
            'totalIncome', 'totalSaving', 'totalExpense', 'denominator', 'composition', 'recent'
        ));
    }
}
