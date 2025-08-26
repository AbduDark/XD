
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
        $types = ['in', 'out'];
        $descriptions = [
            'سحب نقدي من الخزنة',
            'إيداع نقدي في الخزنة',
            'مصاريف إدارية',
            'عمولة مبيعات',
            'شراء معدات',
            'أرباح يومية'
        ];
        
        for ($i = 1; $i <= 25; $i++) {
            CashTransfer::create([
                'type' => $types[rand(0, 1)],
                'amount' => rand(100, 2000),
                'description' => $descriptions[rand(0, count($descriptions) - 1)],
                'user_id' => $users->random()->id,
                'created_at' => Carbon::now()->subDays(rand(0, 15)),
            ]);
        }
    }
}
