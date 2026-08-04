@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('page_title', 'Tambah Produk')
@section('page_sub', 'Tambah produk baru ke inventori')

@section('content')

<a href="/admin/produk" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-slate-500 no-underline mb-5 hover:text-blue-600 transition-colors">← Kembali ke daftar produk</a>

<div class="bg-white border border-slate-200 rounded-2xl p-7 max-w-150 shadow-sm">
    <h2 class="text-lg font-extrabold text-slate-900 mb-5">➕ Tambah produk baru</h2>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 rounded-[10px] py-3 px-4 text-[13px] mb-4.5">
            Periksa kembali form di bawah ini.
        </div>
    @endif

    <form action="/admin/produk" method="POST">
        @csrf

        <div class="mb-4.5">
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Nama produk *</label>
            <input type="text" name="nama" value="{{ old('nama') }}" required
                   oninvalid="this.setCustomValidity('Nama produk wajib diisi.')" oninput="this.setCustomValidity('')"
                   class="w-full py-2.5 px-3.5 bg-slate-50 border rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 transition-colors {{ $errors->has('nama') ? 'border-red-600' : 'border-slate-200' }}"
                   placeholder="Contoh: Nasi Goreng">
            @error('nama') <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4.5">
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Kategori *</label>
            <select name="kategori_id" required
                    oninvalid="this.setCustomValidity('Kategori wajib dipilih.')" onchange="this.setCustomValidity('')"
                    class="w-full py-2.5 px-3.5 bg-slate-50 border rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 transition-colors {{ $errors->has('kategori_id') ? 'border-red-600' : 'border-slate-200' }}">
                <option value="">-- Pilih kategori --</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id') <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="grid grid-cols-2 gap-3.5">
            <div class="mb-4.5">
                <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Harga *</label>
                <input type="number" name="harga" value="{{ old('harga') }}" min="0" step="100" required
                       oninvalid="this.setCustomValidity(this.validity.rangeUnderflow ? 'Harga tidak boleh kurang dari 0.' : 'Harga wajib diisi.')" oninput="this.setCustomValidity('')"
                       class="w-full py-2.5 px-3.5 bg-slate-50 border rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 transition-colors {{ $errors->has('harga') ? 'border-red-600' : 'border-slate-200' }}"
                       placeholder="0">
                @error('harga') <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4.5">
                <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Stok *</label>
                <input type="number" name="stok" value="{{ old('stok') }}" min="0" required
                       oninvalid="this.setCustomValidity(this.validity.rangeUnderflow ? 'Stok tidak boleh kurang dari 0.' : 'Stok wajib diisi.')" oninput="this.setCustomValidity('')"
                       class="w-full py-2.5 px-3.5 bg-slate-50 border rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 transition-colors {{ $errors->has('stok') ? 'border-red-600' : 'border-slate-200' }}"
                       placeholder="0">
                @error('stok') <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-4.5">
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Deskripsi</label>
            <textarea name="deskripsi" placeholder="Deskripsi produk (opsional)"
                      class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 transition-colors resize-y min-h-20">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="flex gap-2.5 mt-6">
            <button type="submit" class="flex-1 py-3 rounded-[9px] text-[13px] font-bold text-center cursor-pointer bg-blue-600 text-white shadow-[0_3px_10px_rgba(37,99,235,0.3)] hover:bg-blue-700 transition-colors">💾 Simpan produk</button>
            <a href="/admin/produk" class="flex-1 py-3 rounded-[9px] text-[13px] font-bold text-center no-underline bg-slate-50 text-slate-500 border border-slate-200 hover:border-red-600 hover:text-red-600 transition-colors">Batal</a>
        </div>
    </form>
</div>

@endsection