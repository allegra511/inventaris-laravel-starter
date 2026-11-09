<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'items';
    protected $fillable = ['code', 'name', 'category_id', 'location', 'unit', 'stock', 'minimum_stock'];

    protected function casts(): array
    {
        return ['stock' => 'integer', 'minimum_stock' => 'integer'];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }

    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class, 'item_id');
    }

    public function barangKeluar(): HasMany
    {
        return $this->hasMany(BarangKeluar::class, 'item_id');
    }

    public function tambahStok(int $jumlah): void
    {
        if ($jumlah < 1) {
            throw new \InvalidArgumentException('Jumlah penambahan harus lebih dari nol.');
        }
        $this->stock += $jumlah;
        $this->save();
    }

    public function kurangiStok(int $jumlah): bool
    {
        if ($jumlah < 1 || $this->stock < $jumlah) {
            return false;
        }
        $this->stock -= $jumlah;
        $this->save();
        return true;
    }

    public function isStokMinimum(): bool
    {
        return $this->stock <= $this->minimum_stock;
    }
}
