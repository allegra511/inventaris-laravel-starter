<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@inventaris.test'], [
            'name' => 'Admin Demo', 'password' => Hash::make('password'), 'role' => 'admin',
        ]);
        User::updateOrCreate(['email' => 'gudang@inventaris.test'], [
            'name' => 'Petugas Gudang Demo', 'password' => Hash::make('password'), 'role' => 'petugas_gudang',
        ]);

        $elektronik = Kategori::firstOrCreate(['name' => 'Elektronik'], ['description' => 'Perangkat elektronik dan komputer']);
        $atk = Kategori::firstOrCreate(['name' => 'ATK'], ['description' => 'Alat tulis kantor']);
        $supplier = Supplier::firstOrCreate(['name' => 'Supplier Demo'], [
            'contact_person' => 'Kontak Demo', 'phone' => '081234567890', 'email' => 'supplier@example.test', 'address' => 'Alamat demo',
        ]);

        Barang::firstOrCreate(['code' => 'BRG-EL-001'], [
            'name' => 'Mouse Wireless', 'category_id' => $elektronik->id, 'location' => 'Rak A1', 'unit' => 'pcs', 'stock' => 20, 'minimum_stock' => 5,
        ]);
        Barang::firstOrCreate(['code' => 'BRG-ATK-001'], [
            'name' => 'Kertas A4', 'category_id' => $atk->id, 'location' => 'Rak B1', 'unit' => 'rim', 'stock' => 4, 'minimum_stock' => 5,
        ]);
    }
}
