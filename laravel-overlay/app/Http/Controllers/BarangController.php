<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    private function adminOnly(): void { abort_unless(auth()->user()->isAdmin(), 403, 'Akses khusus Admin.'); }

    public function index()
    {
        $query = Barang::with('kategori')->orderBy('name');
        if (request('q')) {
            $term = request('q');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"));
        }
        $barangs = $query->paginate(10)->withQueryString();
        return view('items.index', compact('barangs'));
    }

    public function create()
    {
        $this->adminOnly();
        $kategoris = Kategori::orderBy('name')->get();
        return view('items.form', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $this->adminOnly();
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:items,code'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['nullable', 'string', 'max:150'],
            'unit' => ['required', 'string', 'max:30'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ]);
        Barang::create($data);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        $this->adminOnly();
        $kategoris = Kategori::orderBy('name')->get();
        return view('items.form', compact('barang', 'kategoris'));
    }

    public function update(Request $request, Barang $barang)
    {
        $this->adminOnly();
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('items', 'code')->ignore($barang->id)],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['nullable', 'string', 'max:150'],
            'unit' => ['required', 'string', 'max:30'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ]);
        // Stok berjalan hanya diubah melalui transaksi masuk/keluar agar tercatat di riwayat.
        $barang->update($data);
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $this->adminOnly();
        if ($barang->barangMasuk()->exists() || $barang->barangKeluar()->exists()) return back()->with('error', 'Barang sudah memiliki riwayat transaksi sehingga tidak dapat dihapus.');
        $barang->delete();
        return back()->with('success', 'Barang berhasil dihapus.');
    }
}
