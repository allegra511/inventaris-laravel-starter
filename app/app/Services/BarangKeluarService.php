<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BarangKeluarService extends Transaksi
{
    public function prosesTransaksi(): Model
    {
        return DB::transaction(function () {
            $barang = Barang::query()->lockForUpdate()->findOrFail($this->itemId);

            if (!$barang->kurangiStok($this->quantity)) {
                throw ValidationException::withMessages([
                    'quantity' => "Stok {$barang->name} tidak mencukupi. Stok tersedia: {$barang->stock}.",
                ]);
            }

            $transaksi = BarangKeluar::create([
                'item_id' => $barang->id,
                'user_id' => $this->userId,
                'date' => $this->date,
                'quantity' => $this->quantity,
                'note' => $this->note,
            ]);

            $transaksi->forceFill([
                'transaction_code' => 'OUT-' . Carbon::parse($this->date)->format('Ymd') . '-' . str_pad((string) $transaksi->id, 6, '0', STR_PAD_LEFT),
            ])->save();

            return $transaksi;
        });
    }
}
