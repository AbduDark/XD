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
        $users = User::all();
        $products = Product::all();
        $reasons = ['منتج معيب', 'عدم مطابقة الوصف', 'طلب العميل', 'منتج منتهي الصلاحية'];

        for ($i = 1; $i <= 15; $i++) {
            $product = $products->random();
            $quantity = rand(1, 3);

            ReturnItem::create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'amount' => $quantity * $product->selling_price,
                'reason' => $reasons[rand(0, count($reasons) - 1)],
                'user_id' => $users->random()->id,
                'created_at' => Carbon::now()->subDays(rand(0, 20)),
                'updated_at' => Carbon::now()->subDays(rand(0, 19)),
            ]);
        }
    }
}
