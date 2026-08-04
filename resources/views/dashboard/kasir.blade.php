@extends('layouts.app')

@section('title', 'Dashboard Kasir')
@section('page_title', 'Dashboard Kasir')
@section('page_sub', 'Selamat datang, ' . $kasirName)

@section('content')

<div class="bg-linear-to-br from-blue-600 to-violet-600 rounded-2xl px-6 py-5.5 flex items-center justify-between mb-5 text-white">
    <div>
        <h2 class="text-lg font-extrabold mb-1">Selamat datang, {{ $kasirName }} 👋</h2>
        <p class="text-xs opacity-85">Semangat melayani pelanggan hari ini</p>
    </div>
    <div class="text-right">
        <div class="text-2xl font-extrabold" id="clock">00:00:00</div>
        <div class="text-[11px] opacity-85 mt-1" id="datex">—</div>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-5">
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4.5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-10 h-10 rounded-[10px] flex items-center justify-center text-lg mb-3 bg-blue-50">💰</div>
        <div class="text-[11px] text-slate-500 font-semibold mb-1.5">Total penjualan</div>
        <div class="text-[22px] font-extrabold leading-none text-blue-600">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
        <div class="text-[10px] text-slate-400 mt-1.5">Hari ini (kasir Anda)</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4.5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-10 h-10 rounded-[10px] flex items-center justify-center text-lg mb-3 bg-green-50">🛒</div>
        <div class="text-[11px] text-slate-500 font-semibold mb-1.5">Total transaksi</div>
        <div class="text-[22px] font-extrabold leading-none text-green-600">{{ $totalTransaksi }}</div>
        <div class="text-[10px] text-slate-400 mt-1.5">Transaksi hari ini</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4.5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-10 h-10 rounded-[10px] flex items-center justify-center text-lg mb-3 bg-amber-50">📊</div>
        <div class="text-[11px] text-slate-500 font-semibold mb-1.5">Rata-rata transaksi</div>
        <div class="text-[22px] font-extrabold leading-none text-amber-600">Rp {{ number_format($rataTransaksi, 0, ',', '.') }}</div>
        <div class="text-[10px] text-slate-400 mt-1.5">Per transaksi hari ini</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4.5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-10 h-10 rounded-[10px] flex items-center justify-center text-lg mb-3 bg-violet-50">📝</div>
        <div class="text-[11px] text-slate-500 font-semibold mb-1.5">Laporan harian</div>
        <div class="text-sm font-extrabold leading-none {{ $statusLaporan === 'Sudah Submit' ? 'text-green-600' : 'text-red-600' }}">{{ $statusLaporan }}</div>
        <div class="text-[10px] text-slate-400 mt-1.5">Status hari ini</div>
    </div>
</div>

{{-- Breakdown metode pembayaran --}}
<div class="bg-white rounded-xl border border-slate-200 px-4.5 py-4 mb-5">
    <div class="text-[11px] font-bold uppercase text-slate-400 mb-3">Breakdown pembayaran hari ini</div>
    <div class="grid grid-cols-2 gap-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-[10px] flex items-center justify-center text-base bg-blue-50">💵</div>
            <div>
                <div class="text-[11px] text-slate-500">Tunai</div>
                <div class="text-sm font-bold text-slate-900">Rp {{ number_format($tunai, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-[10px] flex items-center justify-center text-base bg-violet-50">📱</div>
            <div>
                <div class="text-[11px] text-slate-500">QRIS</div>
                <div class="text-sm font-bold text-slate-900">Rp {{ number_format($qris, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="text-xs font-bold uppercase mb-2.5 text-slate-500">Aksi cepat</div>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
    <a href="{{ route('kasir.transaksi') }}" class="rounded-xl p-4.5 no-underline text-slate-900 transition-all bg-linear-to-br from-blue-50 to-violet-50 border border-blue-200 hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(0,0,0,0.05)]">
        <div class="text-xl mb-2.5">🛒</div>
        <div class="text-[13px] font-bold mb-1">Buka kasir</div>
        <div class="text-[11px] text-slate-500">Mulai transaksi penjualan</div>
    </a>

    <a href="{{ route('kasir.riwayat') }}" class="bg-white rounded-xl p-4.5 border border-slate-200 no-underline text-slate-900 transition-all hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(0,0,0,0.05)]">
        <div class="text-xl mb-2.5">📋</div>
        <div class="text-[13px] font-bold mb-1">Riwayat transaksi</div>
        <div class="text-[11px] text-slate-500">Lihat transaksi hari ini</div>
    </a>

    <a href="{{ route('kasir.laporan.input') }}" class="bg-white rounded-xl p-4.5 border border-slate-200 no-underline text-slate-900 transition-all hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(0,0,0,0.05)]">
        <div class="text-xl mb-2.5">📝</div>
        <div class="text-[13px] font-bold mb-1">Input laporan</div>
        <div class="text-[11px] text-slate-500">Submit laporan harian</div>
    </a>

    <a href="{{ route('kasir.laporan.riwayat') }}" class="bg-white rounded-xl p-4.5 border border-slate-200 no-underline text-slate-900 transition-all hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(0,0,0,0.05)]">
        <div class="text-xl mb-2.5">📊</div>
        <div class="text-[13px] font-bold mb-1">Riwayat laporan</div>
        <div class="text-[11px] text-slate-500">Lihat laporan sebelumnya</div>
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="py-3.5 px-4.5 border-b border-slate-200 flex items-center justify-between">
        <div class="font-bold text-[13px] text-slate-900">Transaksi terakhir</div>
        <a href="{{ route('kasir.riwayat') }}" class="text-[11px] text-blue-600 no-underline">Lihat semua →</a>
    </div>
    <table class="w-full border-collapse">
        <thead>
        <tr>
            <th class="py-2.5 px-3.5 text-xs border-b border-slate-100 bg-slate-50 uppercase text-[10px] text-slate-500 text-left">No</th>
            <th class="py-2.5 px-3.5 text-xs border-b border-slate-100 bg-slate-50 uppercase text-[10px] text-slate-500 text-left">Waktu</th>
            <th class="py-2.5 px-3.5 text-xs border-b border-slate-100 bg-slate-50 uppercase text-[10px] text-slate-500 text-left">Total</th>
            <th class="py-2.5 px-3.5 text-xs border-b border-slate-100 bg-slate-50 uppercase text-[10px] text-slate-500 text-left">Status</th>
        </tr>
        </thead>
        <tbody>
        @forelse($transaksiTerakhir as $t)
        <tr>
            <td class="py-2.5 px-3.5 text-xs border-b border-slate-100 text-slate-900">#{{ str_pad($t->id,4,'0',STR_PAD_LEFT) }}</td>
            <td class="py-2.5 px-3.5 text-xs border-b border-slate-100 text-slate-900">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('H:i') }}</td>
            <td class="py-2.5 px-3.5 text-xs border-b border-slate-100 text-slate-900">Rp {{ number_format($t->total_pembayaran,0,',','.') }}</td>
            <td class="py-2.5 px-3.5 text-xs border-b border-slate-100">
                @if($t->status === 'dibayar')
                    <span class="py-0.5 px-2 rounded-full text-[10px] font-bold bg-green-100 text-green-700">Dibayar</span>
                @elseif($t->status === 'pending')
                    <span class="py-0.5 px-2 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700">Pending</span>
                @else
                    <span class="py-0.5 px-2 rounded-full text-[10px] font-bold bg-red-100 text-red-700">{{ ucfirst($t->status) }}</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center py-7.5 text-slate-400">Belum ada transaksi hari ini</td>
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