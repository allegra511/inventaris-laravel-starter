<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalBarang' => Barang::count(),
            'totalSupplier' => Supplier::count(),
            'stokKritis' => Barang::whereColumn('stock', '<=', 'minimum_stock')->count(),
            'totalStok' => (int) Barang::sum('stock'),
            'barangStokKritis' => Barang::with('kategori')->whereColumn('stock', '<=', 'minimum_stock')->orderBy('stock')->limit(8)->get(),
            'barangMasukTerbaru' => BarangMasuk::with(['barang', 'supplier'])->latest('date')->latest('id')->limit(5)->get(),
            'barangKeluarTerbaru' => BarangKeluar::with(['barang', 'user'])->latest('date')->latest('id')->limit(5)->get(),
        ]);
    }
}
