<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;


class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $products = Product::all();
        // $users = User::all();
        $faker = FakerFactory::create('ar_SA');


        // إنشاء 20 فاتورة
        foreach (range(1, 20) as $i) {
            $invoice = Invoice::create([
                'invoice_number' => 'INV-2025-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'customer_name' => $faker->name,
                'customer_phone' => $faker->phoneNumber,
                'subtotal' => 0,
                'discount' => $faker->randomFloat(2, 0, 100),
                'total' => 0,
                'status' => 'paid',
                'user_id' => 1,
                'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
            ]);

            $subtotal = 0;
            $itemsCount = $faker->numberBetween(1, 5);

            for ($j = 0; $j < $itemsCount; $j++) {
                $product = Product::inRandomOrder()->first();
                $quantity = $faker->numberBetween(1, 3);
                $unitPrice = $product->selling_price;
                $totalPrice = $unitPrice * $quantity;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);

                $subtotal += $totalPrice;
            }

            $total = $subtotal - $invoice->discount;
            $invoice->update([
                'subtotal' => $subtotal,
                'total' => $total,
            ]);
        }
    }
}