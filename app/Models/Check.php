<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    protected $table = 'check';

    protected $fillable = [
        'amount',
        'order_id',
        'dish_id'
    ];

    public function dish()
    {
        return $this->belongsTo(Dish::class,  'dish_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class,  'order_id', 'id');
    }
}
