<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_barang_masuk_menambahkan_stok_dan_menyimpan_riwayat(): void
    {
        [$user, $barang, $supplier] = $this->buatDataDasar();

        $response = $this->actingAs($user)->post(route('transaksi.masuk.store'), [
            'item_id' => $barang->id,
            'supplier_id' => $supplier->id,
            'quantity' => 5,
            'date' => now()->toDateString(),
            'note' => 'Pembelian awal',
        ]);

        $response->assertRedirect(route('riwayat.index'));
        $this->assertDatabaseHas('items', ['id' => $barang->id, 'stock' => 15]);
        $this->assertDatabaseHas('stock_ins', ['item_id' => $barang->id, 'quantity' => 5]);
    }

    public function test_barang_keluar_ditolak_jika_stok_tidak_mencukupi(): void
    {
        [$user, $barang] = $this->buatDataDasar();

        $response = $this->actingAs($user)->from(route('transaksi.keluar'))->post(route('transaksi.keluar.store'), [
            'item_id' => $barang->id,
            'quantity' => 99,
            'date' => now()->toDateString(),
            'note' => 'Uji stok tidak cukup',
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseHas('items', ['id' => $barang->id, 'stock' => 10]);
        $this->assertDatabaseCount('stock_outs', 0);
    }

    public function test_barang_keluar_mengurangi_stok_dan_menyimpan_riwayat(): void
    {
        [$user, $barang] = $this->buatDataDasar();

        $response = $this->actingAs($user)->post(route('transaksi.keluar.store'), [
            'item_id' => $barang->id,
            'quantity' => 3,
            'date' => now()->toDateString(),
            'note' => 'Pemakaian kantor',
        ]);

        $response->assertRedirect(route('riwayat.index'));
        $this->assertDatabaseHas('items', ['id' => $barang->id, 'stock' => 7]);
        $this->assertDatabaseHas('stock_outs', ['item_id' => $barang->id, 'quantity' => 3]);
    }

    private function buatDataDasar(): array
    {
        $user = User::create([
            'name' => 'Tester',
            'email' => 'tester@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);
        $kategori = Kategori::create(['name' => 'Elektronik']);
        $supplier = Supplier::create(['name' => 'Supplier Uji']);
        $barang = Barang::create([
            'code' => 'TEST-001',
            'name' => 'Barang Uji',
            'category_id' => $kategori->id,
            'location' => 'Rak A1',
            'unit' => 'pcs',
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        return [$user, $barang, $supplier];
    }
}
