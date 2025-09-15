<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\StoreScope;
use Illuminate\Support\Facades\Auth;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_number', 'customer_name', 'customer_phone',
        'device_type', 'problem_description', 'repair_type',
        'repair_cost', 'status', 'user_id'
    ];

    protected $casts = [
        'repair_cost' => 'decimal:2',
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
        parent::boot();

        static::creating(function ($repair) {
            // Auto-assign store_id
            if (Auth::check() && !Auth::user()->isSuperAdmin()) {
                $currentStoreId = session('current_store_id');
                if ($currentStoreId) {
                    $repair->store_id = $currentStoreId;
                    // Generate repair number per store
                    $storeRepairCount = static::where('store_id', $currentStoreId)->count();
                    $repair->repair_number = 'REP-' . date('Y') . '-S' . $currentStoreId . '-' . str_pad($storeRepairCount + 1, 6, '0', STR_PAD_LEFT);
                } else {
                    $repair->repair_number = 'REP-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
                }
            } else {
                $repair->repair_number = 'REP-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}