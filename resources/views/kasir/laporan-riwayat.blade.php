@extends('layouts.app')

@section('title', 'Riwayat Laporan')
@section('page_title', 'Riwayat Laporan')
@section('page_sub', 'Arsip laporan harian Anda')

@section('styles')
<style>
    /* Toolbar */
    .toolbar {
        display: flex; align-items: flex-end; gap: 12px;
        flex-wrap: wrap; margin-bottom: 18px;
    }

    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-label {
        font-size: 10.5px; font-weight: 700;
        color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px;
    }
    .filter-input, .filter-select {
        padding: 8px 12px; background: var(--bg2);
        border: 1px solid var(--border); border-radius: 8px;
        font-family: inherit; font-size: 12.5px; color: var(--text);
        outline: none; transition: border-color 0.15s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .filter-input:focus, .filter-select:focus { border-color: var(--accent); }

    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; border-radius: 8px;
        font-family: inherit; font-size: 12px; font-weight: 600;
        cursor: pointer; border: none; text-decoration: none; transition: all 0.15s;
    }
    .btn-primary { background: var(--accent); color: #fff; box-shadow: 0 2px 8px rgba(37,99,235,0.25); }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-ghost  { background: var(--bg2); color: var(--text2); border: 1px solid var(--border); }
    .btn-ghost:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }
    .btn-new {
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: #fff; box-shadow: 0 2px 10px rgba(37,99,235,0.3);
    }
    .btn-new:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(37,99,235,0.4); }

    /* Cards Grid */
    .laporan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 14px;
    }

    .laporan-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: 14px; overflow: hidden;
        box-shadow: var(--shadow);
        transition: box-shadow 0.2s, transform 0.2s;
        cursor: pointer;
    }
    .laporan-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .card-top {
        padding: 16px 18px;
        background: var(--bg3);
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }

    .card-date { font-size: 13px; font-weight: 800; color: var(--text); }
    .card-day  { font-size: 11px; color: var(--text3); margin-top: 2px; }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 99px;
        font-size: 10.5px; font-weight: 700;
    }
    .badge-baik   { background: #f0fdf4; color: var(--success); }
    .badge-sedang { background: #fffbeb; color: var(--warning); }
    .badge-buruk  { background: #fef2f2; color: var(--danger); }

    .card-body { padding: 16px 18px; }

    .stat-row {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 10px; margin-bottom: 12px;
    }

    .stat-mini {
        background: var(--bg3); border: 1px solid var(--border);
        border-radius: 9px; padding: 10px 12px;
    }
    .stat-mini-label { font-size: 10px; color: var(--text3); font-weight: 600; margin-bottom: 3px; }
    .stat-mini-val   { font-size: 14px; font-weight: 800; color: var(--text); }
    .stat-mini-val.blue  { color: var(--accent); }
    .stat-mini-val.green { color: var(--success); }

    .pay-row {
        display: flex; gap: 8px; margin-bottom: 12px;
    }
    .pay-chip {
        flex: 1; background: var(--bg3); border: 1px solid var(--border);
        border-radius: 8px; padding: 8px 10px; text-align: center;
    }
    .pay-chip-icon  { font-size: 16px; margin-bottom: 3px; }
    .pay-chip-label { font-size: 9.5px; color: var(--text3); font-weight: 600; }
    .pay-chip-val   { font-size: 12px; font-weight: 800; color: var(--text); margin-top: 2px; }

    .catatan-box {
        background: var(--bg3); border: 1px solid var(--border);
        border-radius: 9px; padding: 10px 12px;
        font-size: 11.5px; color: var(--text2);
        line-height: 1.5;
    }
    .catatan-box.empty { color: var(--text3); font-style: italic; }

    .card-footer {
        padding: 10px 18px;
        border-top: 1px solid var(--border);
        background: var(--bg3);
        display: flex; align-items: center; justify-content: space-between;
        font-size: 11px; color: var(--text3);
    }

    /* Empty */
    .empty-state {
        text-align: center; padding: 72px 20px;
        color: var(--text3);
    }
    .empty-icon  { font-size: 48px; margin-bottom: 12px; opacity: 0.3; }
    .empty-title { font-size: 15px; font-weight: 700; color: var(--text2); margin-bottom: 6px; }
    .empty-sub   { font-size: 12px; margin-bottom: 20px; }

    /* Modal */
    .modal {
        display: none; position: fixed; inset: 0;
        background: rgba(15,23,42,0.5); backdrop-filter: blur(4px);
        z-index: 1000; align-items: center; justify-content: center;
    }
    .modal.active { display: flex; }

    .modal-box {
        background: var(--bg2); border: 1px solid var(--border);
        border-radius: 18px; width: 520px; max-width: 96vw;
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
    .modal-body { padding: 22px 24px; }

    .detail-section-title {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.8px; color: var(--text3); margin-bottom: 10px; margin-top: 16px;
    }
    .detail-section-title:first-child { margin-top: 0; }

    .detail-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 4px;
    }
    .detail-cell {
        background: var(--bg3); border: 1px solid var(--border);
        border-radius: 9px; padding: 11px 14px;
    }
    .detail-cell-label {
        font-size: 10px; font-weight: 700; color: var(--text3);
        text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;
    }
    .detail-cell-value { font-size: 13px; font-weight: 700; color: var(--text); }

    @media (max-width: 768px) {
        .laporan-grid { grid-template-columns: 1fr; }
        .toolbar { flex-direction: column; align-items: stretch; }
    }
</style>
@endsection

@section('content')

{{-- Toolbar --}}
<form method="GET" action="{{ route('kasir.laporan.riwayat') }}">
<div class="toolbar">
    <div class="filter-group">
        <span class="filter-label">Bulan</span>
        <select name="bulan" class="filter-select">
            @foreach(range(1, 12) as $m)
            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <span class="filter-label">Tahun</span>
        <select name="tahun" class="filter-select">
            @foreach(range(now()->year, now()->year - 2) as $y)
            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary" style="align-self:flex-end">🔍 Filter</button>
    <a href="{{ route('kasir.laporan.riwayat') }}" class="btn btn-ghost" style="align-self:flex-end">↺ Reset</a>
    <a href="{{ route('kasir.laporan.input') }}" class="btn btn-new" style="margin-left:auto;align-self:flex-end">
        ＋ Input Laporan Hari Ini
    </a>
</div>
</form>

{{-- Grid Laporan --}}
@if($laporan->count() > 0)
<div class="laporan-grid">
    @foreach($laporan as $l)
    @php
        $kondisiIcon  = $l->kondisi_toko === 'baik' ? '😊' : ($l->kondisi_toko === 'sedang' ? '😐' : '😟');
        $kondisiClass = 'badge-' . $l->kondisi_toko;
        $hari = \Carbon\Carbon::parse($l->tanggal)->isoFormat('dddd');
    @endphp
    <div class="laporan-card" onclick="showLaporanDetail({{ $l->id }})">
        <div class="card-top">
            <div>
                <div class="card-date">{{ \Carbon\Carbon::parse($l->tanggal)->format('d M Y') }}</div>
                <div class="card-day">{{ $hari }}</div>
            </div>
            <span class="badge {{ $kondisiClass }}">
                {{ $kondisiIcon }} {{ ucfirst($l->kondisi_toko) }}
            </span>
        </div>

        <div class="card-body">
            <div class="stat-row">
                <div class="stat-mini">
                    <div class="stat-mini-label">Total Transaksi</div>
                    <div class="stat-mini-val blue">{{ $l->total_transaksi }} txn</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-label">Total Pendapatan</div>
                    <div class="stat-mini-val green">Rp {{ number_format($l->total_pendapatan, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="pay-row">
                <div class="pay-chip">
                    <div class="pay-chip-icon">💵</div>
                    <div class="pay-chip-label">Tunai</div>
                    <div class="pay-chip-val">Rp {{ number_format($l->pendapatan_tunai, 0, ',', '.') }}</div>
                </div>
                <div class="pay-chip">
                    <div class="pay-chip-icon">📱</div>
                    <div class="pay-chip-label">QRIS</div>
                    <div class="pay-chip-val">Rp {{ number_format($l->pendapatan_qris, 0, ',', '.') }}</div>
                </div>
            </div>

            @if($l->catatan_kejadian)
            <div class="catatan-box">📌 {{ Str::limit($l->catatan_kejadian, 80) }}</div>
            @else
            <div class="catatan-box empty">Tidak ada catatan kejadian</div>
            @endif
        </div>

        <div class="card-footer">
            <span>⏰ {{ $l->jam_mulai }} – {{ $l->jam_selesai }}</span>
            <span>Submit: {{ \Carbon\Carbon::parse($l->created_at)->format('H:i') }} WIB</span>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
<div style="margin-top:18px;display:flex;justify-content:center">
    {{ $laporan->withQueryString()->links() }}
</div>

@else
<div class="empty-state">
    <div class="empty-icon">📋</div>
    <div class="empty-title">Belum ada laporan</div>
    <div class="empty-sub">Tidak ada laporan pada periode yang dipilih</div>
    <a href="{{ route('kasir.laporan.input') }}" class="btn btn-new" style="display:inline-flex">
        ＋ Buat Laporan Hari Ini
    </a>
</div>
@endif

{{-- Modal Detail Laporan --}}
<div class="modal" id="detailModal">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-title">📋 Detail Laporan</div>
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
    const laporanData = {
        @foreach($laporan as $l)
        {{ $l->id }}: {
            tanggal         : "{{ \Carbon\Carbon::parse($l->tanggal)->isoFormat('dddd, D MMMM Y') }}",
            jam_mulai       : "{{ $l->jam_mulai }}",
            jam_selesai     : "{{ $l->jam_selesai }}",
            kondisi         : "{{ $l->kondisi_toko }}",
            kondisiIcon     : "{{ $l->kondisi_toko === 'baik' ? '😊' : ($l->kondisi_toko === 'sedang' ? '😐' : '😟') }}",
            total_transaksi : {{ $l->total_transaksi }},
            total_pendapatan: {{ $l->total_pendapatan }},
            tunai           : {{ $l->pendapatan_tunai }},
            qris            : {{ $l->pendapatan_qris }},
            catatan         : `{{ addslashes($l->catatan_kejadian ?? '') }}`,
            saran           : `{{ addslashes($l->saran ?? '') }}`,
            submit_at       : "{{ \Carbon\Carbon::parse($l->created_at)->format('H:i') }} WIB",
        },
        @endforeach
    };

    function showLaporanDetail(id) {
        const l = laporanData[id];
        if (!l) return;

        const kondisiMap = {
            'baik'  : '<span style="background:#f0fdf4;color:#16a34a;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700">😊 Baik</span>',
            'sedang': '<span style="background:#fffbeb;color:#d97706;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700">😐 Sedang</span>',
            'buruk' : '<span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700">😟 Buruk</span>',
        };

        document.getElementById('modalContent').innerHTML = `
            <div class="detail-section-title">Informasi Shift</div>
            <div class="detail-grid">
                <div class="detail-cell">
                    <div class="detail-cell-label">Tanggal</div>
                    <div class="detail-cell-value">${l.tanggal}</div>
                </div>
                <div class="detail-cell">
                    <div class="detail-cell-label">Jam Shift</div>
                    <div class="detail-cell-value">${l.jam_mulai} – ${l.jam_selesai}</div>
                </div>
                <div class="detail-cell">
                    <div class="detail-cell-label">Kondisi Toko</div>
                    <div class="detail-cell-value">${kondisiMap[l.kondisi]}</div>
                </div>
                <div class="detail-cell">
                    <div class="detail-cell-label">Waktu Submit</div>
                    <div class="detail-cell-value">${l.submit_at}</div>
                </div>
            </div>

            <div class="detail-section-title">Ringkasan Penjualan</div>
            <div class="detail-grid">
                <div class="detail-cell">
                    <div class="detail-cell-label">Total Transaksi</div>
                    <div class="detail-cell-value" style="color:var(--accent)">${l.total_transaksi} transaksi</div>
                </div>
                <div class="detail-cell">
                    <div class="detail-cell-label">Total Pendapatan</div>
                    <div class="detail-cell-value" style="color:var(--success)">Rp ${l.total_pendapatan.toLocaleString('id-ID')}</div>
                </div>
                <div class="detail-cell">
                    <div class="detail-cell-label">💵 Tunai</div>
                    <div class="detail-cell-value">Rp ${l.tunai.toLocaleString('id-ID')}</div>
                </div>
                <div class="detail-cell">
                    <div class="detail-cell-label">📱 QRIS</div>
                    <div class="detail-cell-value">Rp ${l.qris.toLocaleString('id-ID')}</div>
                </div>
            </div>

            <div class="detail-section-title">Catatan Kejadian</div>
            <div style="background:var(--bg3);border:1px solid var(--border);border-radius:9px;padding:12px 14px;font-size:12.5px;color:${l.catatan ? 'var(--text)' : 'var(--text3)'};font-style:${l.catatan ? 'normal' : 'italic'};line-height:1.6">
                ${l.catatan || 'Tidak ada catatan kejadian'}
            </div>

            ${l.saran ? `
            <div class="detail-section-title">Saran / Masukan</div>
            <div style="background:var(--accent-light);border:1px solid #bfdbfe;border-radius:9px;padding:12px 14px;font-size:12.5px;color:var(--text);line-height:1.6">
                ${l.saran}
            </div>` : ''}

            <div style="margin-top:18px">
                <button onclick="closeModal()" style="width:100%;padding:12px;background:var(--accent);color:#fff;border:none;border-radius:9px;font-family:inherit;font-size:13px;font-weight:700;cursor:pointer">
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