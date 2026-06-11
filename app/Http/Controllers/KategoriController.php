<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    // Menampilkan daftar kategori
    public function index()
    {
        $kategori = Kategori::withCount('produk')->orderBy('nama')->get();
        return view('admin.kategori.index', compact('kategori'));
    }

    // Form tambah kategori
    public function create()
    {
        return view('admin.kategori.create');
    }

    // Simpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama',
        ], [
            'nama.required' => 'Nama kategori harus diisi.',
            'nama.unique' => 'Kategori dengan nama ini sudah ada.',
        ]);

        Kategori::create($request->all());

        return redirect('/admin/kategori')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Form edit kategori
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    // Update kategori
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama,' . $id,
        ], [
            'nama.required' => 'Nama kategori harus diisi.',
            'nama.unique' => 'Kategori dengan nama ini sudah ada.',
        ]);

        $kategori->update($request->all());

        return redirect('/admin/kategori')->with('success', 'Kategori berhasil diupdate.');
    }

    // Hapus kategori
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        // Cek apakah kategori masih digunakan oleh produk
        if ($kategori->produk()->count() > 0) {
            return redirect('/admin/kategori')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $kategori->produk()->count() . ' produk.');
        }

        $kategori->delete();

        return redirect('/admin/kategori')->with('success', 'Kategori berhasil dihapus.');
    }
}