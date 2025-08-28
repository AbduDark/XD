<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}