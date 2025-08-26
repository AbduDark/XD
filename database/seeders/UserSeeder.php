
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin
        User::create([
            'name' => 'الحسيني سوبر أدمن',
            'email' => 'alhussiny@super.com',
            'password' => Hash::make('alhussiny55555'),
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Admin
        User::create([
            'name' => 'الحسيني أدمن',
            'email' => 'alhussiny@admin.com',
            'password' => Hash::make('admin@1234'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
