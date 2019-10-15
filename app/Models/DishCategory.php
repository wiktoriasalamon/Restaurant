<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DishCategory extends Model
{
    protected $table = 'dish_category';

    protected $fillable = [
        'name'
    ];

    public function dish()
    {
        return $this->hasMany(Dish::class);
    }
}
