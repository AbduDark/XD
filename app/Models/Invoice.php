<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\StoreScope;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'user_id', 'customer_name', 
        'customer_phone', 'subtotal', 'discount', 
        'tax', 'total', 'status'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnItem::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new StoreScope);
        parent::boot();

        static::creating(function ($invoice) {
            // Auto-assign store_id
            if (Auth::check() && !Auth::user()->isSuperAdmin()) {
                $currentStoreId = session('current_store_id');
                if ($currentStoreId) {
                    $invoice->store_id = $currentStoreId;
                    // Generate invoice number per store
                    $storeInvoiceCount = static::where('store_id', $currentStoreId)->count();
                    $invoice->invoice_number = 'INV-' . date('Y') . '-S' . $currentStoreId . '-' . str_pad($storeInvoiceCount + 1, 6, '0', STR_PAD_LEFT);
                } else {
                    $invoice->invoice_number = 'INV-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
                }
            } else {
                $invoice->invoice_number = 'INV-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
