@extends('layouts.app')

@section('page_title', 'Laporan Transaksi')
@section('page_sub', 'Monitor penjualan dan performa bisnis Anda')

@section('content')

{{-- Tab Navigasi --}}
<div class="flex gap-2 mb-4.5">
    <a href="{{ route('admin.laporan') }}" class="inline-flex items-center gap-1.5 py-2 px-4 rounded-lg text-xs font-semibold no-underline bg-blue-600 text-white shadow-[0_2px_8px_rgba(37,99,235,0.25)]">📊 Transaksi</a>
    <a href="{{ route('admin.laporan.kasir') }}" class="inline-flex items-center gap-1.5 py-2 px-4 rounded-lg text-xs font-semibold no-underline bg-white text-slate-500 border border-slate-200 hover:border-blue-600 hover:text-blue-600 transition-colors">📋 Laporan kasir</a>
</div>

{{-- Filter --}}
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm mb-5">
    <div class="py-3.5 px-4.5 border-b border-slate-200 flex items-center gap-2">
        <span>🔍</span>
        <span class="text-[13px] font-bold text-slate-900">Filter periode</span>
    </div>
    <div class="p-4.5">
        <form action="/admin/laporan" method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex flex-col gap-1.5 flex-1 min-w-45">
                <label for="start_date" class="text-[11px] font-bold text-slate-500 tracking-wide">Tanggal mulai</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                       class="py-2 px-2.5 border border-slate-200 rounded-lg text-[13px] text-slate-900 bg-slate-50 outline-none transition-colors focus:border-blue-600 focus:bg-white focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)]">
            </div>
            <div class="flex flex-col gap-1.5 flex-1 min-w-45">
                <label for="end_date" class="text-[11px] font-bold text-slate-500 tracking-wide">Tanggal akhir</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                       class="py-2 px-2.5 border border-slate-200 rounded-lg text-[13px] text-slate-900 bg-slate-50 outline-none transition-colors focus:border-blue-600 focus:bg-white focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)]">
            </div>
            <button type="submit" class="inline-flex items-center gap-1.5 bg-blue-600 text-white text-[13px] font-semibold py-2.5 px-4 rounded-lg border-0 cursor-pointer no-underline shadow-[0_2px_8px_rgba(37,99,235,0.25)] hover:bg-blue-700 hover:-translate-y-px transition-all whitespace-nowrap">🔍 Filter</button>
            <a href="/admin/laporan" class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-500 text-[13px] font-semibold py-2.5 px-4 rounded-lg border border-slate-200 no-underline hover:bg-slate-200 hover:text-slate-900 transition-colors whitespace-nowrap">↺ Reset</a>
        </form>
    </div>
</div>

{{-- Summary Stats --}}
<div class="grid gap-3.5 mb-5" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
    <div class="bg-white border border-slate-200 rounded-2xl p-4.5 shadow-sm flex items-center gap-3.5">
        <div class="w-11.5 h-11.5 rounded-xl flex items-center justify-center text-xl shrink-0 bg-blue-50 border border-blue-200">🧾</div>
        <div>
            <div class="text-[11px] font-semibold text-slate-400 mb-1">Total transaksi</div>
            <div class="text-[22px] font-extrabold leading-none text-slate-900">{{ $totalTransaksi }}</div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-4.5 shadow-sm flex items-center gap-3.5">
        <div class="w-11.5 h-11.5 rounded-xl flex items-center justify-center text-xl shrink-0 bg-green-50 border border-green-200">💰</div>
        <div>
            <div class="text-[11px] font-semibold text-slate-400 mb-1">Total pendapatan</div>
            <div class="text-base font-extrabold leading-none text-slate-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-4.5 shadow-sm flex items-center gap-3.5">
        <div class="w-11.5 h-11.5 rounded-xl flex items-center justify-center text-xl shrink-0 bg-violet-50 border border-violet-200">📦</div>
        <div>
            <div class="text-[11px] font-semibold text-slate-400 mb-1">Produk terjual</div>
            <div class="text-[22px] font-extrabold leading-none text-slate-900">{{ $totalProdukTerjual }}</div>
        </div>
    </div>
</div>

{{-- Chart + Top Produk --}}
<div class="grid gap-3.5 mb-5 grid-cols-1 md:grid-cols-2">
    {{-- Grafik --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="py-3.5 px-4.5 border-b border-slate-200 flex items-center gap-2">
            <span>📈</span>
            <span class="text-[13px] font-bold text-slate-900">Grafik penjualan (7 hari terakhir)</span>
        </div>
        <div class="p-4.5">
            <div class="relative h-65">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Produk --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="py-3.5 px-4.5 border-b border-slate-200 flex items-center gap-2">
            <span>🏆</span>
            <span class="text-[13px] font-bold text-slate-900">Top 5 produk terlaris</span>
        </div>
        <div class="p-4.5">
            @forelse($topProduk as $item)
            <div class="flex items-center gap-3 py-2.5 px-3.5 rounded-[9px] bg-slate-50 border border-slate-200 mb-2 last:mb-0">
                <div class="w-7 h-7 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-center text-[11px] font-extrabold text-blue-600 shrink-0">#{{ $loop->iteration }}</div>
                <div class="flex-1">
                    <div class="text-[13px] font-semibold text-slate-900">{{ $item->produk->nama }}</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $item->total_terjual }} terjual &middot; Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</div>
                </div>
            </div>
            @empty
            <div class="py-10 px-6 text-center">
                <div class="text-[28px] mb-2.5">📭</div>
                <div class="text-[13px] font-bold text-slate-900 mb-1">Belum ada data</div>
                <div class="text-xs text-slate-400">Tidak ada penjualan pada periode ini</div>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Tabel Transaksi --}}
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    <div class="py-3.5 px-4.5 border-b border-slate-200 flex items-center gap-2">
        <span>📋</span>
        <span class="text-[13px] font-bold text-slate-900">Daftar transaksi</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-[13px]">
            <thead>
                <tr>
                    <th class="bg-slate-50 border-b border-slate-200 py-2.5 px-4 text-left text-[10.5px] font-bold tracking-wide uppercase text-slate-400">No invoice</th>
                    <th class="bg-slate-50 border-b border-slate-200 py-2.5 px-4 text-left text-[10.5px] font-bold tracking-wide uppercase text-slate-400">Tanggal</th>
                    <th class="bg-slate-50 border-b border-slate-200 py-2.5 px-4 text-left text-[10.5px] font-bold tracking-wide uppercase text-slate-400">Kasir</th>
                    <th class="bg-slate-50 border-b border-slate-200 py-2.5 px-4 text-left text-[10.5px] font-bold tracking-wide uppercase text-slate-400">Total</th>
                    <th class="bg-slate-50 border-b border-slate-200 py-2.5 px-4 text-left text-[10.5px] font-bold tracking-wide uppercase text-slate-400">Status</th>
                    <th class="bg-slate-50 border-b border-slate-200 py-2.5 px-4 text-center text-[10.5px] font-bold tracking-wide uppercase text-slate-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                <tr class="border-b border-slate-200 last:border-0 hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-4 align-middle"><span class="font-mono text-xs text-slate-500">{{ $t->nomor_invoice }}</span></td>
                    <td class="py-3.5 px-4 align-middle text-slate-500">{{ $t->tanggal_transaksi->format('d M Y H:i') }}</td>
                    <td class="py-3.5 px-4 align-middle font-semibold text-slate-900">{{ $t->pengguna->nama }}</td>
                    <td class="py-3.5 px-4 align-middle font-bold text-slate-900">Rp {{ number_format($t->total_pembayaran, 0, ',', '.') }}</td>
                    <td class="py-3.5 px-4 align-middle">
                        @php
                            $badgeClass = match($t->status) {
                                'dibayar' => 'bg-green-50 text-green-600 border border-green-200',
                                'pending' => 'bg-amber-50 text-amber-600 border border-amber-200',
                                default   => 'bg-red-50 text-red-600 border border-red-200',
                            };
                            $badgeIcon = match($t->status) {
                                'dibayar' => '✅',
                                'pending' => '⏳',
                                default   => '❌',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1 py-1 px-2.5 rounded-full text-[11px] font-semibold {{ $badgeClass }}">
                            {{ $badgeIcon }} {{ ucfirst($t->status) }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 align-middle text-center">
                        <a href="/admin/laporan/{{ $t->id }}" class="text-blue-600 font-semibold text-xs no-underline py-1.5 px-2.5 rounded-lg bg-blue-50 border border-blue-200 hover:bg-blue-100 transition-colors">Detail →</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="py-10 px-6 text-center">
                            <div class="text-[28px] mb-2.5">📭</div>
                            <div class="text-[13px] font-bold text-slate-900 mb-1">Tidak ada transaksi</div>
                            <div class="text-xs text-slate-400">Tidak ada transaksi pada periode yang dipilih</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="py-3 px-4.5 border-t border-slate-200 bg-slate-50">
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