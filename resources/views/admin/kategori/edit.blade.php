@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page_title', 'Edit Kategori')
@section('page_sub', 'Perbarui data kategori')

@section('content')

@php
    $borderClass = fn (string $field) => $errors->has($field) ? 'border-red-600' : 'border-slate-200';
@endphp

<a href="/admin/kategori" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-slate-500 no-underline mb-5 hover:text-blue-600 transition-colors">← Kembali ke daftar kategori</a>

<div class="bg-white border border-slate-200 rounded-2xl p-7 max-w-120 shadow-sm">
    <h2 class="text-lg font-extrabold text-slate-900 mb-5">✏️ Edit kategori</h2>

    <form action="/admin/kategori/{{ $kategori->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4.5">
            <label for="nama" class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Nama kategori *</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $kategori->nama) }}" required autofocus
                class="w-full py-2.5 px-3.5 bg-slate-50 border {{ $borderClass('nama') }} rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 transition-colors"
                placeholder="Contoh: Minuman">
            @error('nama') <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="flex gap-2.5 mt-6">
            <button type="submit" class="flex-1 py-3 rounded-[9px] text-[13px] font-bold text-center cursor-pointer bg-blue-600 text-white shadow-[0_3px_10px_rgba(37,99,235,0.3)] hover:bg-blue-700 transition-colors">
                💾 Update kategori
            </button>
            <a href="/admin/kategori" class="flex-1 py-3 rounded-[9px] text-[13px] font-bold text-center no-underline bg-slate-50 text-slate-500 border border-slate-200 hover:border-red-600 hover:text-red-600 transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection