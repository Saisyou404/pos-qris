@extends('layouts.app')

@section('page_title', 'Manajemen Kategori')
@section('page_sub', 'Kelola kategori produk Anda')

@section('styles')
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--accent);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    padding: 9px 16px;
    border-radius: 9px;
    text-decoration: none;
    transition: all 0.15s;
    box-shadow: 0 2px 8px rgba(37,99,235,0.25);
}

.btn-primary:hover {
    background: #1d4ed8;
    box-shadow: 0 4px 12px rgba(37,99,235,0.35);
    transform: translateY(-1px);
}

/* ===== CARD ===== */
.card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 20px;
}

/* ===== TABLE ===== */
.table-wrapper { overflow-x: auto; }

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

thead th {
    background: var(--bg3);
    border-bottom: 1px solid var(--border);
    padding: 11px 16px;
    text-align: left;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: var(--text3);
}

thead th.center { text-align: center; }

tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background 0.12s;
}

tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--bg3); }

tbody td {
    padding: 13px 16px;
    color: var(--text);
    vertical-align: middle;
}

tbody td.center { text-align: center; }

/* ===== CATEGORY ICON ===== */
.cat-cell {
    display: flex;
    align-items: center;
    gap: 11px;
}

.cat-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: var(--accent-light);
    border: 1px solid rgba(37,99,235,0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}

.cat-name {
    font-weight: 600;
    font-size: 13px;
    color: var(--text);
}

/* ===== BADGE ===== */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 600;
}

.badge-blue {
    background: #eff6ff;
    color: var(--accent);
    border: 1px solid #bfdbfe;
}

/* ===== ACTION BUTTONS ===== */
.action-group {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    border: none;
    transition: all 0.15s;
    font-family: inherit;
}

.btn-edit {
    background: var(--accent-light);
    color: var(--accent);
    border: 1px solid #bfdbfe;
}

.btn-edit:hover {
    background: #dbeafe;
    color: #1d4ed8;
}

.btn-delete {
    background: var(--danger-light);
    color: var(--danger);
    border: 1px solid #fecaca;
}

.btn-delete:hover {
    background: #fee2e2;
    color: #b91c1c;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    padding: 48px 24px;
    text-align: center;
}

.empty-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    background: var(--bg3);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin: 0 auto 14px;
}

.empty-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 4px;
}

.empty-sub {
    font-size: 12px;
    color: var(--text3);
    margin-bottom: 16px;
}

/* ===== SUMMARY STATS ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 14px;
}

.stat-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px 18px;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 14px;
}

.stat-icon {
    width: 42px; height: 42px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.stat-icon-green { background: var(--success-light); border: 1px solid #bbf7d0; }
.stat-icon-blue  { background: var(--accent-light);  border: 1px solid #bfdbfe; }

.stat-label { font-size: 11px; font-weight: 600; color: var(--text3); margin-bottom: 3px; }
.stat-value { font-size: 22px; font-weight: 800; color: var(--text); line-height: 1; }
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        {{-- breadcrumb / judul sudah di header layout --}}
    </div>
    <a href="/admin/kategori/create" class="btn-primary">
        ＋ Tambah Kategori
    </a>
</div>

{{-- Table Card --}}
<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width:52px">No</th>
                    <th>Nama Kategori</th>
                    <th class="center" style="width:160px">Jumlah Produk</th>
                    <th class="center" style="width:160px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategori as $k)
                <tr>
                    <td>
                        <span style="font-size:12px;color:var(--text3);font-weight:600">{{ $loop->iteration }}</span>
                    </td>
                    <td>
                        <div class="cat-cell">
                            <div class="cat-icon">🏷️</div>
                            <div class="cat-name">{{ $k->nama }}</div>
                        </div>
                    </td>
                    <td class="center">
                        <span class="badge badge-blue">
                            📦 {{ $k->produk_count }} produk
                        </span>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="/admin/kategori/{{ $k->id }}/edit" class="btn-action btn-edit">
                                ✏️ Edit
                            </a>
                            <form action="/admin/kategori/{{ $k->id }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="btn-action btn-delete"
                                    onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                                >
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <div class="empty-icon">🏷️</div>
                            <div class="empty-title">Belum ada kategori</div>
                            <div class="empty-sub">Mulai dengan menambahkan kategori pertama Anda</div>
                            <a href="/admin/kategori/create" class="btn-primary" style="display:inline-flex">
                                ＋ Tambah Kategori
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Summary Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-green">🏷️</div>
        <div>
            <div class="stat-label">Total Kategori</div>
            <div class="stat-value">{{ $kategori->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-blue">📦</div>
        <div>
            <div class="stat-label">Total Produk</div>
            <div class="stat-value">{{ $kategori->sum('produk_count') }}</div>
        </div>
    </div>
</div>

@endsection