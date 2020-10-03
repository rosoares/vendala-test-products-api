<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colors extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = ['id', 'name'];
}
