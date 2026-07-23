@extends('layouts.app')

@section('page_title', 'Detail Transaksi')
@section('page_sub', $transaksi->nomor_invoice)

@section('content')

<a href="/admin/laporan" class="inline-flex items-center gap-1.5 text-slate-500 text-[13px] font-semibold no-underline py-1.5 px-3 rounded-lg border border-slate-200 bg-white mb-4.5 hover:text-blue-600 hover:border-blue-600 hover:bg-blue-50 transition-colors print:hidden">← Kembali ke laporan</a>

<div class="max-w-205 mx-auto">

    @php
        $badgeClass = match($transaksi->status) {
            'dibayar' => 'bg-green-50 text-green-600 border border-green-200',
            'pending' => 'bg-amber-50 text-amber-600 border border-amber-200',
            default   => 'bg-red-50 text-red-600 border border-red-200',
        };
        $badgeIcon = match($transaksi->status) {
            'dibayar' => '✅',
            'pending' => '⏳',
            default   => '❌',
        };
    @endphp

    {{-- Invoice Header --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-4">
        <div class="p-5.5 border-b border-slate-200 flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-[13px] bg-blue-50 border border-blue-200 flex items-center justify-center text-[22px] shrink-0">🧾</div>
                <div>
                    <div class="text-base font-extrabold text-slate-900">Detail transaksi</div>
                    <div class="font-mono text-[15px] font-medium text-slate-500 mt-0.5">{{ $transaksi->nomor_invoice }}</div>
                </div>
            </div>
            <span class="inline-flex items-center gap-1 py-1.5 px-3.5 rounded-full text-[13px] font-semibold {{ $badgeClass }}">
                {{ $badgeIcon }} {{ ucfirst($transaksi->status) }}
            </span>
        </div>

        {{-- Info Grid --}}
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
            <div class="p-4.5 border-b border-r border-slate-200">
                <div class="text-[10.5px] font-bold tracking-wide uppercase text-slate-400 mb-1.5">Tanggal transaksi</div>
                <div class="text-sm font-semibold text-slate-900">{{ $transaksi->tanggal_transaksi->format('d M Y, H:i') }}</div>
            </div>
            <div class="p-4.5 border-b border-slate-200">
                <div class="text-[10.5px] font-bold tracking-wide uppercase text-slate-400 mb-1.5">Kasir</div>
                <div class="text-sm font-semibold text-slate-900">{{ $transaksi->pengguna->nama }}</div>
            </div>
            <div class="p-4.5 border-r border-slate-200">
                <div class="text-[10.5px] font-bold tracking-wide uppercase text-slate-400 mb-1.5">Metode pembayaran</div>
                <div class="text-sm font-semibold text-slate-900">{{ strtoupper($transaksi->metode_pembayaran) }}</div>
            </div>
            <div class="p-4.5">
                <div class="text-[10.5px] font-bold tracking-wide uppercase text-slate-400 mb-1.5">No. invoice</div>
                <div class="font-mono text-[13px] font-semibold text-slate-900">{{ $transaksi->nomor_invoice }}</div>
            </div>
        </div>
    </div>

    {{-- Detail Produk --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-4">
        <div class="py-3.5 px-5 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <span>📦</span>
            <span class="text-[13px] font-bold text-slate-900">Detail produk</span>
        </div>
        <div class="p-4">
            @foreach($transaksi->detailTransaksi as $detail)
            <div class="flex items-center gap-3.5 py-3 px-3.5 rounded-[10px] border border-slate-200 bg-slate-50 mb-2 last:mb-0">
                <div class="w-9.5 h-9.5 rounded-[10px] bg-blue-50 border border-blue-200 flex items-center justify-center text-base shrink-0">🛍️</div>
                <div class="flex-1">
                    <div class="text-[13px] font-bold text-slate-900">{{ $detail->produk->nama ?? 'Produk dihapus' }}</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }} × {{ $detail->jumlah }}</div>
                </div>
                <div class="ml-auto text-sm font-extrabold text-slate-900 shrink-0">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>

        {{-- Total --}}
        <div class="py-4.5 px-5.5 border-t-2 border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="text-sm font-bold text-slate-500">Total pembayaran</div>
            <div class="text-[26px] font-extrabold text-blue-600">Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex gap-2.5 mt-4.5 print:hidden">
        <button onclick="window.print()" class="flex-1 inline-flex items-center justify-center gap-1.5 bg-blue-600 text-white text-[13px] font-semibold py-2.5 px-4 rounded-[9px] border-0 cursor-pointer no-underline shadow-[0_2px_8px_rgba(37,99,235,0.25)] hover:bg-blue-700 hover:-translate-y-px transition-all">🖨️ Cetak struk</button>
        <a href="/admin/laporan" class="flex-1 inline-flex items-center justify-center gap-1.5 bg-slate-50 text-slate-500 text-[13px] font-semibold py-2.5 px-4 rounded-[9px] border border-slate-200 no-underline hover:bg-slate-200 hover:text-slate-900 transition-colors">← Kembali</a>
    </div>

</div>
@endsection