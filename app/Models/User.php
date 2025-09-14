<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }

    public function cashTransfers()
    {
        return $this->hasMany(CashTransfer::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnItem::class);
    }

    public function ownedStores()
    {
        return $this->hasMany(Store::class, 'owner_id');
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_users')->withPivot('role', 'is_active')->withTimestamps();
    }

    public function currentStore()
    {
        return $this->stores()->wherePivot('is_active', true)->first() ?? $this->ownedStores()->where('is_active', true)->first();
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    public function canAccessStore($storeId)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        return $this->ownedStores()->where('id', $storeId)->exists() 
            || $this->stores()->wherePivot('is_active', true)->where('stores.id', $storeId)->exists();
    }
}
