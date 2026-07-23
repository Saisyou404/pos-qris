@extends('layouts.app')

@section('title', 'Edit Produk')
@section('page_title', 'Edit Produk')
@section('page_sub', 'Perbarui data produk')

@section('content')

@php
    // Helper kecil supaya class border error tidak ditulis dobel (border-red-600
    // & border-slate-200) berdampingan di satu atribut class — selain lebih
    // ringkas, ini juga menghindari false-positive "cssConflict" dari linter
    // Tailwind, yang tidak paham @error/@else adalah exclusive di runtime.
    $borderClass = fn (string $field) => $errors->has($field) ? 'border-red-600' : 'border-slate-200';
@endphp

<a href="/admin/produk" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-slate-500 no-underline mb-5 hover:text-blue-600 transition-colors">← Kembali ke daftar produk</a>

<div class="bg-white border border-slate-200 rounded-2xl p-7 max-w-150 shadow-sm">
    <h2 class="text-lg font-extrabold text-slate-900 mb-5">✏️ Edit produk</h2>

    <form action="/admin/produk/{{ $produk->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4.5">
            <label for="nama" class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Nama produk *</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $produk->nama) }}" required
                class="w-full py-2.5 px-3.5 bg-slate-50 border {{ $borderClass('nama') }} rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 transition-colors">
            @error('nama') <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4.5">
            <label for="kategori_id" class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Kategori *</label>
            <select id="kategori_id" name="kategori_id" required
                class="w-full py-2.5 px-3.5 bg-slate-50 border {{ $borderClass('kategori_id') }} rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 transition-colors">
                <option value="">-- Pilih kategori --</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id', $produk->kategori_id) == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id') <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="grid grid-cols-2 gap-3.5">
            <div class="mb-4.5">
                <label for="harga" class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Harga *</label>
                <input type="number" id="harga" name="harga" value="{{ old('harga', $produk->harga) }}" min="0" step="100" required
                    class="w-full py-2.5 px-3.5 bg-slate-50 border {{ $borderClass('harga') }} rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 transition-colors">
                @error('harga') <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4.5">
                <label for="stok" class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Stok *</label>
                <input type="number" id="stok" name="stok" value="{{ old('stok', $produk->stok) }}" min="0" required
                    class="w-full py-2.5 px-3.5 bg-slate-50 border {{ $borderClass('stok') }} rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 transition-colors">
                @error('stok') <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-4.5">
            <label for="deskripsi" class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="3"
                class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 transition-colors resize-y">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
            @error('deskripsi') <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="flex gap-2.5 mt-6">
            <button type="submit" class="flex-1 py-3 rounded-[9px] text-[13px] font-bold text-center cursor-pointer bg-blue-600 text-white shadow-[0_3px_10px_rgba(37,99,235,0.3)] hover:bg-blue-700 transition-colors">
                💾 Update produk
            </button>
            <a href="/admin/produk" class="flex-1 py-3 rounded-[9px] text-[13px] font-bold text-center no-underline bg-slate-50 text-slate-500 border border-slate-200 hover:border-red-600 hover:text-red-600 transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
