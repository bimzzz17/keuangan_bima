<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('category');

        if ($request->filled('month')) {
            [$year, $mon] = array_map('intval', explode('-', $request->month) + [1 => 1]);
            $query->whereYear('transaction_date', $year)->whereMonth('transaction_date', $mon);
        }

        if (in_array($request->type, ['income', 'expense'], true)) {
            $query->whereHas('category', fn ($q) => $q->where('type', $request->type));
        }

        $transactions = $query->latest('transaction_date')->latest('id')
            ->paginate(15)->withQueryString();

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $categories = Category::orderBy('type')->orderBy('name')->get();
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Transaction::create($this->validated($request));
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Transaction $transaction)
    {
        $categories = Category::orderBy('type')->orderBy('name')->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $transaction->update($this->validated($request));
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id'      => ['required', 'exists:categories,id'],
            'amount'           => ['required', 'integer', 'min:1'],
            'transaction_date' => ['required', 'date'],
            'description'      => ['nullable', 'string', 'max:255'],
        ]);
    }
}
