<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            StoreSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            InvoiceSeeder::class,
            RepairSeeder::class,
            ReturnSeeder::class,
            CashTransferSeeder::class,
            ReportsSeeder::class,
        ]);
    }
}