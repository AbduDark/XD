<?php

namespace Database\Seeders;

use App\Models\Repair;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RepairSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $devices = ['iPhone 14 Pro', 'Samsung Galaxy S23', 'iPad Air', 'MacBook Pro', 'Laptop HP', 'Tablet Samsung'];
        $problems = ['شاشة مكسورة', 'بطارية تالفة', 'مشكلة في الشحن', 'مشكلة في الصوت', 'بطء في الأداء', 'مشكلة في الكاميرا'];
        $repairTypes = ['hardware', 'software'];
        $statuses = ['pending', 'in_progress', 'completed', 'delivered'];

        for ($i = 1; $i <= 30; $i++) {
            $stores = \App\Models\Store::all();
            $storeId = $stores->isNotEmpty() ? $stores->random()->id : 1;

            Repair::create([
                'repair_number' => 'REP-2025-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'customer_name' => 'عميل الصيانة ' . $i,
                'customer_phone' => '01' . rand(100000000, 999999999),
                'device_type' => $devices[rand(0, count($devices) - 1)],
                'problem_description' => $problems[rand(0, count($problems) - 1)],
                'repair_type' => $repairTypes[rand(0, count($repairTypes) - 1)],
                'repair_cost' => rand(100, 800),
                'status' => $statuses[rand(0, count($statuses) - 1)],
                'user_id' => $users->random()->id,
                'store_id' => $storeId,
                'created_at' => Carbon::now()->subDays(rand(0, 20)),
                'updated_at' => Carbon::now()->subDays(rand(0, 19)),
            ]);
        }
    }
}
