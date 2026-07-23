<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaksi->nomor_invoice }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            body { background: white !important; padding: 0 !important; }
            .struk-box { width: 100% !important; }
        }
    </style>
</head>
<body class="font-mono text-xs bg-[#f0f0f0] flex flex-col items-center p-5" style="font-family:'Courier New',monospace">

<div class="struk-box bg-white w-75 px-4 py-5">
    {{-- Header Toko --}}
    <div class="text-center mb-3">
        <div class="text-[15px] font-bold font-sans">{{ config('app.name', 'Warung Gerbang Merapi') }}</div>
        <div class="text-[11px] text-[#333] mt-1 leading-snug">Kali Adem, Kepuharjo, Kec. Cangkringan, Yogyakarta, Daerah Istimewa Yogyakarta 55583</div>
        <div class="text-[11px] mt-1">No. Telp 082209328123</div>
    </div>

    <hr class="border-0 border-t border-dashed border-[#999] my-2.5">

    {{-- Info Transaksi --}}
    <div class="flex justify-between mb-0.5 text-[11px]">
        <span>{{ $transaksi->tanggal_transaksi->format('Y-m-d') }}</span>
        <span>{{ $transaksi->pengguna->nama ?? '-' }}</span>
    </div>
    <div class="flex justify-between mb-0.5 text-[11px]">
        <span>{{ $transaksi->tanggal_transaksi->format('H:i:s') }}</span>
    </div>
    <div class="flex justify-between mb-0.5 text-[11px]">
        <span>No. {{ $transaksi->nomor_invoice }}</span>
    </div>

    <hr class="border-0 border-t border-dashed border-[#999] my-2.5">

    {{-- Detail Produk --}}
    <div class="my-2">
        @foreach($transaksi->detailTransaksi as $i => $detail)
        <div class="mb-2">
            <div class="font-bold text-xs">{{ $i+1 }}. {{ $detail->produk->nama ?? '-' }}</div>
            <div class="flex justify-between text-[11px] text-[#333]">
                <span>{{ $detail->jumlah }} x {{ number_format($detail->harga_satuan, 0, ',', '.') }}</span>
                <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <hr class="border-0 border-t border-dashed border-[#999] my-2.5">

    {{-- Total --}}
    <div class="mt-2">
        <div class="flex justify-between mb-0.5 text-xs">
            <span>Total QTY</span>
            <span>{{ $transaksi->detailTransaksi->sum('jumlah') }}</span>
        </div>
        <div class="flex justify-between mb-0.5 text-xs">
            <span>Sub total</span>
            <span>Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between mb-0.5 text-sm font-bold mt-1">
            <span>Total</span>
            <span>Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between mb-0.5 text-xs">
            <span>Bayar ({{ ucfirst($transaksi->metode_pembayaran) }})</span>
            <span>Rp {{ number_format($transaksi->uang_diterima ?? $transaksi->total_pembayaran, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between mb-0.5 text-xs">
            <span>Kembali</span>
            <span>Rp {{ number_format($transaksi->kembalian ?? 0, 0, ',', '.') }}</span>
        </div>
    </div>

    <hr class="border-0 border-t border-dashed border-[#999] my-2.5">

    {{-- Footer --}}
    <div class="text-center mt-3 text-[11px] text-[#333]">
        <p>Terimakasih telah berbelanja</p>
    </div>
</div>

{{-- Tombol Print (tidak ikut tercetak) --}}
<div class="print:hidden text-center mt-4">
    <button onclick="window.print()"
            class="py-2.5 px-6 bg-blue-600 text-white border-0 rounded-lg text-sm cursor-pointer shadow-lg hover:bg-blue-700 transition-colors font-sans">
        🖨 Print struk
    </button>
</div>

<script>
    // Auto print saat halaman dibuka
    window.onload = function() { window.print(); }
</script>

</body>
</html>