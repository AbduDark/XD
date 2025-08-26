
<?php

namespace Database\Seeders;

use App\Models\ReturnItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReturnSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $users = User::all();
        $reasons = ['منتج معيب', 'عدم رضا العميل', 'مقاس خاطئ', 'لون خاطئ', 'استبدال بمنتج آخر'];
        
        for ($i = 1; $i <= 15; $i++) {
            ReturnItem::create([
                'return_number' => 'RET-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'customer_name' => 'عميل الاسترداد ' . $i,
                'customer_phone' => '01' . rand(100000000, 999999999),
                'product_id' => $products->random()->id,
                'quantity' => rand(1, 3),
                'return_price' => rand(50, 500),
                'reason' => $reasons[rand(0, count($reasons) - 1)],
                'status' => ['pending', 'approved', 'rejected'][rand(0, 2)],
                'user_id' => $users->random()->id,
                'created_at' => Carbon::now()->subDays(rand(0, 10)),
            ]);
        }
    }
}
