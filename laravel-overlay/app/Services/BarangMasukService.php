<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BarangMasukService extends Transaksi
{
    public function __construct(
        int $itemId,
        int $quantity,
        string $date,
        int $userId,
        private int $supplierId,
        ?string $note = null,
    ) {
        parent::__construct($itemId, $quantity, $date, $userId, $note);
    }

    public function prosesTransaksi(): Model
    {
        return DB::transaction(function () {
            $barang = Barang::query()->lockForUpdate()->findOrFail($this->itemId);
            $barang->tambahStok($this->quantity);

            $transaksi = BarangMasuk::create([
                'item_id' => $barang->id,
                'supplier_id' => $this->supplierId,
                'user_id' => $this->userId,
                'date' => $this->date,
                'quantity' => $this->quantity,
                'note' => $this->note,
            ]);

            $transaksi->forceFill([
                'transaction_code' => 'IN-' . Carbon::parse($this->date)->format('Ymd') . '-' . str_pad((string) $transaksi->id, 6, '0', STR_PAD_LEFT),
            ])->save();

            return $transaksi;
        });
    }
}
