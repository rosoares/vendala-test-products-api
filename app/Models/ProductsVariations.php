<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsVariations extends Model
{
    use SoftDeletes;

    protected $table = 'products_has_variations';

    protected $fillable = [
        'product_id',
        'color_id',
        'first_stock',
        'available_stock',
        'price'
    ];

    public function color()
    {
        return $this->belongsTo(Colors::class, 'color_id');
    }
}
