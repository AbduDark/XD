<?php

// namespace Database\Seeders;

// use App\Models\CashTransfer;
// use App\Models\User;
// use Illuminate\Database\Seeder;
// use Carbon\Carbon;

// class CashTransferSeeder extends Seeder
// {
//     public function run()
//     {
//         $users = User::all();
//         $types = ['in', 'out'];
//         $descriptions = [
//             'in' => ['إيداع نقدي', 'تحصيل فاتورة', 'مبيعات نقدية', 'إيراد إضافي'],
//             'out' => ['سحب نقدي', 'شراء منتجات', 'مصاريف تشغيلية', 'مرتبات']
//         ];

//         for ($i = 1; $i <= 50; $i++) {
//             $type = $types[rand(0, 1)];
//             $typeDescriptions = $descriptions[$type];

//             CashTransfer::create([
//                 'type' => $type,
//                 'amount' => rand(100, 5000),
//                 'description' => $typeDescriptions[rand(0, count($typeDescriptions) - 1)],
//                 'user_id' => $users->random()->id,
//                 'created_at' => Carbon::now()->subDays(rand(0, 30)),
//                 'updated_at' => Carbon::now()->subDays(rand(0, 29)),
//             ]);
//         }
//     }
// }
