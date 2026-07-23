@extends('layouts.app')

@section('title', 'Manajemen Produk')
@section('page_title', 'Manajemen Produk')
@section('page_sub', 'Kelola produk dan stok inventori')

@section('content')

{{-- Page Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900">Manajemen Produk</h2>
        <p class="text-xs text-slate-500 mt-0.5">Kelola data produk dan stok inventori toko</p>
    </div>
    <a href="/admin/produk/create" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-[9px] text-xs font-semibold no-underline bg-blue-600 text-white shadow-[0_3px_10px_rgba(37,99,235,0.25)] hover:bg-blue-700 hover:-translate-y-px transition-all">
        ＋ Tambah produk
    </a>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-3.5 mb-5">
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm flex items-center gap-3.5">
        <div class="w-[42px] h-[42px] rounded-[11px] flex items-center justify-center text-xl shrink-0 bg-blue-50">📦</div>
        <div>
            <div class="text-[11px] text-slate-500 font-semibold mb-1">Total produk</div>
            <div class="text-xl font-extrabold leading-none text-blue-600">{{ $produk->count() }}</div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm flex items-center gap-3.5">
        <div class="w-[42px] h-[42px] rounded-[11px] flex items-center justify-center text-xl shrink-0 bg-green-50">📊</div>
        <div>
            <div class="text-[11px] text-slate-500 font-semibold mb-1">Total stok</div>
            <div class="text-xl font-extrabold leading-none text-green-600">{{ number_format($produk->sum('stok'), 0, ',', '.') }} pcs</div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm flex items-center gap-3.5">
        <div class="w-[42px] h-[42px] rounded-[11px] flex items-center justify-center text-xl shrink-0 bg-violet-50">💰</div>
        <div>
            <div class="text-[11px] text-slate-500 font-semibold mb-1">Nilai inventori</div>
            <div class="text-xl font-extrabold leading-none text-violet-600">
                Rp {{ number_format($produk->sum(fn($p) => $p->harga * $p->stok), 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<div class="flex items-center gap-2.5 mb-3.5 flex-wrap">
    <div class="relative flex-1 max-w-[300px]">
        <span class="absolute left-[11px] top-1/2 -translate-y-1/2 text-[13px] pointer-events-none">🔍</span>
        <input type="text" id="searchInput" placeholder="Cari nama produk..." onkeyup="searchTable()"
               class="w-full py-2.5 pl-9 pr-3.5 bg-white border border-slate-200 rounded-[9px] text-xs text-slate-900 outline-none shadow-sm focus:border-blue-600 transition-colors">
    </div>
    <select id="stockFilter" onchange="filterStock()"
            class="py-2.5 px-3.5 bg-white border border-slate-200 rounded-[9px] text-xs text-slate-900 outline-none cursor-pointer shadow-sm focus:border-blue-600 transition-colors">
        <option value="all">Semua stok</option>
        <option value="high">Stok aman (&gt; 20)</option>
        <option value="medium">Stok sedang (11–20)</option>
        <option value="low">Stok rendah (≤ 10)</option>
    </select>
</div>

{{-- Table --}}
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm mb-5">
    <table id="produkTable" class="w-full border-collapse">
        <thead class="bg-slate-50">
            <tr>
                <th class="w-12 py-2.5 px-4 text-left text-[10px] font-bold tracking-wider uppercase text-slate-400 border-b border-slate-200 whitespace-nowrap">No</th>
                <th class="py-2.5 px-4 text-left text-[10px] font-bold tracking-wider uppercase text-slate-400 border-b border-slate-200 whitespace-nowrap">Nama produk</th>
                <th class="py-2.5 px-4 text-left text-[10px] font-bold tracking-wider uppercase text-slate-400 border-b border-slate-200 whitespace-nowrap">Kategori</th>
                <th class="py-2.5 px-4 text-left text-[10px] font-bold tracking-wider uppercase text-slate-400 border-b border-slate-200 whitespace-nowrap">Harga</th>
                <th class="py-2.5 px-4 text-left text-[10px] font-bold tracking-wider uppercase text-slate-400 border-b border-slate-200 whitespace-nowrap">Stok</th>
                <th class="py-2.5 px-4 text-center text-[10px] font-bold tracking-wider uppercase text-slate-400 border-b border-slate-200 whitespace-nowrap">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produk as $p)
            <tr data-stok="{{ $p->stok }}" class="hover:bg-slate-50">
                <td class="py-3.5 px-4 text-xs border-b border-slate-200 align-middle text-slate-400">{{ $loop->iteration }}</td>
                <td class="py-3.5 px-4 text-[12.5px] border-b border-slate-200 align-middle text-slate-900">
                    <div class="prod-name font-bold text-[13px] mb-0.5">{{ $p->nama }}</div>
                    @if($p->deskripsi)
                    <div class="text-[11px] text-slate-400">{{ Str::limit($p->deskripsi, 60) }}</div>
                    @endif
                </td>
                <td class="py-3.5 px-4 text-[12.5px] border-b border-slate-200 align-middle">
                    <span class="inline-block py-0.5 px-2.5 bg-blue-50 text-blue-600 rounded-full text-[10.5px] font-bold">{{ $p->kategori->nama }}</span>
                </td>
                <td class="py-3.5 px-4 text-[12.5px] border-b border-slate-200 align-middle">
                    <span class="font-bold text-blue-600">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                </td>
                <td class="py-3.5 px-4 text-[12.5px] border-b border-slate-200 align-middle">
                    @if($p->stok > 20)
                        <span class="inline-flex items-center gap-1 py-1 px-2.5 rounded-full text-[11px] font-bold bg-green-50 text-green-600">● {{ $p->stok }} pcs</span>
                    @elseif($p->stok > 10)
                        <span class="inline-flex items-center gap-1 py-1 px-2.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600">● {{ $p->stok }} pcs</span>
                    @else
                        <span class="inline-flex items-center gap-1 py-1 px-2.5 rounded-full text-[11px] font-bold bg-red-50 text-red-600">⚠ {{ $p->stok }} pcs</span>
                    @endif
                </td>
                <td class="py-3.5 px-4 text-[12.5px] border-b border-slate-200 align-middle">
                    <div class="flex gap-1.5 justify-center">
                        <a href="/admin/produk/{{ $p->id }}/edit"
                           class="inline-flex items-center gap-1 py-1.5 px-3 rounded-lg text-[11px] font-semibold no-underline border border-slate-200 bg-slate-50 text-slate-500 hover:border-blue-600 hover:text-blue-600 hover:bg-blue-50 transition-colors whitespace-nowrap">
                            ✏️ Edit
                        </a>
                        <form action="/admin/produk/{{ $p->id }}" method="POST" class="inline"
                              onsubmit="return confirm('Nonaktifkan produk ini? Produk tidak akan tampil lagi di kasir, tapi riwayat transaksi yang sudah ada tetap aman.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1 py-1.5 px-3 rounded-lg text-[11px] font-semibold cursor-pointer border border-slate-200 bg-slate-50 text-slate-500 hover:border-red-600 hover:text-red-600 hover:bg-red-50 transition-colors whitespace-nowrap">
                                🚫 Nonaktifkan
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="text-center py-[50px] px-5 text-slate-400">
                        <div class="text-[42px] mb-2.5 opacity-40">📦</div>
                        <div class="text-[13px] font-semibold mb-1.5 text-slate-500">Belum ada produk</div>
                        <div class="text-xs">
                            <a href="/admin/produk/create" class="text-blue-600 font-semibold">
                                + Tambah produk pertama
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="py-3 px-4 border-t border-slate-200 flex items-center justify-between bg-slate-50 text-[11.5px] text-slate-500">
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