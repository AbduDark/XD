
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin only if not exists
        User::firstOrCreate([
            'email' => 'alhussiny@super.com'
        ], [
            'name' => 'الحسيني سوبر أدمن',
            'password' => Hash::make('alhussiny55555'),
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Admin only if not exists
        User::firstOrCreate([
            'email' => 'alhussiny@admin.com'
        ], [
            'name' => 'الحسيني أدمن',
            'password' => Hash::make('admin@1234'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Employee only if not exists
        User::firstOrCreate([
            'email' => 'employ@employ.com'
        ], [
            'name' => 'الحسيني موظف',
            'password' => Hash::make('employ@1234'),
            'role' => 'employee',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        echo "تم إنشاء المستخدمين بنجاح\n";
    }
}
