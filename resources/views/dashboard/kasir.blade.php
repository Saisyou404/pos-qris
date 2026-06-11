@extends('layouts.app')

@section('title', 'Dashboard Kasir')
@section('page_title', 'Dashboard Kasir')
@section('page_sub', 'Selamat datang, ' . $kasirName)

@section('styles')
<style>
.welcome-bar{
    background:linear-gradient(135deg,#2563eb 0%,#7c3aed 100%);
    border-radius:14px;
    padding:22px 26px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
    color:#fff;
}
.welcome-info h2{font-size:18px;font-weight:800;margin-bottom:4px;}
.welcome-info p{font-size:12px;opacity:.85;}

.datetime-box{text-align:right;}
.clock{font-size:26px;font-weight:800;}
.datex{font-size:11px;opacity:.85;margin-top:4px;}

.section-title{
    font-size:12px;
    font-weight:700;
    text-transform:uppercase;
    margin-bottom:10px;
    color:#64748b;
}

.actions-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:12px;
    margin-bottom:20px;
}

.action-card{
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:12px;
    padding:18px;
    text-decoration:none;
    color:#111827;
    transition:.2s;
}
.action-card:hover{
    transform:translateY(-3px);
    box-shadow:0 8px 20px rgba(0,0,0,.05);
}
.action-card.primary{
    background:linear-gradient(135deg,#eff6ff,#f5f3ff);
    border-color:#bfdbfe;
}
.action-icon{
    font-size:22px;
    margin-bottom:10px;
}
.action-title{font-size:13px;font-weight:700;margin-bottom:4px;}
.action-desc{font-size:11px;color:#6b7280;}

.card{
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:12px;
    overflow:hidden;
}
.card-header{
    padding:14px 18px;
    border-bottom:1px solid #e5e7eb;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.card-title{font-weight:700;font-size:13px;}
.card-link{font-size:11px;color:#2563eb;text-decoration:none;}

table{width:100%;border-collapse:collapse;}
th,td{padding:10px 14px;font-size:12px;border-bottom:1px solid #f1f5f9;}
th{background:#f8fafc;text-transform:uppercase;font-size:10px;color:#64748b;}

.badge{padding:3px 8px;border-radius:20px;font-size:10px;font-weight:700;}
.badge-success{background:#dcfce7;color:#15803d;}
.badge-warning{background:#fef9c3;color:#a16207;}
.badge-danger{background:#fee2e2;color:#b91c1c;}

.empty-state{text-align:center;padding:30px;color:#9ca3af;}

@media(max-width:1100px){
    .actions-grid{grid-template-columns:repeat(2,1fr);}
}
</style>
@endsection

@section('content')

<div class="welcome-bar">
    <div class="welcome-info">
        <h2>Selamat Datang, {{ $kasirName }} 👋</h2>
        <p>Semangat melayani pelanggan hari ini</p>
    </div>
    <div class="datetime-box">
        <div class="clock" id="clock">00:00:00</div>
        <div class="datex" id="datex">—</div>
    </div>
</div>

<div class="section-title">Aksi Cepat</div>
<div class="actions-grid">
    <a href="{{ route('kasir.transaksi') }}" class="action-card primary">
        <div class="action-icon">🛒</div>
        <div class="action-title">Buka Kasir</div>
        <div class="action-desc">Mulai transaksi penjualan</div>
    </a>

    <a href="{{ route('kasir.riwayat') }}" class="action-card">
        <div class="action-icon">📋</div>
        <div class="action-title">Riwayat Transaksi</div>
        <div class="action-desc">Lihat transaksi hari ini</div>
    </a>

    <a href="{{ route('kasir.laporan.input') }}" class="action-card">
        <div class="action-icon">📝</div>
        <div class="action-title">Input Laporan</div>
        <div class="action-desc">Submit laporan harian</div>
    </a>

    <a href="{{ route('kasir.laporan.riwayat') }}" class="action-card">
        <div class="action-icon">📊</div>
        <div class="action-title">Riwayat Laporan</div>
        <div class="action-desc">Lihat laporan sebelumnya</div>
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Transaksi Terakhir</div>
        <a href="{{ route('kasir.riwayat') }}" class="card-link">Lihat Semua →</a>
    </div>
    <table>
        <thead>
        <tr>
            <th>No</th>
            <th>Waktu</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @forelse($transaksiTerakhir as $t)
        <tr>
            <td>#{{ str_pad($t->id,4,'0',STR_PAD_LEFT) }}</td>
            <td>{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('H:i') }}</td>
            <td>Rp {{ number_format($t->total_pembayaran,0,',','.') }}</td>
            <td>
                @if($t->status === 'dibayar')
                    <span class="badge badge-success">Dibayar</span>
                @elseif($t->status === 'pending')
                    <span class="badge badge-warning">Pending</span>
                @else
                    <span class="badge badge-danger">{{ ucfirst($t->status) }}</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="empty-state">Belum ada transaksi hari ini</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection

@section('scripts')
<script>
function updateClock(){
    const now=new Date();
    document.getElementById('clock').textContent=now.toLocaleTimeString('id-ID');
    document.getElementById('datex').textContent=now.toLocaleDateString('id-ID',{
        weekday:'long',year:'numeric',month:'long',day:'numeric'
    });
}
updateClock();
setInterval(updateClock,1000);
</script>
@endsection