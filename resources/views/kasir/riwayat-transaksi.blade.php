@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi')
@section('page_sub', 'Daftar semua transaksi Anda')

@section('styles')
<style>
    /* Filter Bar */
    .filter-bar {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 18px;
        display: flex; align-items: flex-end; gap: 12px;
        flex-wrap: wrap;
        box-shadow: var(--shadow);
    }

    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-label {
        font-size: 10.5px; font-weight: 700;
        color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px;
    }

    .filter-input, .filter-select {
        padding: 8px 12px;
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-family: inherit; font-size: 12.5px;
        color: var(--text); outline: none;
        transition: border-color 0.15s;
    }
    .filter-input:focus, .filter-select:focus { border-color: var(--accent); }

    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; border-radius: 8px;
        font-family: inherit; font-size: 12px; font-weight: 600;
        cursor: pointer; border: none; text-decoration: none;
        transition: all 0.15s;
    }
    .btn-primary { background: var(--accent); color: #fff; box-shadow: 0 2px 8px rgba(37,99,235,0.25); }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-ghost {
        background: var(--bg3); color: var(--text2);
        border: 1px solid var(--border);
    }
    .btn-ghost:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }

    /* Summary Strip */
    .summary-strip {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 12px; margin-bottom: 18px;
    }

    .strip-card {
        background: var(--bg2); border: 1px solid var(--border);
        border-radius: 12px; padding: 14px 18px;
        box-shadow: var(--shadow);
        display: flex; align-items: center; gap: 12px;
    }

    .strip-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 17px; flex-shrink: 0;
    }

    .strip-label { font-size: 10.5px; color: var(--text2); font-weight: 600; margin-bottom: 3px; }
    .strip-value { font-size: 18px; font-weight: 800; color: var(--text); line-height: 1; }
    .strip-value.blue  { color: var(--accent); }
    .strip-value.green { color: var(--success); }

    /* Table */
    .table-card {
        background: var(--bg2); border: 1px solid var(--border);
        border-radius: 14px; overflow: hidden;
        box-shadow: var(--shadow);
    }

    table { width: 100%; border-collapse: collapse; }

    thead th {
        padding: 11px 16px; text-align: left;
        font-size: 10px; font-weight: 700; letter-spacing: 0.8px;
        text-transform: uppercase; color: var(--text3);
        background: var(--bg3); border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    tbody td {
        padding: 13px 16px; font-size: 12.5px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle; color: var(--text);
    }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f8fafc; }

    .invoice-no {
        font-family: 'DM Mono', monospace;
        font-size: 11.5px; font-weight: 600; color: var(--text2);
    }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 99px;
        font-size: 10.5px; font-weight: 700;
    }
    .badge-success { background: #f0fdf4; color: var(--success); }
    .badge-warning { background: #fffbeb; color: var(--warning); }
    .badge-danger  { background: #fef2f2; color: var(--danger); }

    .pay-chip {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 99px;
        font-size: 10.5px; font-weight: 600;
        background: var(--bg3); border: 1px solid var(--border); color: var(--text2);
    }

    .btn-detail {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 12px; border-radius: 7px;
        font-size: 11.5px; font-weight: 600;
        background: var(--accent-light); color: var(--accent);
        border: 1px solid #bfdbfe; text-decoration: none;
        transition: all 0.15s; cursor: pointer;
    }
    .btn-detail:hover { background: #dbeafe; }

    .empty-state {
        text-align: center; padding: 52px 20px; color: var(--text3);
    }
    .empty-icon  { font-size: 40px; margin-bottom: 10px; opacity: 0.35; }
    .empty-title { font-size: 14px; font-weight: 700; color: var(--text2); margin-bottom: 4px; }

    .table-footer {
        padding: 12px 16px; border-top: 1px solid var(--border);
        background: var(--bg3);
        display: flex; align-items: center; justify-content: space-between;
        font-size: 11.5px; color: var(--text2);
    }

    /* Modal */
    .modal {
        display: none; position: fixed; inset: 0;
        background: rgba(15,23,42,0.5); backdrop-filter: blur(4px);
        z-index: 1000; align-items: center; justify-content: center;
    }
    .modal.active { display: flex; }

    .modal-box {
        background: var(--bg2); border: 1px solid var(--border);
        border-radius: 18px; width: 480px; max-width: 96vw;
        max-height: 90vh; overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    .modal-head {
        padding: 20px 24px 16px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        position: sticky; top: 0; background: var(--bg2); z-index: 1;
    }
    .modal-title { font-size: 15px; font-weight: 800; }
    .modal-close {
        width: 30px; height: 30px; border: 1px solid var(--border);
        background: var(--bg3); border-radius: 7px; cursor: pointer;
        font-size: 14px; color: var(--text2);
        display: flex; align-items: center; justify-content: center;
        transition: all 0.15s;
    }
    .modal-close:hover { border-color: var(--danger); color: var(--danger); background: #fef2f2; }
    .modal-body { padding: 20px 24px; }

    .info-grid {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 10px; margin-bottom: 18px;
    }
    .info-cell {
        background: var(--bg3); border: 1px solid var(--border);
        border-radius: 10px; padding: 11px 14px;
    }
    .info-cell-label {
        font-size: 10px; font-weight: 700; color: var(--text3);
        text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 4px;
    }
    .info-cell-value { font-size: 13px; font-weight: 700; color: var(--text); }

    .items-section-title {
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.7px; color: var(--text3); margin-bottom: 10px;
    }

    .item-row {
        display: flex; align-items: center; gap: 12px;
        padding: 11px 14px; background: var(--bg3);
        border: 1px solid var(--border); border-radius: 10px; margin-bottom: 8px;
    }
    .item-row:last-child { margin-bottom: 0; }

    .item-icon {
        width: 34px; height: 34px;
        background: var(--accent-light); border: 1px solid #bfdbfe;
        border-radius: 9px; display: flex; align-items: center;
        justify-content: center; font-size: 15px; flex-shrink: 0;
    }

    .item-name  { font-size: 12.5px; font-weight: 700; color: var(--text); margin-bottom: 2px; }
    .item-price { font-size: 11px; color: var(--text2); }
    .item-sub   { margin-left: auto; font-size: 13px; font-weight: 800; color: var(--text); white-space: nowrap; }

    .total-bar {
        background: var(--accent-light); border: 1px solid #bfdbfe;
        border-radius: 10px; padding: 14px 18px;
        display: flex; align-items: center; justify-content: space-between;
        margin-top: 16px;
    }
    .total-label { font-size: 12px; font-weight: 700; color: var(--accent); }
    .total-value { font-size: 20px; font-weight: 800; color: var(--accent); }

    @media (max-width: 768px) {
        .summary-strip { grid-template-columns: 1fr; }
        .filter-bar    { flex-direction: column; align-items: stretch; }
    }
</style>
@endsection

@section('content')

{{-- Filter --}}
<form method="GET" action="{{ route('kasir.riwayat') }}">
<div class="filter-bar">
    <div class="filter-group">
        <span class="filter-label">Tanggal Mulai</span>
        <input type="date" name="start_date" class="filter-input" value="{{ $startDate }}">
    </div>
    <div class="filter-group">
        <span class="filter-label">Tanggal Akhir</span>
        <input type="date" name="end_date" class="filter-input" value="{{ $endDate }}">
    </div>
    <div class="filter-group">
        <span class="filter-label">Status</span>
        <select name="status" class="filter-select">
            <option value="all"        {{ $status === 'all'        ? 'selected' : '' }}>Semua Status</option>
            <option value="dibayar"    {{ $status === 'dibayar'    ? 'selected' : '' }}>Dibayar</option>
            <option value="pending"    {{ $status === 'pending'    ? 'selected' : '' }}>Pending</option>
            <option value="dibatalkan" {{ $status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
    </div>
    <div class="filter-group">
        <span class="filter-label">Metode</span>
        <select name="metode" class="filter-select">
            <option value="all"   {{ $metode === 'all'   ? 'selected' : '' }}>Semua Metode</option>
            <option value="tunai" {{ $metode === 'tunai' ? 'selected' : '' }}>💵 Tunai</option>
            <option value="qris"  {{ $metode === 'qris'  ? 'selected' : '' }}>📱 QRIS</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary" style="align-self:flex-end">🔍 Filter</button>
    <a href="{{ route('kasir.riwayat') }}" class="btn btn-ghost" style="align-self:flex-end">↺ Reset</a>
</div>
</form>

{{-- Summary --}}
<div class="summary-strip">
    <div class="strip-card">
        <div class="strip-icon" style="background:#eff6ff">🛒</div>
        <div>
            <div class="strip-label">Total Transaksi</div>
            <div class="strip-value blue">{{ $transaksi->total() }}</div>
        </div>
    </div>
    <div class="strip-card">
        <div class="strip-icon" style="background:#f0fdf4">💰</div>
        <div>
            <div class="strip-label">Total Pendapatan</div>
            <div class="strip-value green">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="strip-card">
        <div class="strip-icon" style="background:#fffbeb">📊</div>
        <div>
            <div class="strip-label">Rata-rata / Transaksi</div>
            <div class="strip-value">
                Rp {{ $transaksi->total() > 0 ? number_format($totalPendapatan / $transaksi->total(), 0, ',', '.') : 0 }}
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>No. Invoice</th>
                <th>Tanggal & Waktu</th>
                <th>Item</th>
                <th>Total</th>
                <th>Metode</th>
                <th>Status</th>
                <th style="text-align:center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $t)
            <tr>
                <td><span class="invoice-no">{{ $t->nomor_invoice }}</span></td>
                <td>
                    <div style="font-weight:600;font-size:12.5px">
                        {{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d M Y') }}
                    </div>
                    <div style="font-size:11px;color:var(--text3)">
                        {{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('H:i') }} WIB
                    </div>
                </td>
                <td><span style="font-size:12px;font-weight:600">{{ $t->detailTransaksi->count() }} item</span></td>
                <td><span style="font-weight:800;color:var(--accent)">Rp {{ number_format($t->total_pembayaran, 0, ',', '.') }}</span></td>
                <td>
                    <span class="pay-chip">
                        {{ $t->metode_pembayaran === 'tunai' ? '💵' : '📱' }}
                        {{ ucfirst($t->metode_pembayaran) }}
                    </span>
                </td>
                <td>
                    @if($t->status === 'dibayar')
                        <span class="badge badge-success">● Dibayar</span>
                    @elseif($t->status === 'pending')
                        <span class="badge badge-warning">● Pending</span>
                    @else
                        <span class="badge badge-danger">● {{ ucfirst($t->status) }}</span>
                    @endif
                </td>
                <td style="text-align:center">
                    <button class="btn-detail" onclick="showDetail({{ $t->id }})">
                        🔍 Detail
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <div class="empty-icon">🛒</div>
                        <div class="empty-title">Belum ada transaksi</div>
                        <div style="font-size:12px">Tidak ada transaksi pada periode yang dipilih</div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="table-footer">
        <span>Menampilkan <strong>{{ $transaksi->count() }}</strong> dari <strong>{{ $transaksi->total() }}</strong> transaksi</span>
        <div>{{ $transaksi->withQueryString()->links() }}</div>
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal" id="detailModal">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-title">🧾 Detail Transaksi</div>
            <button class="modal-close" onclick="closeModal()">✕</button>
        </div>
        <div class="modal-body" id="modalContent">
            <div style="text-align:center;padding:40px;color:var(--text3)">Memuat data...</div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const transaksiData = {
        @foreach($transaksi as $t)
        {{ $t->id }}: {
            invoice : "{{ $t->nomor_invoice }}",
            tanggal : "{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->isoFormat('D MMMM Y, HH:mm') }}",
            metode  : "{{ ucfirst($t->metode_pembayaran) }}",
            icon    : "{{ $t->metode_pembayaran === 'tunai' ? '💵' : '📱' }}",
            status  : "{{ $t->status }}",
            total   : {{ $t->total_pembayaran }},
            items   : [
                @foreach($t->detailTransaksi as $d)
                {
                    nama     : "{{ addslashes($d->produk->nama ?? 'Produk dihapus') }}",
                    harga    : {{ $d->harga_satuan }},
                    qty      : {{ $d->jumlah }},
                    subtotal : {{ $d->subtotal }}
                },
                @endforeach
            ]
        },
        @endforeach
    };

    function showDetail(id) {
        const t = transaksiData[id];
        if (!t) return;

        const statusMap = {
            'dibayar'    : '<span style="background:#f0fdf4;color:#16a34a;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700">● Dibayar</span>',
            'pending'    : '<span style="background:#fffbeb;color:#d97706;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700">● Pending</span>',
            'dibatalkan' : '<span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700">● Dibatalkan</span>',
        };

        const items = t.items.map(item => `
            <div class="item-row">
                <div class="item-icon">📦</div>
                <div style="flex:1;min-width:0">
                    <div class="item-name">${item.nama}</div>
                    <div class="item-price">Rp ${item.harga.toLocaleString('id-ID')} × ${item.qty}</div>
                </div>
                <div class="item-sub">Rp ${item.subtotal.toLocaleString('id-ID')}</div>
            </div>
        `).join('');

        document.getElementById('modalContent').innerHTML = `
            <div class="info-grid">
                <div class="info-cell">
                    <div class="info-cell-label">No. Invoice</div>
                    <div class="info-cell-value" style="font-family:'DM Mono',monospace;font-size:11.5px">${t.invoice}</div>
                </div>
                <div class="info-cell">
                    <div class="info-cell-label">Tanggal & Waktu</div>
                    <div class="info-cell-value">${t.tanggal}</div>
                </div>
                <div class="info-cell">
                    <div class="info-cell-label">Metode Pembayaran</div>
                    <div class="info-cell-value">${t.icon} ${t.metode}</div>
                </div>
                <div class="info-cell">
                    <div class="info-cell-label">Status</div>
                    <div class="info-cell-value">${statusMap[t.status] || t.status}</div>
                </div>
            </div>
            <div class="items-section-title">Detail Produk (${t.items.length} item)</div>
            ${items}
            <div class="total-bar">
                <span class="total-label">Total Pembayaran</span>
                <span class="total-value">Rp ${t.total.toLocaleString('id-ID')}</span>
            </div>
            <div style="display:flex;gap:8px;margin-top:14px">
                <button onclick="window.print()" style="flex:1;padding:11px;background:var(--bg3);border:1px solid var(--border);border-radius:9px;font-family:inherit;font-size:12px;font-weight:600;cursor:pointer;color:var(--text2)">
                    🖨️ Cetak Struk
                </button>
                <button onclick="closeModal()" style="flex:1;padding:11px;background:var(--accent);color:#fff;border:none;border-radius:9px;font-family:inherit;font-size:12px;font-weight:700;cursor:pointer">
                    Tutup
                </button>
            </div>
        `;

        document.getElementById('detailModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('detailModal').classList.remove('active');
    }

    document.getElementById('detailModal').addEventListener('click', e => {
        if (e.target === document.getElementById('detailModal')) closeModal();
    });
</script>
@endsection