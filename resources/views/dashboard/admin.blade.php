@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')
@section('page_sub', 'Ringkasan aktivitas toko hari ini')

@section('content')

{{-- Welcome Bar --}}
<div class="bg-linear-to-br from-blue-700 to-violet-600 rounded-2xl px-6 py-5.5 flex items-center justify-between mb-5 text-white shadow-[0_4px_16px_rgba(29,78,216,0.25)]">
    <div>
        <h2 class="text-lg font-extrabold mb-1">Selamat datang, Admin! 👋</h2>
        <p class="text-xs opacity-80">Berikut ringkasan aktivitas toko hari ini</p>
    </div>
    <div class="text-right">
        <div class="text-[28px] font-extrabold leading-none text-white" id="clock" style="font-family:'DM Mono',monospace">00:00:00</div>
        <div class="text-[11px] opacity-75 mt-1" id="datex">—</div>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-5">
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4.5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-10 h-10 rounded-[10px] flex items-center justify-center text-lg mb-3 bg-blue-50">💰</div>
        <div class="text-[11px] text-slate-500 font-semibold mb-1.5">Total penjualan</div>
        <div class="text-[22px] font-extrabold leading-none text-blue-600">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
        <div class="text-[10px] text-slate-400 mt-1.5">Hari ini (semua kasir)</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4.5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-10 h-10 rounded-[10px] flex items-center justify-center text-lg mb-3 bg-green-50">🛒</div>
        <div class="text-[11px] text-slate-500 font-semibold mb-1.5">Total transaksi</div>
        <div class="text-[22px] font-extrabold leading-none text-green-600">{{ $totalTransaksi }}</div>
        <div class="text-[10px] text-slate-400 mt-1.5">Transaksi hari ini</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4.5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-10 h-10 rounded-[10px] flex items-center justify-center text-lg mb-3 bg-amber-50">📦</div>
        <div class="text-[11px] text-slate-500 font-semibold mb-1.5">Total produk</div>
        <div class="text-[22px] font-extrabold leading-none text-amber-600">{{ $totalProduk }}</div>
        <div class="text-[10px] text-slate-400 mt-1.5">Produk terdaftar</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4.5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-10 h-10 rounded-[10px] flex items-center justify-center text-lg mb-3 bg-violet-50">👥</div>
        <div class="text-[11px] text-slate-500 font-semibold mb-1.5">Total kasir</div>
        <div class="text-[22px] font-extrabold leading-none text-violet-600">{{ $totalPengguna }}</div>
        <div class="text-[10px] text-slate-400 mt-1.5">Kasir aktif</div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="text-[11px] font-bold tracking-wide uppercase text-slate-400 mb-2.5">Menu utama</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-5">
    <a href="/admin/produk" class="bg-white border border-slate-200 border-l-[3px] border-l-blue-600 rounded-2xl p-5 no-underline text-slate-900 flex items-center gap-3.5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-11.5 h-11.5 shrink-0 rounded-xl flex items-center justify-center text-xl bg-blue-50">📦</div>
        <div>
            <div class="text-sm font-bold mb-0.5">Manajemen produk</div>
            <div class="text-[11px] text-slate-500">Kelola produk & stok inventori</div>
        </div>
        <div class="ml-auto text-base text-slate-400">→</div>
    </a>
    <a href="/admin/kategori" class="bg-white border border-slate-200 border-l-[3px] border-l-green-600 rounded-2xl p-5 no-underline text-slate-900 flex items-center gap-3.5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-11.5 h-11.5 shrink-0 rounded-xl flex items-center justify-center text-xl bg-green-50">🏷️</div>
        <div>
            <div class="text-sm font-bold mb-0.5">Manajemen kategori</div>
            <div class="text-[11px] text-slate-500">Kelola kategori produk</div>
        </div>
        <div class="ml-auto text-base text-slate-400">→</div>
    </a>
    <a href="/admin/laporan" class="bg-white border border-slate-200 border-l-[3px] border-l-violet-600 rounded-2xl p-5 no-underline text-slate-900 flex items-center gap-3.5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-11.5 h-11.5 shrink-0 rounded-xl flex items-center justify-center text-xl bg-violet-50">📊</div>
        <div>
            <div class="text-sm font-bold mb-0.5">Laporan transaksi</div>
            <div class="text-[11px] text-slate-500">Lihat laporan & statistik</div>
        </div>
        <div class="ml-auto text-base text-slate-400">→</div>
    </a>
</div>

{{-- Bottom --}}
<div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-4">

    {{-- Transaksi Terbaru --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="py-3.5 px-4.5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="text-[13px] font-bold text-slate-900">Transaksi terbaru</div>
            <a href="/admin/laporan" class="text-[11px] text-blue-600 no-underline font-semibold">Lihat semua →</a>
        </div>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="py-2.5 px-3.5 text-left text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200">No. transaksi</th>
                    <th class="py-2.5 px-3.5 text-left text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200">Kasir</th>
                    <th class="py-2.5 px-3.5 text-left text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200">Waktu</th>
                    <th class="py-2.5 px-3.5 text-left text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200">Total</th>
                    <th class="py-2.5 px-3.5 text-left text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200">Metode</th>
                    <th class="py-2.5 px-3.5 text-left text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiTerbaru as $transaksi)
                <tr class="hover:bg-slate-50">
                    <td class="py-3 px-3.5 text-[12.5px] border-b border-slate-200 align-middle text-slate-500" style="font-family:'DM Mono',monospace">
                        #{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="py-3 px-3.5 text-[12.5px] border-b border-slate-200 align-middle font-semibold text-slate-900">{{ $transaksi->pengguna->nama ?? '—' }}</td>
                    <td class="py-3 px-3.5 text-[12.5px] border-b border-slate-200 align-middle text-slate-900">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('H:i') }}</td>
                    <td class="py-3 px-3.5 text-[12.5px] border-b border-slate-200 align-middle font-bold text-slate-900">Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</td>
                    <td class="py-3 px-3.5 text-[12.5px] border-b border-slate-200 align-middle text-slate-900">{{ ucfirst($transaksi->metode_pembayaran) }}</td>
                    <td class="py-3 px-3.5 text-[12.5px] border-b border-slate-200 align-middle">
                        @if($transaksi->status === 'dibayar')
                            <span class="inline-flex items-center gap-1 py-0.5 px-2.5 rounded-full text-[10.5px] font-bold bg-green-50 text-green-600">● Dibayar</span>
                        @elseif($transaksi->status === 'pending')
                            <span class="inline-flex items-center gap-1 py-0.5 px-2.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-600">● Pending</span>
                        @else
                            <span class="inline-flex items-center gap-1 py-0.5 px-2.5 rounded-full text-[10.5px] font-bold bg-red-50 text-red-600">● {{ ucfirst($transaksi->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-10 text-slate-400 text-[13px]">🛒 Belum ada transaksi hari ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Info Panel --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm h-fit">
        <div class="py-3.5 px-4.5 border-b border-slate-200 bg-slate-50">
            <div class="text-[13px] font-bold text-slate-900">📌 Ringkasan</div>
        </div>
        <div class="p-3.5">
            <a href="/admin/produk" class="flex items-center gap-3 py-3 px-3.5 rounded-[10px] bg-slate-50 border border-slate-200 mb-2 no-underline text-slate-900 hover:border-blue-600 hover:bg-blue-50 transition-colors">
                <div class="text-xl">📦</div>
                <div>
                    <div class="text-[11px] text-slate-500 font-medium">Total produk</div>
                    <div class="text-[15px] font-extrabold text-slate-900 mt-0.5">{{ $totalProduk }} produk</div>
                </div>
                <div class="ml-auto text-sm text-slate-400">→</div>
            </a>
            <a href="/admin/pengguna" class="flex items-center gap-3 py-3 px-3.5 rounded-[10px] bg-slate-50 border border-slate-200 mb-2 no-underline text-slate-900 hover:border-blue-600 hover:bg-blue-50 transition-colors">
                <div class="text-xl">👥</div>
                <div>
                    <div class="text-[11px] text-slate-500 font-medium">Kasir aktif</div>
                    <div class="text-[15px] font-extrabold text-slate-900 mt-0.5">{{ $totalPengguna }} kasir</div>
                </div>
                <div class="ml-auto text-sm text-slate-400">→</div>
            </a>
            <div class="flex items-center gap-3 py-3 px-3.5 rounded-[10px] bg-slate-50 border border-slate-200 mb-2">
                <div class="text-xl">🛒</div>
                <div>
                    <div class="text-[11px] text-slate-500 font-medium">Transaksi hari ini</div>
                    <div class="text-[15px] font-extrabold text-slate-900 mt-0.5">{{ $totalTransaksi }} transaksi</div>
                </div>
            </div>
            <div class="flex items-center gap-3 py-3 px-3.5 rounded-[10px] bg-slate-50 border border-slate-200">
                <div class="text-xl">💰</div>
                <div>
                    <div class="text-[11px] text-slate-500 font-medium">Pendapatan hari ini</div>
                    <div class="text-[15px] font-extrabold text-blue-600 mt-0.5">
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