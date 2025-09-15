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

        static::creating(function ($invoice) {
            if (!$invoice->store_id && session('current_store_id')) {
                $invoice->store_id = session('current_store_id');
                // Generate invoice number per store
                $storeInvoiceCount = static::where('store_id', session('current_store_id'))->count();
                $invoice->invoice_number = 'INV-' . date('Y') . '-S' . session('current_store_id') . '-' . str_pad($storeInvoiceCount + 1, 6, '0', STR_PAD_LEFT);
            } else if (Auth::check() && !Auth::user()->isSuperAdmin()) {
                 // Fallback for cases where session might not be set but user is logged in and not super admin
                 if (!$invoice->store_id) {
                    // Attempt to get store_id from user if not explicitly set
                    $userStoreId = Auth::user()->stores()->value('id'); // Assuming user has a relationship to stores
                    if ($userStoreId) {
                        $invoice->store_id = $userStoreId;
                        $storeInvoiceCount = static::where('store_id', $userStoreId)->count();
                        $invoice->invoice_number = 'INV-' . date('Y') . '-S' . $userStoreId . '-' . str_pad($storeInvoiceCount + 1, 6, '0', STR_PAD_LEFT);
                    } else {
                        // If user has no store, generate a generic invoice number
                        $invoice->invoice_number = 'INV-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
                    }
                 } else {
                    // If store_id is already set, generate invoice number based on that store
                    $storeInvoiceCount = static::where('store_id', $invoice->store_id)->count();
                    $invoice->invoice_number = 'INV-' . date('Y') . '-S' . $invoice->store_id . '-' . str_pad($storeInvoiceCount + 1, 6, '0', STR_PAD_LEFT);
                 }
            } else {
                // For super admins or when no store context is available
                $invoice->invoice_number = 'INV-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}