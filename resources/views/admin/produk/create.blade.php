@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="/admin/produk" class="text-blue-600 hover:text-blue-800 flex items-center">
        ← Kembali ke Daftar Produk
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Produk Baru</h1>

    <form action="/admin/produk" method="POST">
        @csrf

        <div class="mb-5">
            <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk *</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @else border-gray-300 @enderror">
            @error('nama')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="kategori_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori *</label>
            <select id="kategori_id" name="kategori_id" required
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kategori_id') border-red-500 @else border-gray-300 @enderror">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-5">
            <div>
                <label for="harga" class="block text-sm font-semibold text-gray-700 mb-2">Harga *</label>
                <input type="number" id="harga" name="harga" value="{{ old('harga') }}" min="0" step="100" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('harga') border-red-500 @else border-gray-300 @enderror">
                @error('harga')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="stok" class="block text-sm font-semibold text-gray-700 mb-2">Stok *</label>
                <input type="number" id="stok" name="stok" value="{{ old('stok') }}" min="0" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stok') border-red-500 @else border-gray-300 @enderror">
                @error('stok')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="3"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') border-red-500 @else border-gray-300 @enderror">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg shadow transition">
                Simpan Produk
            </button>
            <a href="/admin/produk" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-lg transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection