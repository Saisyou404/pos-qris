@extends('layouts.app')

@section('title', 'Manajemen Kategori')
@section('page_title', 'Manajemen Kategori')
@section('page_sub', 'Kelola kategori produk')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900">🏷️ Manajemen Kategori</h2>
        <p class="text-xs text-slate-500 mt-0.5">Kelola kategori produk yang tersedia di sistem</p>
    </div>
    <a href="/admin/kategori/create" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-[9px] text-xs font-semibold no-underline bg-blue-600 text-white shadow-[0_3px_10px_rgba(37,99,235,0.25)] hover:bg-blue-700 hover:-translate-y-px transition-all">
        ➕ Tambah kategori
    </a>
</div>

{{-- Summary --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 mb-5">
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm flex items-center gap-3.5">
        <div class="w-10.5 h-10.5 rounded-[11px] flex items-center justify-center text-xl shrink-0 bg-blue-50">🏷️</div>
        <div>
            <div class="text-[11px] text-slate-500 font-semibold mb-1 uppercase">Total kategori</div>
            <div class="text-xl font-extrabold leading-none text-blue-600">{{ $kategori->count() }}</div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm flex items-center gap-3.5">
        <div class="w-10.5 h-10.5 rounded-[11px] flex items-center justify-center text-xl shrink-0 bg-green-50">📦</div>
        <div>
            <div class="text-[11px] text-slate-500 font-semibold mb-1 uppercase">Total produk terdaftar</div>
            <div class="text-xl font-extrabold leading-none text-green-600">{{ $kategori->sum('produk_count') }}</div>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<div class="flex items-center gap-2.5 mb-3.5">
    <div class="relative flex-1 max-w-75">
        <span class="absolute left-2.75 top-1/2 -translate-y-1/2 text-[13px] pointer-events-none">🔍</span>
        <input type="text" id="searchInput" placeholder="Cari kategori..." oninput="filterTable()"
               class="w-full py-2.5 pl-9 pr-3.5 bg-white border border-slate-200 rounded-[9px] text-xs text-slate-900 outline-none shadow-sm focus:border-blue-600 transition-colors">
    </div>
</div>

{{-- Table --}}
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <table id="kategoriTable" class="w-full border-collapse">
        <thead class="bg-slate-50">
            <tr>
                <th class="py-2.5 px-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-200">#</th>
                <th class="py-2.5 px-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-200">Nama kategori</th>
                <th class="py-2.5 px-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-200">Jumlah produk</th>
                <th class="py-2.5 px-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-200">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori as $i => $k)
            <tr class="hover:bg-slate-50">
                <td class="py-3 px-4 text-[13px] border-b border-slate-200 align-middle font-semibold text-slate-400">{{ $i + 1 }}</td>
                <td class="py-3 px-4 text-[13px] border-b border-slate-200 align-middle font-bold text-slate-900">{{ $k->nama }}</td>
                <td class="py-3 px-4 text-[13px] border-b border-slate-200 align-middle">
                    @if($k->produk_count > 0)
                        <span class="inline-flex items-center py-0.5 px-2.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600">{{ $k->produk_count }} produk</span>
                    @else
                        <span class="inline-flex items-center py-0.5 px-2.5 rounded-full text-[11px] font-bold bg-slate-50 text-slate-500 border border-slate-200">Kosong</span>
                    @endif
                </td>
                <td class="py-3 px-4 text-[13px] border-b border-slate-200 align-middle">
                    <div class="flex gap-1.5">
                        <a href="/admin/kategori/{{ $k->id }}/edit"
                           class="inline-flex items-center gap-1 py-1.5 px-3 rounded-lg text-[11px] font-semibold no-underline bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors">✏️ Edit</a>

                        @if($k->produk_count == 0)
                            <form action="/admin/kategori/{{ $k->id }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus kategori \'{{ $k->nama }}\'?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 py-1.5 px-3 rounded-lg text-[11px] font-semibold cursor-pointer bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-colors">🗑 Hapus</button>
                            </form>
                        @else
                            <span class="inline-flex items-center gap-1 py-1.5 px-3 rounded-lg text-[11px] font-semibold bg-slate-50 text-slate-400 cursor-not-allowed"
                                  title="Tidak bisa dihapus — masih ada {{ $k->produk_count }} produk">
                                🔒 Terkunci
                            </span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">
                    <div class="text-center py-12.5 px-5 text-slate-500">
                        <div class="text-[40px] mb-3">🏷️</div>
                        <p class="text-[13px]">Belum ada kategori. <a href="/admin/kategori/create" class="text-blue-600 font-semibold">Tambah sekarang</a></p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="py-2.5 px-4 border-t border-slate-200 text-[11px] text-slate-500 bg-slate-50">
        Total <strong>{{ $kategori->count() }}</strong> kategori
    </div>
</div>

@endsection

@section('scripts')
<script>
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#kategoriTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>
@endsection