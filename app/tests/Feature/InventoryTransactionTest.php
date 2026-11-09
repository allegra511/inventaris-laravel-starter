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
        $transaksi = \App\Models\BarangKeluar::latest('id')->firstOrFail();
        $this->assertStringStartsWith('OUT-' . now()->format('Ymd') . '-', $transaksi->transaction_code);
    }

    public function test_kode_barang_dibuat_otomatis_dan_stok_awal_nol(): void
    {
        [$user] = $this->buatDataDasar();
        $kategori = Kategori::first();

        $response = $this->actingAs($user)->post(route('barang.store'), [
            'name' => 'Keyboard Mechanical',
            'category_id' => $kategori->id,
            'location' => 'Rak C1',
            'unit' => 'pcs',
            'minimum_stock' => 3,
            'target_stock' => 12,
        ]);

        $response->assertRedirect(route('barang.index'));
        $barang = Barang::where('name', 'Keyboard Mechanical')->firstOrFail();
        $this->assertStringStartsWith('BRG-' . now()->format('Ymd') . '-', $barang->code);
        $this->assertSame(0, $barang->stock);
        $this->assertSame(12, $barang->target_stock);
    }

    public function test_barang_masuk_membuat_nomor_transaksi_otomatis_dan_tanggal_default(): void
    {
        [$user, $barang, $supplier] = $this->buatDataDasar();

        $response = $this->actingAs($user)->post(route('transaksi.masuk.store'), [
            'item_id' => $barang->id,
            'supplier_id' => $supplier->id,
            'quantity' => 2,
            'note' => 'Penerimaan tanpa input tanggal',
        ]);

        $response->assertRedirect(route('riwayat.index'));
        $transaksi = \App\Models\BarangMasuk::latest('id')->firstOrFail();
        $this->assertSame(now()->toDateString(), $transaksi->date->format('Y-m-d'));
        $this->assertStringStartsWith('IN-' . now()->format('Ymd') . '-', $transaksi->transaction_code);
        $this->assertSame($user->id, $transaksi->user_id);
    }

    public function test_saran_restock_dihitung_otomatis_dari_target_stock(): void
    {
        [$user, $barang] = $this->buatDataDasar();
        $barang->update(['stock' => 4, 'minimum_stock' => 5, 'target_stock' => 15]);

        $this->assertSame(11, $barang->fresh()->jumlahRestockDisarankan());
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
