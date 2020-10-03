<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'slug', 'created_by'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function colorVariations()
    {
        return $this->hasMany(ProductsVariations::class, 'product_id')
            ->with('color');
    }
}
