@extends('layouts.app')
@section('title', 'Edit Transaksi')

@section('content')
<h4 class="mb-3">Edit Transaksi</h4>
<div class="card border-0 shadow-sm" style="max-width:600px"><div class="card-body">
    <form action="{{ route('transactions.update', $transaction) }}" method="POST">
        @csrf @method('PUT')
        @include('transactions._form')
    </form>
</div></div>
@endsection
