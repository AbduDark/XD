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
            SuperAdminSeeder::class, // This creates stores and users first
            ProductCategorySeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            InvoiceSeeder::class,
            RepairSeeder::class,
            ReturnSeeder::class,
            // CashTransferSeeder::class,
            ReportsSeeder::class,
        ]);
    }
}