<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

            return BarangMasuk::create([
                'item_id' => $barang->id,
                'supplier_id' => $this->supplierId,
                'user_id' => $this->userId,
                'date' => $this->date,
                'quantity' => $this->quantity,
                'note' => $this->note,
            ]);
        });
    }
}
