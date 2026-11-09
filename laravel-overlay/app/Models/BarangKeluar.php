<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangKeluar extends Model
{
    protected $table = 'stock_outs';
    protected $fillable = ['item_id', 'user_id', 'date', 'quantity', 'note'];

    protected function casts(): array
    {
        return ['date' => 'date', 'quantity' => 'integer'];
    }

    public function barang(): BelongsTo { return $this->belongsTo(Barang::class, 'item_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
