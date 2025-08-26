
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($repair) {
            $repair->repair_number = 'REP-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
        });
    }
}
