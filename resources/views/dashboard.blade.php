@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Dashboard</h4>
    <form method="GET" class="d-flex gap-2">
        <input type="month" name="month" value="{{ $month }}" class="form-control" onchange="this.form.submit()">
    </form>
</div>

<div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <div class="text-muted small">Saldo Dompet</div>
            <div class="fs-4 fw-bold {{ $balance >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($balance, 0, ',', '.') }}</div>
            <div class="small text-muted">Pemasukan - pengeluaran - tabungan</div>
        </div></div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <div class="text-muted small">Total Tabungan</div>
            <div class="fs-4 fw-bold text-primary">Rp {{ number_format($totalSaving, 0, ',', '.') }}</div>
            <div class="small text-muted">Akumulasi semua waktu</div>
        </div></div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <div class="text-muted small">Ditabung Bulan Ini</div>
            <div class="fs-4 fw-bold text-primary">Rp {{ number_format($saving, 0, ',', '.') }}</div>
        </div></div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <div class="text-muted small">Pemasukan Bulan Ini</div>
            <div class="fs-4 fw-bold text-success">Rp {{ number_format($income, 0, ',', '.') }}</div>
        </div></div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <div class="text-muted small">Pengeluaran Bulan Ini</div>
            <div class="fs-4 fw-bold text-danger">Rp {{ number_format($expense, 0, ',', '.') }}</div>
        </div></div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100"><div class="card-body">
            <h6 class="mb-1">Total Uang Saya</h6>
            <div class="small text-muted mb-3">Akumulasi seluruh pemasukan (semua waktu)</div>
            @if ($denominator > 0)
                <div class="mx-auto mb-3" style="max-width:280px"><canvas id="chart"></canvas></div>
                <ul class="list-unstyled mb-0">
                    @foreach ($composition as $c)
                        <li class="d-flex justify-content-between align-items-center py-2 border-top">
                            <span>
                                <span class="d-inline-block rounded-circle me-2" style="width:12px;height:12px;background:{{ $c['color'] }}"></span>{{ $c['label'] }}
                            </span>
                            <span class="text-end">
                                <strong>{{ number_format($c['percent'], 1, ',', '.') }}%</strong><br>
                                <small class="text-muted">Rp {{ number_format($c['value'], 0, ',', '.') }}</small>
                            </span>
                        </li>
                    @endforeach
                </ul>
                @if ($balance < 0)
                    <div class="alert alert-warning mt-3 mb-0 small">
                        Pengeluaran + tabungan melebihi total pemasukan, saldo dompet minus Rp {{ number_format(abs($balance), 0, ',', '.') }}.
                    </div>
                @endif
            @else
                <p class="text-muted mb-0">Belum ada data untuk ditampilkan.</p>
            @endif
        </div></div>
    </div>
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100"><div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h6 class="mb-0">Transaksi Terbaru</h6>
                <a href="{{ route('transactions.create') }}" class="btn btn-success btn-sm">+ Tambah</a>
            </div>
            <table class="table table-sm align-middle mb-0">
                <tbody>
                @forelse ($recent as $t)
                    <tr>
                        <td class="text-muted small">{{ $t->transaction_date->format('d M Y') }}</td>
                        <td>{{ $t->category->name }}<div class="small text-muted">{{ $t->description }}</div></td>
                        <td class="text-end fw-semibold {{ $t->category->text_class }}">
                            {{ $t->category->sign }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr><td class="text-muted">Belum ada transaksi.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div></div>
    </div>
</div>
@endsection

@push('scripts')
@if ($denominator > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const totalIncome = {{ $totalIncome }};
    const centerText = {
        id: 'centerText',
        afterDraw(chart) {
            const { ctx, chartArea: { left, right, top, bottom } } = chart;
            const x = (left + right) / 2, y = (top + bottom) / 2;
            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = getComputedStyle(document.body).color;
            ctx.font = '12px sans-serif';
            ctx.fillText('Total Pemasukan', x, y - 12);
            ctx.font = 'bold 15px sans-serif';
            ctx.fillText('Rp ' + totalIncome.toLocaleString('id-ID'), x, y + 8);
            ctx.restore();
        }
    };

    new Chart(document.getElementById('chart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_column($composition, 'label')),
            datasets: [{
                data: @json(array_column($composition, 'value')),
                backgroundColor: @json(array_column($composition, 'color'))
            }]
        },
        options: {
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (c) => {
                            const total = c.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total ? (c.parsed / total * 100).toFixed(1) : 0;
                            return c.label + ': Rp ' + c.parsed.toLocaleString('id-ID') + ' (' + pct + '%)';
                        }
                    }
                }
            }
        },
        plugins: [centerText]
    });
</script>
@endif
@endpush
