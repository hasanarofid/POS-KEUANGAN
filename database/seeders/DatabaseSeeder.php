<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Settings Default
        $this->call(SettingSeeder::class);

        // Roles
        $this->call(RoleSeeder::class);

        // Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Agung',
                'password' => bcrypt('password'),
            ]
        );

        $admin->assignRole('super_admin');

        // Gudang User
        $gudang = User::updateOrCreate(
            ['email' => 'gudang@email.com'],
            [
                'name' => 'Staff Gudang',
                'password' => bcrypt('password'),
            ]
        );
        $gudang->assignRole('gudang');

        // Kasir User
        $kasir = User::updateOrCreate(
            ['email' => 'kasir@email.com'],
            [
                'name' => 'Staff Kasir',
                'password' => bcrypt('password'),
            ]
        );
        $kasir->assignRole('kasir');

        // Products
        $this->call(ProductSeeder::class);

        // Customers
        $this->call(CustomerSeeder::class);

        // Specific Users
        $this->call(SpecificUserSeeder::class);

        // Stok Awal, Pembelian & Mutasi Masuk
        $this->call(StockAndPurchaseSeeder::class);

        // Penjualan, Mutasi Keluar & Pengeluaran Kas
        $this->call(TransactionSeeder::class);
    }
}
