<div class="mb-3">
    <label class="form-label">Kategori</label>
    <select name="category_id" class="form-select" required>
        <option value="">-- Pilih kategori --</option>
        @foreach ($categories->groupBy('type') as $type => $items)
            <optgroup label="{{ \App\Models\Category::TYPES[$type] ?? $type }}">
                @foreach ($items as $c)
                    <option value="{{ $c->id }}" @selected(old('category_id', $transaction->category_id ?? '') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Jumlah (Rp)</label>
    <input type="number" name="amount" min="1" class="form-control" required
           value="{{ old('amount', $transaction->amount ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Tanggal</label>
    <input type="date" name="transaction_date" class="form-control" required
           value="{{ old('transaction_date', isset($transaction) ? $transaction->transaction_date->format('Y-m-d') : now()->format('Y-m-d')) }}">
</div>
<div class="mb-3">
    <label class="form-label">Keterangan</label>
    <input type="text" name="description" maxlength="255" class="form-control"
           value="{{ old('description', $transaction->description ?? '') }}">
</div>
<button class="btn btn-success">Simpan</button>
<a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Batal</a>
