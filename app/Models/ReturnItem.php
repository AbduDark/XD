<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\StoreScope;
use Illuminate\Support\Facades\Auth;

class ReturnItem extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'invoice_id',
        'product_id',
        'user_id',
        'store_id',
        'product_name',
        'quantity',
        'amount',
        'reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'quantity' => 'integer'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new StoreScope);

        static::creating(function ($returnItem) {
            if (!$returnItem->store_id && session('current_store_id')) {
                $returnItem->store_id = session('current_store_id');
            }
        });
    }
}