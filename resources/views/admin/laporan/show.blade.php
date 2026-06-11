@extends('layouts.app')

@section('page_title', 'Detail Transaksi')
@section('page_sub', $transaksi->nomor_invoice)

@section('styles')
.back-link {
    display: inline-flex; align-items: center; gap: 6px;
    color: var(--text2); font-size: 13px; font-weight: 600;
    text-decoration: none; padding: 7px 12px;
    border-radius: 8px; border: 1px solid var(--border);
    background: var(--bg2);
    transition: all 0.15s;
    margin-bottom: 18px;
}

.back-link:hover { color: var(--accent); border-color: var(--accent); background: var(--accent-light); }

.card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 16px;
}

/* ===== INVOICE HEADER ===== */
.invoice-header {
    padding: 22px 24px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.invoice-icon {
    width: 48px; height: 48px;
    border-radius: 13px;
    background: var(--accent-light);
    border: 1px solid #bfdbfe;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}

.invoice-number {
    font-family: 'DM Mono', monospace;
    font-size: 15px; font-weight: 500;
    color: var(--text2);
    margin-top: 3px;
}

/* ===== INFO GRID ===== */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0;
}

.info-item {
    padding: 16px 22px;
    border-bottom: 1px solid var(--border);
    border-right: 1px solid var(--border);
}

.info-item:nth-child(even) { border-right: none; }

@media (max-width: 600px) {
    .info-item { border-right: none; }
}

.info-label { font-size: 10.5px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; color: var(--text3); margin-bottom: 5px; }
.info-value { font-size: 14px; font-weight: 600; color: var(--text); }

.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 11px; border-radius: 99px;
    font-size: 12px; font-weight: 600;
}

.badge-success { background: var(--success-light); color: var(--success); border: 1px solid #bbf7d0; }
.badge-warning { background: var(--warning-light); color: var(--warning); border: 1px solid #fde68a; }
.badge-danger  { background: var(--danger-light);  color: var(--danger);  border: 1px solid #fecaca; }

/* ===== DETAIL PRODUK ===== */
.card-header {
    padding: 13px 20px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 7px;
    background: var(--bg3);
}

.card-header-title { font-size: 13px; font-weight: 700; color: var(--text); }
.card-body { padding: 16px 20px; }

.product-item {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--bg3);
    margin-bottom: 8px;
}

.product-item:last-child { margin-bottom: 0; }

.product-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: var(--accent-light);
    border: 1px solid #bfdbfe;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
}

.product-name { font-size: 13px; font-weight: 700; color: var(--text); }
.product-price { font-size: 11px; color: var(--text3); margin-top: 2px; }

.product-subtotal {
    margin-left: auto;
    font-size: 14px; font-weight: 800; color: var(--text);
    flex-shrink: 0;
}

/* ===== TOTAL BAR ===== */
.total-bar {
    padding: 18px 22px;
    border-top: 2px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: var(--bg3);
}

.total-label { font-size: 14px; font-weight: 700; color: var(--text2); }

.total-value {
    font-size: 26px; font-weight: 800; color: var(--accent);
}

/* ===== ACTION BUTTONS ===== */
.action-row {
    display: flex; gap: 10px; margin-top: 18px;
}

.btn-primary {
    flex: 1;
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    background: var(--accent); color: #fff;
    font-size: 13px; font-weight: 600;
    padding: 11px 16px; border-radius: 9px;
    border: none; cursor: pointer; font-family: inherit;
    text-decoration: none;
    transition: all 0.15s;
    box-shadow: 0 2px 8px rgba(37,99,235,0.25);
}

.btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }

.btn-secondary {
    flex: 1;
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    background: var(--bg3); color: var(--text2);
    font-size: 13px; font-weight: 600;
    padding: 11px 16px; border-radius: 9px;
    border: 1px solid var(--border2); cursor: pointer; font-family: inherit;
    text-decoration: none;
    transition: all 0.15s;
}

.btn-secondary:hover { background: var(--border); color: var(--text); }

/* ===== PRINT ===== */
@media print {
    .sidebar, .header, .back-link, .action-row { display: none !important; }
    .main { margin-left: 0 !important; }
    .card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
}
@endsection

@section('content')

<a href="/admin/laporan" class="back-link">← Kembali ke Laporan</a>

<div style="max-width: 820px; margin: 0 auto;">

    {{-- Invoice Header --}}
    <div class="card">
        <div class="invoice-header">
            <div style="display:flex; align-items:center; gap:14px;">
                <div class="invoice-icon">🧾</div>
                <div>
                    <div style="font-size:16px; font-weight:800; color:var(--text)">Detail Transaksi</div>
                    <div class="invoice-number">{{ $transaksi->nomor_invoice }}</div>
                </div>
            </div>
            <span class="badge {{ $transaksi->status === 'success' ? 'badge-success' : ($transaksi->status === 'pending' ? 'badge-warning' : 'badge-danger') }}" style="font-size:13px; padding: 6px 14px;">
                {{ $transaksi->status === 'success' ? '✅' : ($transaksi->status === 'pending' ? '⏳' : '❌') }}
                {{ ucfirst($transaksi->status) }}
            </span>
        </div>

        {{-- Info Grid --}}
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Tanggal Transaksi</div>
                <div class="info-value">{{ $transaksi->tanggal_transaksi->format('d M Y, H:i') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Kasir</div>
                <div class="info-value">{{ $transaksi->pengguna->nama }}</div>
            </div>
            <div class="info-item" style="border-bottom:none">
                <div class="info-label">Metode Pembayaran</div>
                <div class="info-value">{{ strtoupper($transaksi->metode_pembayaran) }}</div>
            </div>
            <div class="info-item" style="border-bottom:none">
                <div class="info-label">No. Invoice</div>
                <div class="info-value" style="font-family:'DM Mono',monospace; font-size:13px">{{ $transaksi->nomor_invoice }}</div>
            </div>
        </div>
    </div>

    {{-- Detail Produk --}}
    <div class="card">
        <div class="card-header">
            <span>📦</span>
            <span class="card-header-title">Detail Produk</span>
        </div>
        <div class="card-body">
            @foreach($transaksi->detailTransaksi as $detail)
            <div class="product-item">
                <div class="product-icon">🛍️</div>
                <div style="flex:1">
                    <div class="product-name">{{ $detail->produk->nama }}</div>
                    <div class="product-price">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }} × {{ $detail->jumlah }}</div>
                </div>
                <div class="product-subtotal">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>

        {{-- Total --}}
        <div class="total-bar">
            <div class="total-label">Total Pembayaran</div>
            <div class="total-value">Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="action-row">
        <button onclick="window.print()" class="btn-primary">🖨️ Cetak Struk</button>
        <a href="/admin/laporan" class="btn-secondary">← Kembali</a>
    </div>

</div>
@endsection