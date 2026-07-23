@extends('layouts.app')

@section('title', 'Laporan Kasir')
@section('page_title', 'Laporan Kasir')
@section('page_sub', 'Laporan harian yang disubmit oleh kasir')

@section('content')

{{-- Tab Navigasi --}}
<div class="flex gap-2 mb-4.5">
    <a href="{{ route('admin.laporan') }}" class="inline-flex items-center gap-1.5 py-2 px-4 rounded-lg text-xs font-semibold no-underline bg-white text-slate-500 border border-slate-200 hover:border-blue-600 hover:text-blue-600 transition-colors">📊 Transaksi</a>
    <a href="{{ route('admin.laporan.kasir') }}" class="inline-flex items-center gap-1.5 py-2 px-4 rounded-lg text-xs font-semibold no-underline bg-blue-600 text-white shadow-[0_2px_8px_rgba(37,99,235,0.25)]">📋 Laporan kasir</a>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('admin.laporan.kasir') }}">
<div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 mb-4.5 flex items-end gap-3 flex-wrap shadow-sm">
    <div class="flex flex-col gap-1.5">
        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wide">Kasir</span>
        <select name="kasir_id" class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-[12.5px] text-slate-900 outline-none focus:border-blue-600 transition-colors min-w-40">
            <option value="all" {{ $kasirId === 'all' ? 'selected' : '' }}>Semua kasir</option>
            @foreach($kasirs as $k)
                <option value="{{ $k->id }}" {{ (string) $kasirId === (string) $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex flex-col gap-1.5">
        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wide">Tanggal mulai</span>
        <input type="date" name="start_date" value="{{ $startDate }}"
               class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-[12.5px] text-slate-900 outline-none focus:border-blue-600 transition-colors">
    </div>
    <div class="flex flex-col gap-1.5">
        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wide">Tanggal akhir</span>
        <input type="date" name="end_date" value="{{ $endDate }}"
               class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-[12.5px] text-slate-900 outline-none focus:border-blue-600 transition-colors">
    </div>
    <button type="submit" class="self-end inline-flex items-center gap-1.5 py-2 px-4 rounded-lg text-xs font-semibold cursor-pointer bg-blue-600 text-white shadow-[0_2px_8px_rgba(37,99,235,0.25)] hover:bg-blue-700 transition-colors">🔍 Filter</button>
    <a href="{{ route('admin.laporan.kasir') }}" class="self-end inline-flex items-center gap-1.5 py-2 px-4 rounded-lg text-xs font-semibold no-underline bg-slate-50 text-slate-500 border border-slate-200 hover:border-blue-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">↺ Reset</a>
</div>
</form>

{{-- Summary --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-3.5 mb-4.5">
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm flex items-center gap-3.5">
        <div class="w-10.5 h-10.5 rounded-[11px] flex items-center justify-center text-xl shrink-0 bg-blue-50">📋</div>
        <div>
            <div class="text-[11px] text-slate-500 font-semibold mb-1">Total laporan</div>
            <div class="text-xl font-extrabold leading-none text-blue-600">{{ $totalLaporan }}</div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm flex items-center gap-3.5">
        <div class="w-10.5 h-10.5 rounded-[11px] flex items-center justify-center text-xl shrink-0 bg-green-50">💰</div>
        <div>
            <div class="text-[11px] text-slate-500 font-semibold mb-1">Total pendapatan (sesuai laporan)</div>
            <div class="text-xl font-extrabold leading-none text-green-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm flex items-center gap-3.5">
        <div class="w-10.5 h-10.5 rounded-[11px] flex items-center justify-center text-xl shrink-0 bg-red-50">⚠️</div>
        <div>
            <div class="text-[11px] text-slate-500 font-semibold mb-1">Laporan dengan kendala</div>
            <div class="text-xl font-extrabold leading-none text-red-600">{{ $adaKendala }}</div>
        </div>
    </div>
</div>

{{-- Grid Laporan --}}
@if($laporan->count() > 0)
<div class="grid gap-3.5" style="grid-template-columns:repeat(auto-fill,minmax(340px,1fr))">
    @foreach($laporan as $l)
    @php
        $kondisiIcon = $l->kondisi_toko === 'baik' ? '😊' : ($l->kondisi_toko === 'sedang' ? '😐' : '😟');
        $badgeClass  = match($l->kondisi_toko) {
            'baik'   => 'bg-green-50 text-green-600',
            'sedang' => 'bg-amber-50 text-amber-600',
            default  => 'bg-red-50 text-red-600',
        };
        $hari = \Carbon\Carbon::parse($l->tanggal)->isoFormat('dddd');
    @endphp
    <div onclick="showLaporanDetail({{ $l->id }})"
         class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all cursor-pointer">
        <div class="py-4 px-4.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div>
                <div class="text-[13px] font-extrabold text-slate-900">{{ \Carbon\Carbon::parse($l->tanggal)->format('d M Y') }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">{{ $hari }} &middot; {{ $l->pengguna->nama ?? 'Kasir dihapus' }}</div>
            </div>
            <span class="inline-flex items-center gap-1 py-1 px-2.5 rounded-full text-[10.5px] font-bold {{ $badgeClass }}">
                {{ $kondisiIcon }} {{ ucfirst($l->kondisi_toko) }}
            </span>
        </div>

        <div class="p-4.5">
            <div class="grid grid-cols-2 gap-2.5 mb-3">
                <div class="bg-slate-50 border border-slate-200 rounded-[9px] py-2.5 px-3">
                    <div class="text-[10px] text-slate-400 font-semibold mb-1">Total transaksi</div>
                    <div class="text-sm font-extrabold text-blue-600">{{ $l->total_transaksi }} txn</div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-[9px] py-2.5 px-3">
                    <div class="text-[10px] text-slate-400 font-semibold mb-1">Total pendapatan</div>
                    <div class="text-sm font-extrabold text-green-600">Rp {{ number_format($l->total_pendapatan, 0, ',', '.') }}</div>
                </div>
            </div>

            @if($l->catatan_kejadian)
            <div class="bg-red-50 border border-red-200 rounded-[9px] py-2.5 px-3 text-[11.5px] text-red-600 leading-relaxed">⚠️ {{ Str::limit($l->catatan_kejadian, 80) }}</div>
            @else
            <div class="bg-slate-50 border border-slate-200 rounded-[9px] py-2.5 px-3 text-[11.5px] text-slate-400 italic leading-relaxed">Tidak ada catatan kejadian</div>
            @endif
        </div>

        <div class="py-2.5 px-4.5 border-t border-slate-200 bg-slate-50 flex items-center justify-between text-[11px] text-slate-400">
            <span>⏰ {{ $l->jam_mulai }} – {{ $l->jam_selesai }}</span>
            <span>Submit: {{ \Carbon\Carbon::parse($l->created_at)->format('d M, H:i') }}</span>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4.5 flex justify-center">
    {{ $laporan->withQueryString()->links() }}
</div>

@else
<div class="text-center py-18 px-5 text-slate-400">
    <div class="text-5xl mb-3 opacity-30">📋</div>
    <div class="text-[15px] font-bold text-slate-500 mb-1.5">Belum ada laporan</div>
    <div class="text-xs">Tidak ada laporan kasir pada filter yang dipilih</div>
</div>
@endif

{{-- Modal Detail --}}
<div class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-1000 items-center justify-center" id="detailModal">
    <div class="bg-white border border-slate-200 rounded-[18px] w-130 max-w-[96vw] max-h-[90vh] overflow-y-auto shadow-[0_20px_60px_rgba(0,0,0,0.15)]">
        <div class="py-5 px-6 pb-4 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-1">
            <div class="text-[15px] font-extrabold text-slate-900">📋 Detail laporan kasir</div>
            <button onclick="closeModal()"
                    class="w-7.5 h-7.5 border border-slate-200 bg-slate-50 rounded-lg cursor-pointer text-sm text-slate-500 flex items-center justify-center hover:border-red-600 hover:text-red-600 hover:bg-red-50 transition-colors">✕</button>
        </div>
        <div class="p-6" id="modalContent">
            <div class="text-center py-10 text-slate-400">Memuat data...</div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const laporanData = {
        @foreach($laporan as $l)
        {{ $l->id }}: {
            kasir           : `{{ addslashes($l->pengguna->nama ?? 'Kasir dihapus') }}`,
            tanggal         : "{{ \Carbon\Carbon::parse($l->tanggal)->isoFormat('dddd, D MMMM Y') }}",
            jam_mulai       : "{{ $l->jam_mulai }}",
            jam_selesai     : "{{ $l->jam_selesai }}",
            kondisi         : "{{ $l->kondisi_toko }}",
            total_transaksi : {{ $l->total_transaksi }},
            total_pendapatan: {{ $l->total_pendapatan }},
            tunai           : {{ $l->pendapatan_tunai }},
            qris            : {{ $l->pendapatan_qris }},
            catatan         : `{{ addslashes($l->catatan_kejadian ?? '') }}`,
            saran           : `{{ addslashes($l->saran ?? '') }}`,
            submit_at       : "{{ \Carbon\Carbon::parse($l->created_at)->format('d M Y, H:i') }} WIB",
        },
        @endforeach
    };

    function showLaporanDetail(id) {
        const l = laporanData[id];
        if (!l) return;

        const kondisiMap = {
            'baik'  : '<span class="bg-green-50 text-green-600 py-1 px-2.5 rounded-full text-[11px] font-bold">😊 Baik</span>',
            'sedang': '<span class="bg-amber-50 text-amber-600 py-1 px-2.5 rounded-full text-[11px] font-bold">😐 Sedang</span>',
            'buruk' : '<span class="bg-red-50 text-red-600 py-1 px-2.5 rounded-full text-[11px] font-bold">😟 Buruk</span>',
        };

        document.getElementById('modalContent').innerHTML = `
            <div class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-2.5">Informasi shift</div>
            <div class="grid grid-cols-2 gap-2.5 mb-1">
                <div class="bg-slate-50 border border-slate-200 rounded-[9px] py-2.5 px-3.5">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Kasir</div>
                    <div class="text-[13px] font-bold text-slate-900">${l.kasir}</div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-[9px] py-2.5 px-3.5">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Tanggal</div>
                    <div class="text-[13px] font-bold text-slate-900">${l.tanggal}</div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-[9px] py-2.5 px-3.5">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Jam shift</div>
                    <div class="text-[13px] font-bold text-slate-900">${l.jam_mulai} – ${l.jam_selesai}</div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-[9px] py-2.5 px-3.5">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Kondisi toko</div>
                    <div class="text-[13px] font-bold text-slate-900">${kondisiMap[l.kondisi]}</div>
                </div>
            </div>

            <div class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-2.5 mt-4">Ringkasan penjualan</div>
            <div class="grid grid-cols-2 gap-2.5 mb-1">
                <div class="bg-slate-50 border border-slate-200 rounded-[9px] py-2.5 px-3.5">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Total transaksi</div>
                    <div class="text-[13px] font-bold text-blue-600">${l.total_transaksi} transaksi</div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-[9px] py-2.5 px-3.5">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Total pendapatan</div>
                    <div class="text-[13px] font-bold text-green-600">Rp ${l.total_pendapatan.toLocaleString('id-ID')}</div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-[9px] py-2.5 px-3.5">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">💵 Tunai</div>
                    <div class="text-[13px] font-bold text-slate-900">Rp ${l.tunai.toLocaleString('id-ID')}</div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-[9px] py-2.5 px-3.5">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">📱 QRIS</div>
                    <div class="text-[13px] font-bold text-slate-900">Rp ${l.qris.toLocaleString('id-ID')}</div>
                </div>
            </div>

            <div class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-2.5 mt-4">Catatan kejadian</div>
            <div class="bg-slate-50 border border-slate-200 rounded-[9px] py-3 px-3.5 text-[12.5px] leading-relaxed ${l.catatan ? 'text-slate-900' : 'text-slate-400 italic'}">
                ${l.catatan || 'Tidak ada catatan kejadian'}
            </div>

            ${l.saran ? `
            <div class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-2.5 mt-4">Saran / masukan dari kasir</div>
            <div class="bg-blue-50 border border-blue-200 rounded-[9px] py-3 px-3.5 text-[12.5px] text-slate-900 leading-relaxed">
                ${l.saran}
            </div>` : ''}

            <div class="text-[10.5px] text-slate-400 mt-4">Disubmit: ${l.submit_at}</div>

            <div class="mt-4.5">
                <button onclick="closeModal()" class="w-full py-3 bg-blue-600 text-white border-0 rounded-[9px] text-[13px] font-bold cursor-pointer hover:bg-blue-700 transition-colors">
                    Tutup
                </button>
            </div>
        `;

        document.getElementById('detailModal').classList.remove('hidden');
        document.getElementById('detailModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('detailModal').classList.remove('flex');
    }

    document.getElementById('detailModal').addEventListener('click', e => {
        if (e.target === document.getElementById('detailModal')) closeModal();
    });
</script>
@endsection