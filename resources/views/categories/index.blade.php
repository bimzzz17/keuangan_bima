@extends('layouts.app')
@section('title', 'Kategori')

@section('content')
<h4 class="mb-3">Kategori</h4>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <h6>Tambah Kategori</h6>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-2"><input type="text" name="name" class="form-control" placeholder="Nama kategori" required></div>
                <div class="mb-3">
                    <select name="type" class="form-select" required>
                        <option value="expense">Pengeluaran</option>
                        <option value="income">Pemasukan</option>
                        <option value="saving">Tabungan</option>
                    </select>
                </div>
                <button class="btn btn-success w-100">Simpan</button>
            </form>
        </div></div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm"><div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Nama</th><th>Jenis</th><th>Dipakai</th><th></th></tr></thead>
            <tbody>
            @foreach ($categories as $c)
                <tr>
                    <td>{{ $c->name }}</td>
                    <td><span class="badge {{ $c->badge_class }}">{{ $c->type_label }}</span></td>
                    <td>{{ $c->transactions_count }}x</td>
                    <td class="text-end">
                        <form action="{{ route('categories.destroy', $c) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div></div>
    </div>
</div>
@endsection
