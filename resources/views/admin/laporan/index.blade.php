@extends('layouts.app')

@section('page_title', 'Laporan Transaksi')
@section('page_sub', 'Monitor penjualan dan performa bisnis Anda')

@section('styles')
/* ===== FILTER CARD ===== */
.card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 20px;
}

.card-header {
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 8px;
}

.card-header-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--text);
}

.card-body { padding: 18px; }

.filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: flex-end;
}

.form-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 180px; }

.form-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--text2);
    letter-spacing: 0.3px;
}

.form-input {
    padding: 8px 11px;
    border: 1px solid var(--border2);
    border-radius: 8px;
    font-size: 13px;
    font-family: inherit;
    color: var(--text);
    background: var(--bg3);
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.form-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    background: var(--bg2);
}

.btn-primary {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--accent); color: #fff;
    font-size: 13px; font-weight: 600;
    padding: 9px 16px; border-radius: 9px;
    border: none; cursor: pointer; font-family: inherit;
    text-decoration: none;
    transition: all 0.15s;
    box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    white-space: nowrap;
}

.btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }

.btn-secondary {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--bg3); color: var(--text2);
    font-size: 13px; font-weight: 600;
    padding: 9px 16px; border-radius: 9px;
    border: 1px solid var(--border2); cursor: pointer; font-family: inherit;
    text-decoration: none;
    transition: all 0.15s;
    white-space: nowrap;
}

.btn-secondary:hover { background: var(--border); color: var(--text); }

/* ===== STAT CARDS ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 14px;
    margin-bottom: 20px;
}

.stat-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 14px;
}

.stat-icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}

.stat-icon-blue    { background: var(--accent-light);  border: 1px solid #bfdbfe; }
.stat-icon-green   { background: var(--success-light); border: 1px solid #bbf7d0; }
.stat-icon-purple  { background: #f5f3ff;              border: 1px solid #ddd6fe; }

.stat-label { font-size: 11px; font-weight: 600; color: var(--text3); margin-bottom: 4px; }
.stat-value { font-size: 22px; font-weight: 800; color: var(--text); line-height: 1; }

/* ===== 2-COL GRID ===== */
.two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 20px;
}

@media (max-width: 900px) { .two-col { grid-template-columns: 1fr; } }

/* ===== TOP PRODUK ===== */
.top-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-radius: 9px;
    background: var(--bg3);
    border: 1px solid var(--border);
    margin-bottom: 8px;
}

.top-rank {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: var(--accent-light);
    border: 1px solid #bfdbfe;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; color: var(--accent);
    flex-shrink: 0;
}

.top-name { font-size: 13px; font-weight: 600; color: var(--text); }
.top-sub  { font-size: 11px; color: var(--text3); margin-top: 2px; }

/* ===== TABLE ===== */
.table-wrapper { overflow-x: auto; }

table { width: 100%; border-collapse: collapse; font-size: 13px; }

thead th {
    background: var(--bg3);
    border-bottom: 1px solid var(--border);
    padding: 11px 16px;
    text-align: left;
    font-size: 10.5px; font-weight: 700;
    letter-spacing: 0.8px; text-transform: uppercase;
    color: var(--text3);
}

thead th.center { text-align: center; }

tbody tr { border-bottom: 1px solid var(--border); transition: background 0.12s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--bg3); }
tbody td { padding: 13px 16px; vertical-align: middle; }
tbody td.center { text-align: center; }

.badge {
    display: inline-flex; align-items: center;
    padding: 3px 10px; border-radius: 99px;
    font-size: 11px; font-weight: 600;
}

.badge-success { background: var(--success-light); color: var(--success); border: 1px solid #bbf7d0; }
.badge-warning { background: var(--warning-light); color: var(--warning); border: 1px solid #fde68a; }
.badge-danger  { background: var(--danger-light);  color: var(--danger);  border: 1px solid #fecaca; }

.link-accent {
    color: var(--accent); font-weight: 600; font-size: 12px;
    text-decoration: none; padding: 5px 10px;
    border-radius: 7px; background: var(--accent-light);
    border: 1px solid #bfdbfe;
    transition: all 0.12s;
}

.link-accent:hover { background: #dbeafe; color: #1d4ed8; }

.card-footer {
    padding: 12px 18px;
    border-top: 1px solid var(--border);
    background: var(--bg3);
}

.invoice-text {
    font-family: 'DM Mono', monospace;
    font-size: 12px;
    color: var(--text2);
}

.empty-state {
    padding: 40px 24px; text-align: center;
}
.empty-icon { font-size: 28px; margin-bottom: 10px; }
.empty-title { font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
.empty-sub   { font-size: 12px; color: var(--text3); }
@endsection

@section('content')

{{-- Filter --}}
<div class="card">
    <div class="card-header">
        <span>🔍</span>
        <span class="card-header-title">Filter Periode</span>
    </div>
    <div class="card-body">
        <form action="/admin/laporan" method="GET" class="filter-form">
            <div class="form-group">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="form-input">
            </div>
            <div class="form-group">
                <label for="end_date" class="form-label">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="form-input">
            </div>
            <button type="submit" class="btn-primary">🔍 Filter</button>
            <a href="/admin/laporan" class="btn-secondary">↺ Reset</a>
        </form>
    </div>
</div>

{{-- Summary Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-blue">🧾</div>
        <div>
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ $totalTransaksi }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-green">💰</div>
        <div>
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value" style="font-size:16px">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-purple">📦</div>
        <div>
            <div class="stat-label">Produk Terjual</div>
            <div class="stat-value">{{ $totalProdukTerjual }}</div>
        </div>
    </div>
</div>

{{-- Chart + Top Produk --}}
<div class="two-col">
    {{-- Grafik --}}
    <div class="card">
        <div class="card-header">
            <span>📈</span>
            <span class="card-header-title">Grafik Penjualan (7 Hari Terakhir)</span>
        </div>
        <div class="card-body">
            <div style="height:260px; position:relative;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Produk --}}
    <div class="card">
        <div class="card-header">
            <span>🏆</span>
            <span class="card-header-title">Top 5 Produk Terlaris</span>
        </div>
        <div class="card-body">
            @forelse($topProduk as $item)
            <div class="top-item">
                <div class="top-rank">#{{ $loop->iteration }}</div>
                <div style="flex:1">
                    <div class="top-name">{{ $item->produk->nama }}</div>
                    <div class="top-sub">{{ $item->total_terjual }} terjual &middot; Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <div class="empty-title">Belum ada data</div>
                <div class="empty-sub">Tidak ada penjualan pada periode ini</div>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Tabel Transaksi --}}
<div class="card">
    <div class="card-header">
        <span>📋</span>
        <span class="card-header-title">Daftar Transaksi</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No Invoice</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                <tr>
                    <td><span class="invoice-text">{{ $t->nomor_invoice }}</span></td>
                    <td style="color:var(--text2)">{{ $t->tanggal_transaksi->format('d M Y H:i') }}</td>
                    <td style="font-weight:600">{{ $t->pengguna->nama }}</td>
                    <td style="font-weight:700">Rp {{ number_format($t->total_pembayaran, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $t->status === 'success' ? 'badge-success' : ($t->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $t->status === 'success' ? '✅' : ($t->status === 'pending' ? '⏳' : '❌') }}
                            {{ ucfirst($t->status) }}
                        </span>
                    </td>
                    <td class="center">
                        <a href="/admin/laporan/{{ $t->id }}" class="link-accent">Detail →</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <div class="empty-title">Tidak ada transaksi</div>
                            <div class="empty-sub">Tidak ada transaksi pada periode yang dipilih</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $transaksi->links() }}
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($grafikLabels) !!},
        datasets: [{
            label: 'Penjualan (Rp)',
            data: {!! json_encode($grafikValues) !!},
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.08)',
            borderWidth: 2.5,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#2563eb',
            pointRadius: 4,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: 'rgba(226,232,240,0.7)' }, ticks: { font: { size: 11 }, color: '#94a3b8' } },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(226,232,240,0.7)' },
                ticks: {
                    font: { size: 11 }, color: '#94a3b8',
                    callback: v => 'Rp ' + v.toLocaleString('id-ID')
                }
            }
        }
    }
});
</script>
@endsection