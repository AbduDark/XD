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
            ProductCategorySeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            InvoiceSeeder::class,
            RepairSeeder::class,
            // CashTransferSeeder::class,
            // ReturnSeeder::class,
        ]);
    }
}
