<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\StoreScope;
use Illuminate\Support\Facades\Auth;

class ReturnItem extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'product_name',
        'quantity',
        'amount',
        'reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'quantity' => 'integer'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new StoreScope);
        
        static::creating(function ($returnItem) {
            if (Auth::check() && !Auth::user()->isSuperAdmin()) {
                $currentStoreId = session('current_store_id');
                if ($currentStoreId) {
                    $returnItem->store_id = $currentStoreId;
                }
            }
        });
    }
}