<?php

namespace Database\Seeders;

use App\Models\CashTransfer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CashTransferSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $stores = \App\Models\Store::all();

        if ($stores->isEmpty() || $users->isEmpty()) {
            return;
        }

        $services = ['vodafone_cash', 'etisalat_cash', 'orange_cash', 'access_cash', 'cards'];
        $servicesAr = ['فودافون كاش', 'اتصالات كاش', 'أورانج كاش', 'أكسيس كاش', 'كروت'];

        for ($i = 1; $i <= 30; $i++) {
            $serviceIndex = rand(0, count($services) - 1);
            $amount = rand(100, 2000);

            CashTransfer::create([
                'service' => $services[$serviceIndex],
                'service_ar' => $servicesAr[$serviceIndex],
                'amount' => $amount,
                'commission' => $amount * 0.02, // 2% commission
                'customer_phone' => '01' . rand(100000000, 999999999),
                'notes' => 'تحويل نقدي رقم ' . $i,
                'user_id' => $users->random()->id,
                'store_id' => $stores->random()->id,
                'type' => ['in', 'out'][rand(0, 1)],
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now()->subDays(rand(0, 29)),
            ]);
        }
    }
}
