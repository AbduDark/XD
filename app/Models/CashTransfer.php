<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\StoreScope;
use Illuminate\Support\Facades\Auth;

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
            if (Auth::check() && !Auth::user()->isSuperAdmin()) {
                $currentStoreId = session('current_store_id');
                if ($currentStoreId) {
                    $cashTransfer->store_id = $currentStoreId;
                }
            }
        });
    }
}