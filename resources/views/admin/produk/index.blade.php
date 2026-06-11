@extends('layouts.app')

@section('title', 'Manajemen Produk')
@section('page_title', 'Manajemen Produk')
@section('page_sub', 'Kelola produk dan stok inventori')

@section('styles')
<style>
    /* Page Header */
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 20px;
    }
    .page-title-wrap h2 { font-size: 20px; font-weight: 800; color: var(--text); }
    .page-title-wrap p  { font-size: 12px; color: var(--text2); margin-top: 2px; }

    /* Buttons */
    .btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 16px;
        border-radius: 9px;
        font-family: inherit; font-size: 12px; font-weight: 600;
        cursor: pointer; border: none; text-decoration: none;
        transition: all 0.15s;
    }
    .btn-primary {
        background: var(--accent); color: #fff;
        box-shadow: 0 3px 10px rgba(37,99,235,0.25);
    }
    .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 5px 14px rgba(37,99,235,0.35); }

    /* Summary Cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px; margin-bottom: 20px;
    }

    .summary-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px 20px;
        box-shadow: var(--shadow);
        display: flex; align-items: center; gap: 14px;
    }

    .summary-icon {
        width: 42px; height: 42px;
        border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }

    .summary-label { font-size: 11px; color: var(--text2); font-weight: 600; margin-bottom: 4px; }
    .summary-value { font-size: 20px; font-weight: 800; color: var(--text); line-height: 1; }

    /* Toolbar */
    .toolbar {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 14px; flex-wrap: wrap;
    }

    .search-wrap { position: relative; flex: 1; max-width: 300px; }
    .search-wrap input {
        width: 100%;
        padding: 9px 14px 9px 36px;
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: 9px;
        font-family: inherit; font-size: 12px;
        color: var(--text); outline: none;
        transition: border-color 0.15s;
        box-shadow: var(--shadow);
    }
    .search-wrap input:focus { border-color: var(--accent); }
    .search-icon {
        position: absolute; left: 11px; top: 50%;
        transform: translateY(-50%); font-size: 13px; pointer-events: none;
    }

    .filter-select {
        padding: 9px 14px;
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: 9px;
        font-family: inherit; font-size: 12px;
        color: var(--text); outline: none; cursor: pointer;
        box-shadow: var(--shadow);
        transition: border-color 0.15s;
    }
    .filter-select:focus { border-color: var(--accent); }

    /* Table */
    .table-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: var(--shadow);
        margin-bottom: 20px;
    }

    table { width: 100%; border-collapse: collapse; }

    thead { background: var(--bg3); }

    th {
        padding: 11px 16px;
        text-align: left;
        font-size: 10px; font-weight: 700; letter-spacing: 0.8px;
        text-transform: uppercase; color: var(--text3);
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    td {
        padding: 13px 16px;
        font-size: 12.5px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        color: var(--text);
    }

    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f8fafc; }

    .prod-name   { font-weight: 700; font-size: 13px; margin-bottom: 2px; }
    .prod-desc   { font-size: 11px; color: var(--text3); }

    .cat-badge {
        display: inline-block;
        padding: 3px 10px;
        background: var(--accent-light);
        color: var(--accent);
        border-radius: 99px;
        font-size: 10.5px; font-weight: 700;
    }

    .stock-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 11px; font-weight: 700;
    }
    .stock-high   { background: #f0fdf4; color: #16a34a; }
    .stock-medium { background: #fffbeb; color: #d97706; }
    .stock-low    { background: #fef2f2; color: #dc2626; }

    .price-text { font-weight: 700; color: var(--accent); }

    .action-btns { display: flex; gap: 6px; justify-content: center; }

    .icon-btn {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 12px;
        border-radius: 7px;
        font-size: 11px; font-weight: 600;
        cursor: pointer; text-decoration: none;
        border: 1px solid var(--border);
        background: var(--bg3);
        color: var(--text2);
        transition: all 0.15s;
        white-space: nowrap;
    }
    .icon-btn:hover       { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }
    .icon-btn.danger:hover{ border-color: var(--danger); color: var(--danger); background: #fef2f2; }

    /* Empty state */
    .empty-state {
        text-align: center; padding: 50px 20px;
        color: var(--text3);
    }
    .empty-icon { font-size: 42px; margin-bottom: 10px; opacity: 0.4; }
    .empty-text { font-size: 13px; font-weight: 600; margin-bottom: 6px; color: var(--text2); }
    .empty-sub  { font-size: 12px; }

    /* Table Footer */
    .table-footer {
        padding: 12px 16px;
        border-top: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        background: var(--bg3);
        font-size: 11.5px; color: var(--text2);
    }

    @media (max-width: 900px) {
        .summary-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div class="page-title-wrap">
        <h2>Manajemen Produk</h2>
        <p>Kelola data produk dan stok inventori toko</p>
    </div>
    <a href="/admin/produk/create" class="btn btn-primary">
        ＋ Tambah Produk
    </a>
</div>

{{-- Summary Cards --}}
<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-icon" style="background:#eff6ff">📦</div>
        <div>
            <div class="summary-label">Total Produk</div>
            <div class="summary-value" style="color:#2563eb">{{ $produk->count() }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background:#f0fdf4">📊</div>
        <div>
            <div class="summary-label">Total Stok</div>
            <div class="summary-value" style="color:#16a34a">{{ number_format($produk->sum('stok'), 0, ',', '.') }} pcs</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background:#faf5ff">💰</div>
        <div>
            <div class="summary-label">Nilai Inventori</div>
            <div class="summary-value" style="color:#7c3aed">
                Rp {{ number_format($produk->sum(fn($p) => $p->harga * $p->stok), 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<div class="toolbar">
    <div class="search-wrap">
        <span class="search-icon">🔍</span>
        <input type="text" id="searchInput" placeholder="Cari nama produk..." onkeyup="searchTable()">
    </div>
    <select class="filter-select" id="stockFilter" onchange="filterStock()">
        <option value="all">Semua Stok</option>
        <option value="high">Stok Aman (> 20)</option>
        <option value="medium">Stok Sedang (11–20)</option>
        <option value="low">Stok Rendah (≤ 10)</option>
    </select>
</div>

{{-- Table --}}
<div class="table-card">
    <table id="produkTable">
        <thead>
            <tr>
                <th style="width:48px">No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th style="text-align:center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produk as $p)
            <tr data-stok="{{ $p->stok }}">
                <td style="color:var(--text3);font-size:12px">{{ $loop->iteration }}</td>
                <td>
                    <div class="prod-name">{{ $p->nama }}</div>
                    @if($p->deskripsi)
                    <div class="prod-desc">{{ Str::limit($p->deskripsi, 60) }}</div>
                    @endif
                </td>
                <td>
                    <span class="cat-badge">{{ $p->kategori->nama }}</span>
                </td>
                <td>
                    <span class="price-text">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                </td>
                <td>
                    @if($p->stok > 20)
                        <span class="stock-badge stock-high">● {{ $p->stok }} pcs</span>
                    @elseif($p->stok > 10)
                        <span class="stock-badge stock-medium">● {{ $p->stok }} pcs</span>
                    @else
                        <span class="stock-badge stock-low">⚠ {{ $p->stok }} pcs</span>
                    @endif
                </td>
                <td>
                    <div class="action-btns">
                        <a href="/admin/produk/{{ $p->id }}/edit" class="icon-btn">
                            ✏️ Edit
                        </a>
                        <form action="/admin/produk/{{ $p->id }}" method="POST" class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus produk ini? Semua data transaksi terkait juga akan terhapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-btn danger">🗑 Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <div class="empty-text">Belum ada produk</div>
                        <div class="empty-sub">
                            <a href="/admin/produk/create" style="color:var(--accent);font-weight:600">
                                + Tambah produk pertama
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="table-footer">
        <span>Total <strong>{{ $produk->count() }}</strong> produk</span>
        <span id="filteredCount"></span>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function searchTable() {
        const q    = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#produkTable tbody tr[data-stok]');
        let visible = 0;

        rows.forEach(row => {
            const name = row.querySelector('.prod-name').textContent.toLowerCase();
            const show = name.includes(q);
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('filteredCount').textContent =
            q ? `Menampilkan ${visible} hasil` : '';
    }

    function filterStock() {
        const val  = document.getElementById('stockFilter').value;
        const rows = document.querySelectorAll('#produkTable tbody tr[data-stok]');

        rows.forEach(row => {
            const stok = parseInt(row.getAttribute('data-stok'));
            let show = true;
            if (val === 'high')   show = stok > 20;
            if (val === 'medium') show = stok > 10 && stok <= 20;
            if (val === 'low')    show = stok <= 10;
            row.style.display = show ? '' : 'none';
        });
    }
</script>
@endsection