<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $table = 'table';

    protected $fillable = [
        'size',
        'occupied_since'
    ];

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
