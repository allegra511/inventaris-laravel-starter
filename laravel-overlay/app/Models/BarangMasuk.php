<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasuk extends Model
{
    protected $table = 'stock_ins';
    protected $fillable = ['transaction_code', 'item_id', 'supplier_id', 'user_id', 'date', 'quantity', 'note'];

    protected function casts(): array
    {
        return ['date' => 'date', 'quantity' => 'integer'];
    }

    public function barang(): BelongsTo { return $this->belongsTo(Barang::class, 'item_id'); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
