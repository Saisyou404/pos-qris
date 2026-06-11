@extends('layouts.app')

@section('title', 'Input Laporan Harian')
@section('page_title', 'Input Laporan Harian')
@section('page_sub', 'Submit laporan shift Anda hari ini')

@section('styles')
<style>
    .page-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 18px;
        align-items: start;
    }

    /* Section Card */
    .section-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: var(--shadow);
        margin-bottom: 18px;
    }

    .section-head {
        padding: 15px 20px;
        border-bottom: 1px solid var(--border);
        background: var(--bg3);
        display: flex; align-items: center; gap: 10px;
    }
    .section-head-icon {
        width: 32px; height: 32px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px;
    }
    .section-head-title { font-size: 13px; font-weight: 700; color: var(--text); }
    .section-head-sub   { font-size: 11px; color: var(--text3); margin-top: 1px; }

    .section-body { padding: 20px; }

    /* Form */
    .form-row {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 14px; margin-bottom: 14px;
    }
    .form-row.single { grid-template-columns: 1fr; }

    .form-group { display: flex; flex-direction: column; gap: 6px; }

    .form-label {
        font-size: 11px; font-weight: 700;
        color: var(--text2); text-transform: uppercase; letter-spacing: 0.5px;
    }

    .form-input, .form-select, .form-textarea {
        padding: 10px 14px;
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 9px;
        font-family: inherit; font-size: 13px;
        color: var(--text); outline: none;
        transition: border-color 0.15s;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: var(--accent);
        background: var(--bg2);
    }
    .form-input[readonly], .form-input:disabled {
        color: var(--text2); cursor: default;
    }
    .form-textarea { resize: vertical; min-height: 90px; line-height: 1.5; }

    .form-hint { font-size: 10.5px; color: var(--text3); margin-top: 2px; }

    /* Auto-fill rows */
    .auto-field {
        position: relative;
    }
    .auto-badge {
        position: absolute; right: 10px; top: 50%;
        transform: translateY(-50%);
        background: var(--success-light); color: var(--success);
        border: 1px solid #bbf7d0;
        font-size: 9px; font-weight: 700; letter-spacing: 0.5px;
        padding: 2px 7px; border-radius: 99px; text-transform: uppercase;
        pointer-events: none;
    }

    /* Shift Summary (right column) */
    .shift-summary {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: var(--shadow);
        position: sticky; top: 80px;
    }

    .shift-head {
        padding: 15px 18px;
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: white;
    }
    .shift-head-title { font-size: 13px; font-weight: 800; margin-bottom: 2px; }
    .shift-head-sub   { font-size: 11px; opacity: 0.75; }

    .shift-stats { padding: 14px; }

    .shift-stat-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px;
        background: var(--bg3); border: 1px solid var(--border);
        border-radius: 10px; margin-bottom: 8px;
    }
    .shift-stat-item:last-child { margin-bottom: 0; }

    .stat-emoji  { font-size: 22px; }
    .stat-label  { font-size: 11px; color: var(--text2); font-weight: 500; margin-bottom: 2px; }
    .stat-val    { font-size: 15px; font-weight: 800; color: var(--text); }
    .stat-val.blue   { color: var(--accent); }
    .stat-val.green  { color: var(--success); }

    /* Kondisi chip selector */
    .kondisi-group {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;
    }

    .kondisi-btn {
        padding: 9px 6px;
        border: 1.5px solid var(--border);
        background: var(--bg3); border-radius: 9px;
        font-family: inherit; font-size: 12px; font-weight: 600;
        cursor: pointer; text-align: center;
        color: var(--text2); transition: all 0.15s;
        display: flex; flex-direction: column;
        align-items: center; gap: 3px;
    }
    .kondisi-btn .k-icon { font-size: 18px; }
    .kondisi-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }
    .kondisi-btn.active.baik   { border-color: var(--success); background: var(--success-light); color: var(--success); }
    .kondisi-btn.active.sedang { border-color: var(--warning); background: var(--warning-light); color: var(--warning); }
    .kondisi-btn.active.buruk  { border-color: var(--danger);  background: var(--danger-light);  color: var(--danger); }

    /* Submit area */
    .submit-area {
        margin-top: 6px;
        display: flex; gap: 10px;
    }

    .btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 11px 20px; border-radius: 9px;
        font-family: inherit; font-size: 13px; font-weight: 700;
        cursor: pointer; border: none; text-decoration: none;
        transition: all 0.15s;
    }
    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: #fff; box-shadow: 0 3px 12px rgba(37,99,235,0.3);
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 5px 18px rgba(37,99,235,0.4); }
    .btn-ghost {
        background: var(--bg3); color: var(--text2);
        border: 1px solid var(--border);
    }
    .btn-ghost:hover { border-color: var(--danger); color: var(--danger); }

    /* Already submitted banner */
    .submitted-banner {
        background: var(--success-light);
        border: 1px solid #bbf7d0;
        border-radius: 14px;
        padding: 20px 24px;
        display: flex; align-items: center; gap: 16px;
        margin-bottom: 18px;
    }
    .submitted-icon { font-size: 36px; }
    .submitted-title { font-size: 15px; font-weight: 800; color: var(--success); margin-bottom: 3px; }
    .submitted-sub   { font-size: 12px; color: var(--text2); }

    @media (max-width: 960px) {
        .page-grid { grid-template-columns: 1fr; }
        .shift-summary { position: static; }
    }
</style>
@endsection

@section('content')

{{-- Sudah Submit Banner --}}
@if($sudahSubmit)
<div class="submitted-banner">
    <div class="submitted-icon">✅</div>
    <div>
        <div class="submitted-title">Laporan Sudah Disubmit</div>
        <div class="submitted-sub">
            Anda telah mengirim laporan hari ini pada
            {{ \Carbon\Carbon::parse($laporanHariIni->created_at)->format('H:i') }} WIB.
            Terima kasih!
        </div>
    </div>
    <a href="{{ route('kasir.laporan.riwayat') }}" class="btn btn-ghost" style="margin-left:auto">
        📋 Lihat Riwayat
    </a>
</div>
@endif

<div class="page-grid">

    {{-- Kiri: Form --}}
    <div>
        @if(!$sudahSubmit)
        <form action="{{ route('kasir.laporan.store') }}" method="POST" id="laporanForm">
        @csrf
        @endif

        {{-- Informasi Shift --}}
        <div class="section-card">
            <div class="section-head">
                <div class="section-head-icon" style="background:#eff6ff">⏰</div>
                <div>
                    <div class="section-head-title">Informasi Shift</div>
                    <div class="section-head-sub">Data otomatis dari sistem</div>
                </div>
            </div>
            <div class="section-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal</label>
                        <div class="auto-field">
                            <input type="text" class="form-input" value="{{ now()->isoFormat('D MMMM Y') }}" readonly>
                            <span class="auto-badge">Auto</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kasir</label>
                        <div class="auto-field">
                            <input type="text" class="form-input" value="{{ session('user_name') }}" readonly>
                            <span class="auto-badge">Auto</span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jam Mulai Shift</label>
                        <input type="time" name="jam_mulai" class="form-input"
                               value="{{ $sudahSubmit ? $laporanHariIni->jam_mulai : '08:00' }}"
                               {{ $sudahSubmit ? 'readonly' : '' }} required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jam Selesai Shift</label>
                        <input type="time" name="jam_selesai" class="form-input"
                               value="{{ $sudahSubmit ? $laporanHariIni->jam_selesai : now()->format('H:i') }}"
                               {{ $sudahSubmit ? 'readonly' : '' }} required>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan Penjualan --}}
        <div class="section-card">
            <div class="section-head">
                <div class="section-head-icon" style="background:#f0fdf4">💰</div>
                <div>
                    <div class="section-head-title">Ringkasan Penjualan</div>
                    <div class="section-head-sub">Data diambil otomatis dari sistem</div>
                </div>
            </div>
            <div class="section-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Total Transaksi</label>
                        <div class="auto-field">
                            <input type="number" name="total_transaksi" class="form-input"
                                   value="{{ $sudahSubmit ? $laporanHariIni->total_transaksi : $statsHariIni['total_transaksi'] }}"
                                   readonly>
                            <span class="auto-badge">Auto</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Pendapatan (Rp)</label>
                        <div class="auto-field">
                            <input type="number" name="total_pendapatan" class="form-input"
                                   value="{{ $sudahSubmit ? $laporanHariIni->total_pendapatan : $statsHariIni['total_pendapatan'] }}"
                                   readonly>
                            <span class="auto-badge">Auto</span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Pendapatan Tunai (Rp)</label>
                        <div class="auto-field">
                            <input type="number" name="pendapatan_tunai" class="form-input"
                                   value="{{ $sudahSubmit ? $laporanHariIni->pendapatan_tunai : $statsHariIni['tunai'] }}"
                                   readonly>
                            <span class="auto-badge">Auto</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pendapatan QRIS (Rp)</label>
                        <div class="auto-field">
                            <input type="number" name="pendapatan_qris" class="form-input"
                                   value="{{ $sudahSubmit ? $laporanHariIni->pendapatan_qris : $statsHariIni['qris'] }}"
                                   readonly>
                            <span class="auto-badge">Auto</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kondisi & Catatan --}}
        <div class="section-card">
            <div class="section-head">
                <div class="section-head-icon" style="background:#faf5ff">📝</div>
                <div>
                    <div class="section-head-title">Kondisi & Catatan</div>
                    <div class="section-head-sub">Isi kondisi toko dan catatan hari ini</div>
                </div>
            </div>
            <div class="section-body">

                <div class="form-group" style="margin-bottom:14px">
                    <label class="form-label">Kondisi Toko Hari Ini</label>
                    <div class="kondisi-group">
                        <button type="button" class="kondisi-btn baik {{ ($sudahSubmit ? $laporanHariIni->kondisi_toko : 'baik') === 'baik' ? 'active' : '' }}"
                                onclick="setKondisi('baik', this)" {{ $sudahSubmit ? 'disabled' : '' }}>
                            <span class="k-icon">😊</span> Baik
                        </button>
                        <button type="button" class="kondisi-btn sedang {{ ($sudahSubmit ? $laporanHariIni->kondisi_toko : '') === 'sedang' ? 'active' : '' }}"
                                onclick="setKondisi('sedang', this)" {{ $sudahSubmit ? 'disabled' : '' }}>
                            <span class="k-icon">😐</span> Sedang
                        </button>
                        <button type="button" class="kondisi-btn buruk {{ ($sudahSubmit ? $laporanHariIni->kondisi_toko : '') === 'buruk' ? 'active' : '' }}"
                                onclick="setKondisi('buruk', this)" {{ $sudahSubmit ? 'disabled' : '' }}>
                            <span class="k-icon">😟</span> Buruk
                        </button>
                    </div>
                    <input type="hidden" name="kondisi_toko" id="kondisiInput"
                           value="{{ $sudahSubmit ? $laporanHariIni->kondisi_toko : 'baik' }}">
                </div>

                <div class="form-group" style="margin-bottom:14px">
                    <label class="form-label">Catatan Kejadian / Kendala</label>
                    <textarea name="catatan_kejadian" class="form-textarea"
                              placeholder="Contoh: Mesin kasir sempat hang, listrik mati 10 menit, dsb. (opsional)"
                              {{ $sudahSubmit ? 'readonly' : '' }}>{{ $sudahSubmit ? $laporanHariIni->catatan_kejadian : old('catatan_kejadian') }}</textarea>
                    <div class="form-hint">Kosongkan jika tidak ada kendala hari ini</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Saran / Masukan untuk Manajemen</label>
                    <textarea name="saran" class="form-textarea"
                              placeholder="Masukan, saran, atau hal yang ingin disampaikan ke admin (opsional)"
                              {{ $sudahSubmit ? 'readonly' : '' }}>{{ $sudahSubmit ? $laporanHariIni->saran : old('saran') }}</textarea>
                </div>

            </div>
        </div>

        {{-- Submit --}}
        @if(!$sudahSubmit)
        <div class="submit-area">
            <button type="submit" class="btn btn-primary">
                ✅ Submit Laporan
            </button>
            <a href="{{ route('kasir.dashboard') }}" class="btn btn-ghost">
                ← Kembali
            </a>
        </div>
        </form>
        @else
        <a href="{{ route('kasir.dashboard') }}" class="btn btn-ghost">← Kembali ke Dashboard</a>
        @endif

    </div>

    {{-- Kanan: Ringkasan shift --}}
    <div>
        <div class="shift-summary">
            <div class="shift-head">
                <div class="shift-head-title">📊 Ringkasan Shift Hari Ini</div>
                <div class="shift-head-sub">{{ now()->isoFormat('D MMMM Y') }}</div>
            </div>
            <div class="shift-stats">
                <div class="shift-stat-item">
                    <div class="stat-emoji">🛒</div>
                    <div>
                        <div class="stat-label">Total Transaksi</div>
                        <div class="stat-val blue">{{ $statsHariIni['total_transaksi'] }} transaksi</div>
                    </div>
                </div>
                <div class="shift-stat-item">
                    <div class="stat-emoji">💰</div>
                    <div>
                        <div class="stat-label">Total Pendapatan</div>
                        <div class="stat-val green">Rp {{ number_format($statsHariIni['total_pendapatan'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="shift-stat-item">
                    <div class="stat-emoji">💵</div>
                    <div>
                        <div class="stat-label">Tunai</div>
                        <div class="stat-val">Rp {{ number_format($statsHariIni['tunai'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="shift-stat-item">
                    <div class="stat-emoji">📱</div>
                    <div>
                        <div class="stat-label">QRIS</div>
                        <div class="stat-val">Rp {{ number_format($statsHariIni['qris'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="shift-stat-item">
                    <div class="stat-emoji">📦</div>
                    <div>
                        <div class="stat-label">Item Terjual</div>
                        <div class="stat-val">{{ $statsHariIni['total_item'] }} pcs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function setKondisi(val, btn) {
        document.querySelectorAll('.kondisi-btn').forEach(b => {
            b.classList.remove('active');
        });
        btn.classList.add('active');
        document.getElementById('kondisiInput').value = val;
    }

    // Konfirmasi sebelum submit
    document.getElementById('laporanForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        if (confirm('Yakin ingin mengirimkan laporan harian? Data tidak dapat diubah setelah disubmit.')) {
            this.submit();
        }
    });
</script>
@endsection