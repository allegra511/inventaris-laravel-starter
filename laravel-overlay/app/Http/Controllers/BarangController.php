<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    private function adminOnly(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses khusus Admin.');
    }

    public function index()
    {
        $query = Barang::with('kategori')->orderBy('name');
        if (request('q')) {
            $term = request('q');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%"));
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
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['nullable', 'string', 'max:150'],
            'unit' => ['required', 'string', 'max:30'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'target_stock' => ['required', 'integer', 'min:0', 'gte:minimum_stock'],
        ]);

        DB::transaction(function () use ($data) {
            // Temporary unique code allows the database to assign the item ID first.
            $barang = Barang::create($data + [
                'code' => 'TMP-' . (string) Str::uuid(),
                // Initial stock is always zero. Stock must enter through a recorded receipt.
                'stock' => 0,
            ]);

            $barang->forceFill([
                'code' => 'BRG-' . now()->format('Ymd') . '-' . str_pad((string) $barang->id, 6, '0', STR_PAD_LEFT),
            ])->save();
        });

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan. Kode dibuat otomatis; catat stok awal melalui transaksi barang masuk.');
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
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['nullable', 'string', 'max:150'],
            'unit' => ['required', 'string', 'max:30'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'target_stock' => ['required', 'integer', 'min:0', 'gte:minimum_stock'],
        ]);

        // Running stock changes only through stock-in/out transactions.
        $barang->update($data);
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $this->adminOnly();
        if ($barang->barangMasuk()->exists() || $barang->barangKeluar()->exists()) {
            return back()->with('error', 'Barang sudah memiliki riwayat transaksi sehingga tidak dapat dihapus.');
        }

        $barang->delete();
        return back()->with('success', 'Barang berhasil dihapus.');
    }
}
