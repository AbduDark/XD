<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\StoreScope;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_ar', 'code', 'category_id',
        'purchase_price', 'selling_price', 'quantity',
        'min_quantity', 'description'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnItem::class);
    }

    public function isLowStock()
    {
        return $this->quantity <= $this->min_quantity;
    }

    public function getProfit()
    {
        return $this->selling_price - $this->purchase_price;
    }

    protected static function booted()
    {
        static::addGlobalScope(new StoreScope);

        static::creating(function ($product) {
            if (!$product->store_id && session('current_store_id')) {
                $product->store_id = session('current_store_id');
            }
        });
    }
}