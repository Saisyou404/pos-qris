@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="/admin/kategori" class="text-green-600 hover:text-green-800 flex items-center">
        ← Kembali ke Daftar Kategori
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg p-8 max-w-xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Kategori</h1>

    <form action="/admin/kategori/{{ $kategori->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori *</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $kategori->nama) }}" required autofocus
                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 {{ $errors->has('nama') ? 'border-red-500' : 'border-gray-300' }}"
                placeholder="Contoh: Minuman">
            @error('nama')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg shadow transition">
                Update Kategori
            </button>
            <a href="/admin/kategori" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-lg transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection