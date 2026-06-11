@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')
@section('page_sub', 'Ringkasan aktivitas toko hari ini')

@section('styles')
<style>
    /* Welcome Bar */
    .welcome-bar {
        background: linear-gradient(135deg, #1d4ed8 0%, #7c3aed 100%);
        border-radius: 14px;
        padding: 22px 26px;
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 20px;
        color: white;
        box-shadow: 0 4px 16px rgba(29,78,216,0.25);
    }
    .welcome-info h2 { font-size: 18px; font-weight: 800; margin-bottom: 4px; }
    .welcome-info p  { font-size: 12px; opacity: 0.8; }
    .datetime-box    { text-align: right; }
    .clock {
        font-size: 28px; font-weight: 800;
        font-family: 'DM Mono', monospace;
        line-height: 1; color: white;
    }
    .datex { font-size: 11px; opacity: 0.75; margin-top: 4px; }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px; margin-bottom: 20px;
    }

    .stat-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: var(--shadow);
        transition: box-shadow 0.2s, transform 0.2s;
        text-decoration: none; color: var(--text);
        display: block;
    }
    .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }

    .stat-icon-wrap {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; margin-bottom: 12px;
    }
    .stat-label { font-size: 11px; color: var(--text2); font-weight: 600; margin-bottom: 6px; }
    .stat-value { font-size: 22px; font-weight: 800; color: var(--text); line-height: 1; }
    .stat-value.blue   { color: #2563eb; }
    .stat-value.green  { color: #16a34a; }
    .stat-value.orange { color: #d97706; }
    .stat-value.purple { color: #7c3aed; }
    .stat-sub { font-size: 10px; color: var(--text3); margin-top: 5px; }

    /* Section Title */
    .section-title {
        font-size: 11px; font-weight: 700; letter-spacing: 0.8px;
        text-transform: uppercase; color: var(--text3);
        margin-bottom: 10px;
    }

    /* Quick Actions */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px; margin-bottom: 20px;
    }

    .action-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 20px;
        text-decoration: none; color: var(--text);
        transition: all 0.2s; display: flex;
        align-items: center; gap: 14px;
        box-shadow: var(--shadow);
    }
    .action-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .action-card.blue   { border-left: 3px solid #2563eb; }
    .action-card.green  { border-left: 3px solid #16a34a; }
    .action-card.purple { border-left: 3px solid #7c3aed; }

    .action-icon {
        width: 46px; height: 46px; flex-shrink: 0;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
    }
    .action-icon.blue   { background: #eff6ff; }
    .action-icon.green  { background: #f0fdf4; }
    .action-icon.purple { background: #faf5ff; }

    .action-title { font-size: 14px; font-weight: 700; margin-bottom: 3px; }
    .action-desc  { font-size: 11px; color: var(--text2); }
    .action-arrow { margin-left: auto; font-size: 16px; color: var(--text3); }

    /* Bottom grid */
    .bottom-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 16px;
    }

    /* Card */
    .card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }
    .card-header {
        padding: 15px 18px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        background: var(--bg3);
    }
    .card-title { font-size: 13px; font-weight: 700; }
    .card-link  { font-size: 11px; color: var(--accent); text-decoration: none; font-weight: 600; }

    table { width: 100%; border-collapse: collapse; }
    th {
        padding: 10px 14px;
        text-align: left;
        font-size: 10px; font-weight: 700; letter-spacing: 0.7px;
        text-transform: uppercase; color: var(--text3);
        background: var(--bg3);
        border-bottom: 1px solid var(--border);
    }
    td {
        padding: 12px 14px;
        font-size: 12.5px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        color: var(--text);
    }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f8fafc; }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 99px;
        font-size: 10.5px; font-weight: 700;
    }
    .badge-success { background: #f0fdf4; color: #16a34a; }
    .badge-warning { background: #fffbeb; color: #d97706; }
    .badge-danger  { background: #fef2f2; color: #dc2626; }

    .empty-state {
        text-align: center; padding: 40px;
        color: var(--text3); font-size: 13px;
    }

    /* Info Stats Panel */
    .info-panel { padding: 14px; }

    .info-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px;
        border-radius: 10px;
        background: var(--bg3);
        border: 1px solid var(--border);
        margin-bottom: 8px;
        text-decoration: none; color: var(--text);
        transition: all 0.15s;
    }
    .info-item:last-child { margin-bottom: 0; }
    .info-item:hover { border-color: var(--accent); background: var(--accent-light); }

    .info-emoji { font-size: 22px; }
    .info-label  { font-size: 11px; color: var(--text2); font-weight: 500; }
    .info-value  { font-size: 15px; font-weight: 800; color: var(--text); margin-top: 1px; }
    .info-right  { margin-left: auto; font-size: 14px; color: var(--text3); }

    @media (max-width: 1100px) {
        .stats-grid   { grid-template-columns: repeat(2, 1fr); }
        .actions-grid { grid-template-columns: 1fr; }
        .bottom-grid  { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

{{-- Welcome Bar --}}
<div class="welcome-bar">
    <div class="welcome-info">
        <h2>Selamat Datang, Admin! 👋</h2>
        <p>Berikut ringkasan aktivitas toko hari ini</p>
    </div>
    <div class="datetime-box">
        <div class="clock" id="clock">00:00:00</div>
        <div class="datex" id="datex">—</div>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon-wrap" style="background:#eff6ff">💰</div>
        <div class="stat-label">Total Penjualan</div>
        <div class="stat-value blue">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
        <div class="stat-sub">Hari ini (semua kasir)</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap" style="background:#f0fdf4">🛒</div>
        <div class="stat-label">Total Transaksi</div>
        <div class="stat-value green">{{ $totalTransaksi }}</div>
        <div class="stat-sub">Transaksi hari ini</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap" style="background:#fffbeb">📦</div>
        <div class="stat-label">Total Produk</div>
        <div class="stat-value orange">{{ $totalProduk }}</div>
        <div class="stat-sub">Produk terdaftar</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap" style="background:#faf5ff">👥</div>
        <div class="stat-label">Total Kasir</div>
        <div class="stat-value purple">{{ $totalPengguna }}</div>
        <div class="stat-sub">Kasir aktif</div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="section-title">Menu Utama</div>
<div class="actions-grid">
    <a href="/admin/produk" class="action-card blue">
        <div class="action-icon blue">📦</div>
        <div>
            <div class="action-title">Manajemen Produk</div>
            <div class="action-desc">Kelola produk & stok inventori</div>
        </div>
        <div class="action-arrow">→</div>
    </a>
    <a href="/admin/kategori" class="action-card green">
        <div class="action-icon green">🏷️</div>
        <div>
            <div class="action-title">Manajemen Kategori</div>
            <div class="action-desc">Kelola kategori produk</div>
        </div>
        <div class="action-arrow">→</div>
    </a>
    <a href="/admin/laporan" class="action-card purple">
        <div class="action-icon purple">📊</div>
        <div>
            <div class="action-title">Laporan Transaksi</div>
            <div class="action-desc">Lihat laporan & statistik</div>
        </div>
        <div class="action-arrow">→</div>
    </a>
</div>

{{-- Bottom --}}
<div class="bottom-grid">

    {{-- Transaksi Terbaru --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Transaksi Terbaru</div>
            <a href="/admin/laporan" class="card-link">Lihat Semua →</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Kasir</th>
                    <th>Waktu</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiTerbaru as $transaksi)
                <tr>
                    <td style="font-family:'DM Mono',monospace;font-size:11px;color:var(--text2)">
                        #{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td style="font-weight:600">{{ $transaksi->pengguna->nama ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('H:i') }}</td>
                    <td style="font-weight:700">Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($transaksi->metode_pembayaran) }}</td>
                    <td>
                        @if($transaksi->status === 'dibayar')
                            <span class="badge badge-success">● Dibayar</span>
                        @elseif($transaksi->status === 'pending')
                            <span class="badge badge-warning">● Pending</span>
                        @else
                            <span class="badge badge-danger">● {{ ucfirst($transaksi->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">🛒 Belum ada transaksi hari ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Info Panel --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">📌 Ringkasan</div>
        </div>
        <div class="info-panel">
            <a href="/admin/produk" class="info-item">
                <div class="info-emoji">📦</div>
                <div>
                    <div class="info-label">Total Produk</div>
                    <div class="info-value">{{ $totalProduk }} produk</div>
                </div>
                <div class="info-right">→</div>
            </a>
            <a href="/admin/pengguna" class="info-item">
                <div class="info-emoji">👥</div>
                <div>
                    <div class="info-label">Kasir Aktif</div>
                    <div class="info-value">{{ $totalPengguna }} kasir</div>
                </div>
                <div class="info-right">→</div>
            </a>
            <div class="info-item">
                <div class="info-emoji">🛒</div>
                <div>
                    <div class="info-label">Transaksi Hari Ini</div>
                    <div class="info-value">{{ $totalTransaksi }} transaksi</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-emoji">💰</div>
                <div>
                    <div class="info-label">Pendapatan Hari Ini</div>
                    <div class="info-value" style="color:#2563eb">
                        Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    function updateClock() {
        const now    = new Date();
        const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli',
                        'Agustus','September','Oktober','November','Desember'];

        document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID');
        document.getElementById('datex').textContent =
            `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    }

    updateClock();
    setInterval(updateClock, 1000);
</script>
@endsection