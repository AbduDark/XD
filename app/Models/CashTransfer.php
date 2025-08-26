
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'service', 'service_ar', 'amount', 'commission',
        'customer_phone', 'notes', 'user_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
