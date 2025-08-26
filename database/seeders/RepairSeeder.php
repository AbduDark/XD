
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
        $statuses = ['pending', 'in_progress', 'completed', 'delivered'];
        
        for ($i = 1; $i <= 30; $i++) {
            Repair::create([
                'repair_number' => 'REP-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'customer_name' => 'عميل الصيانة ' . $i,
                'customer_phone' => '01' . rand(100000000, 999999999),
                'device_type' => $devices[rand(0, count($devices) - 1)],
                'device_model' => 'موديل ' . rand(2020, 2024),
                'problem_description' => $problems[rand(0, count($problems) - 1)],
                'estimated_cost' => rand(100, 800),
                'actual_cost' => rand(100, 800),
                'status' => $statuses[rand(0, count($statuses) - 1)],
                'received_date' => Carbon::now()->subDays(rand(0, 20)),
                'delivery_date' => rand(0, 1) ? Carbon::now()->addDays(rand(1, 10)) : null,
                'notes' => 'ملاحظات إضافية للجهاز رقم ' . $i,
                'user_id' => $users->random()->id,
                'created_at' => Carbon::now()->subDays(rand(0, 20)),
            ]);
        }
    }
}
