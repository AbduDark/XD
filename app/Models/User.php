<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user can access a specific store
     */
    public function canAccessStore(int $storeId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->ownedStores()->where('id', $storeId)->exists() ||
               $this->stores()->where('store_id', $storeId)->exists();
    }

    /**
     * Get stores owned by this user
     */
    public function ownedStores()
    {
        return $this->hasMany(Store::class, 'owner_id');
    }

    /**
     * Get stores this user has access to (many-to-many relationship)
     */
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_users')->withPivot('role', 'is_active')->withTimestamps();
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

    public function isEmployee()
    {
        return $this->role === 'employee';
    }
}