@extends('layouts.app')
@section('title', 'Tambah Transaksi')

@section('content')
<h4 class="mb-3">Tambah Transaksi</h4>
<div class="card border-0 shadow-sm" style="max-width:600px"><div class="card-body">
    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf
        @include('transactions._form')
    </form>
</div></div>
@endsection
