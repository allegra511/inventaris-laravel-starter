<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Supplier;
use App\Services\BarangKeluarService;
use App\Services\BarangMasukService;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function formMasuk()
    {
        return view('transactions.form', [
            'type' => 'masuk',
            'barangs' => Barang::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    public function simpanMasuk(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        (new BarangMasukService(
            (int) $data['item_id'], (int) $data['quantity'], $data['date'],
            (int) $request->user()->id, (int) $data['supplier_id'], $data['note'] ?? null
        ))->prosesTransaksi();

        return redirect()->route('riwayat.index')->with('success', 'Transaksi barang masuk berhasil. Stok telah ditambahkan.');
    }

    public function formKeluar()
    {
        return view('transactions.form', [
            'type' => 'keluar',
            'barangs' => Barang::orderBy('name')->get(),
            'suppliers' => collect(),
        ]);
    }

    public function simpanKeluar(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date'],
            'note' => ['required', 'string', 'max:1000'],
        ]);

        (new BarangKeluarService(
            (int) $data['item_id'], (int) $data['quantity'], $data['date'],
            (int) $request->user()->id, $data['note']
        ))->prosesTransaksi();

        return redirect()->route('riwayat.index')->with('success', 'Transaksi barang keluar berhasil. Stok telah dikurangi.');
    }
}
