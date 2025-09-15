<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\StoreScope;

class CashTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'service', 'service_ar', 'amount', 'commission',
        'customer_phone', 'notes', 'user_id', 'type'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission' => 'decimal:2',
    ];

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

        static::creating(function ($cashTransfer) {
            if (!$cashTransfer->store_id && session('current_store_id')) {
                $cashTransfer->store_id = session('current_store_id');
            }
        });
    }
}