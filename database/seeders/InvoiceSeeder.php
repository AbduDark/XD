
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
        $products = Product::all();
        $users = User::all();
        
        // إنشاء 50 فاتورة
        for ($i = 1; $i <= 50; $i++) {
            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'customer_name' => 'عميل رقم ' . $i,
                'customer_phone' => '01' . rand(100000000, 999999999),
                'total_amount' => 0,
                'discount' => rand(0, 100),
                'final_amount' => 0,
                'payment_method' => ['cash', 'card', 'transfer'][rand(0, 2)],
                'user_id' => $users->random()->id,
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);

            $total = 0;
            $itemsCount = rand(1, 5);
            
            for ($j = 0; $j < $itemsCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->selling_price;
                $subtotal = $quantity * $price;
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
                
                $total += $subtotal;
            }
            
            $finalAmount = $total - $invoice->discount;
            $invoice->update([
                'total_amount' => $total,
                'final_amount' => $finalAmount,
            ]);
        }
    }
}
