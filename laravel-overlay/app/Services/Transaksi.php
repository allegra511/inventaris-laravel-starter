<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

abstract class Transaksi
{
    public function __construct(
        protected int $itemId,
        protected int $quantity,
        protected string $date,
        protected int $userId,
        protected ?string $note = null,
    ) {
        if ($this->quantity < 1) {
            throw new \InvalidArgumentException('Jumlah transaksi harus lebih dari nol.');
        }
    }

    abstract public function prosesTransaksi(): Model;
}
