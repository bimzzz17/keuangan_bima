@extends('layouts.app')
@section('title', 'Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Transaksi</h4>
    <a href="{{ route('transactions.create') }}" class="btn btn-success">+ Tambah Transaksi</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><input type="month" name="month" value="{{ request('month') }}" class="form-control"></div>
    <div class="col-md-3">
        <select name="type" class="form-select">
            <option value="">Semua jenis</option>
            <option value="income" @selected(request('type') === 'income')>Pemasukan</option>
            <option value="expense" @selected(request('type') === 'expense')>Pengeluaran</option>
            <option value="saving" @selected(request('type') === 'saving')>Tabungan</option>
        </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-outline-success">Filter</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="card border-0 shadow-sm"><div class="table-responsive">
<table class="table table-hover align-middle mb-0">
    <thead class="table-light">
        <tr><th>Tanggal</th><th>Kategori</th><th>Keterangan</th><th class="text-end">Jumlah</th><th></th></tr>
    </thead>
    <tbody>
    @forelse ($transactions as $t)
        <tr>
            <td>{{ $t->transaction_date->format('d M Y') }}</td>
            <td>
                <span class="badge {{ $t->category->badge_class }}">{{ $t->category->name }}</span>
            </td>
            <td>{{ $t->description }}</td>
            <td class="text-end fw-semibold {{ $t->category->text_class }}">
                {{ $t->category->sign }} Rp {{ number_format($t->amount, 0, ',', '.') }}
            </td>
            <td class="text-end text-nowrap">
                <a href="{{ route('transactions.edit', $t) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="{{ route('transactions.destroy', $t) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Hapus transaksi ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada transaksi.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>

<div class="mt-3">{{ $transactions->links() }}</div>
@endsection
