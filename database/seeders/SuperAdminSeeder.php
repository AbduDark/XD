<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin User only if not exists
        User::firstOrCreate([
            'email' => 'admin@admin.com'
        ], [
            'name' => 'مدير النظام الرئيسي',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        echo "تم إنشاء مدير النظام الرئيسي بنجاح\n";
    }
}
