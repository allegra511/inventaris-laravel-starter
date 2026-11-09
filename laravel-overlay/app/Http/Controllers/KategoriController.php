<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    private function adminOnly(): void { abort_unless(auth()->user()->isAdmin(), 403, 'Akses khusus Admin.'); }

    public function index()
    {
        $kategoris = Kategori::withCount('barang')->orderBy('name')->paginate(10);
        return view('categories.index', compact('kategoris'));
    }

    public function create() { $this->adminOnly(); return view('categories.form'); }

    public function store(Request $request)
    {
        $this->adminOnly();
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:categories,name'], 'description' => ['nullable', 'string', 'max:1000']]);
        Kategori::create($data);
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori) { $this->adminOnly(); return view('categories.form', compact('kategori')); }

    public function update(Request $request, Kategori $kategori)
    {
        $this->adminOnly();
        $data = $request->validate(['name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($kategori->id)], 'description' => ['nullable', 'string', 'max:1000']]);
        $kategori->update($data);
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $this->adminOnly();
        if ($kategori->barang()->exists()) return back()->with('error', 'Kategori masih digunakan oleh barang.');
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
