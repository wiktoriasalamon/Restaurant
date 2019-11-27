<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dish extends Model
{
    use SoftDeletes;

    protected $table = 'dish';

    protected $fillable = [
        'name',
        'category_id',
        'price'
    ];

    public function check()
    {
        return $this->hasMany(Check::class);
    }

    public function category()
    {
        return $this->belongsTo(Dish::class);
    }
}
