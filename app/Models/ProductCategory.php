<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_ar'];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\StoreScope;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'description_ar',
        'store_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    protected static function booted()
    {
        static::addGlobalScope(new StoreScope);

        static::creating(function ($category) {
            if (!$category->store_id && session('current_store_id')) {
                $category->store_id = session('current_store_id');
            }
        });
    }
}
