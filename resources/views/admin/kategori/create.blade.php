@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('page_title', 'Tambah Kategori')
@section('page_sub', 'Tambah kategori produk baru')

@section('content')

<a href="/admin/kategori" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-slate-500 no-underline mb-5 hover:text-blue-600 transition-colors">← Kembali ke daftar kategori</a>

<div class="bg-white border border-slate-200 rounded-2xl p-7 max-w-120 shadow-sm">
    <h2 class="text-lg font-extrabold text-slate-900 mb-5">🏷️ Tambah kategori baru</h2>

    <form action="/admin/kategori" method="POST">
        @csrf

        <div class="mb-4.5">
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Nama kategori *</label>
            <input type="text" name="nama" value="{{ old('nama') }}" required autofocus
                   class="w-full py-2.5 px-3.5 bg-slate-50 border rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 transition-colors {{ $errors->has('nama') ? 'border-red-600' : 'border-slate-200' }}"
                   placeholder="Contoh: Minuman">
            @error('nama') <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="flex gap-2.5 mt-6">
            <button type="submit" class="flex-1 py-3 rounded-[9px] text-[13px] font-bold text-center cursor-pointer bg-blue-600 text-white shadow-[0_3px_10px_rgba(37,99,235,0.3)] hover:bg-blue-700 transition-colors">💾 Simpan kategori</button>
            <a href="/admin/kategori" class="flex-1 py-3 rounded-[9px] text-[13px] font-bold text-center no-underline bg-slate-50 text-slate-500 border border-slate-200 hover:border-red-600 hover:text-red-600 transition-colors">Batal</a>
        </div>
    </form>
</div>

@endsection