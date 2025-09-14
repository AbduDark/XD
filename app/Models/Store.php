<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'slug',
        'description',
        'description_ar',
        'owner_id',
        'phone',
        'address',
        'address_ar',
        'email',
        'logo',
        'settings',
        'is_active',
        'tax_rate',
        'currency',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'tax_rate' => 'decimal:2',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'store_users')->withPivot('role', 'is_active')->withTimestamps();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnItem::class);
    }

    public function cashTransfers()
    {
        return $this->hasMany(CashTransfer::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($store) {
            if (empty($store->slug)) {
                $store->slug = Str::slug($store->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
