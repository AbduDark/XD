<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $products = Product::all();

        for ($i = 1; $i <= 20; $i++) {
            $subtotal = rand(500, 5000);
            $discount = rand(0, $subtotal * 0.2);
            $tax = ($subtotal - $discount) * 0.14; // 14% tax
            $total = $subtotal - $discount + $tax;

            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'user_id' => $users->random()->id,
                'customer_name' => 'عميل رقم ' . $i,
                'customer_phone' => '01' . rand(100000000, 999999999),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'status' => ['paid', 'pending', 'cancelled'][rand(0, 2)],
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now()->subDays(rand(0, 29)),
            ]);

            // Create invoice items
            $itemCount = rand(1, 5);
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->selling_price,
                    'total_price' => $quantity * $product->selling_price,
                ]);
            }
        }
    }
}
