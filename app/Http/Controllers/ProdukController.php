<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;

class ProdukController extends Controller
{
    // Menampilkan daftar produk
    public function index()
    {
        $produk = Produk::with('kategori')->orderBy('nama')->get();
        return view('admin.produk.index', compact('produk'));
    }

    // Form tambah produk
    public function create()
    {
        $kategori = Kategori::orderBy('nama')->get();
        return view('admin.produk.create', compact('kategori'));
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        Produk::create($request->all());

        return redirect('/admin/produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Form edit produk
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::orderBy('nama')->get();
        return view('admin.produk.edit', compact('produk', 'kategori'));
    }

    // Update produk
    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->all());

        return redirect('/admin/produk')->with('success', 'Produk berhasil diupdate.');
    }

    // Hapus produk (Force Delete dengan Cascade)
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        
        // Hapus produk (otomatis menghapus detail_transaksi yang terkait)
        $produk->delete();

        return redirect('/admin/produk')->with('success', 'Produk dan semua data transaksi terkait berhasil dihapus.');
    }
}