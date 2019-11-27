<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes;

    protected $table = 'table';

    protected $fillable = [
        'size',
        'occupied_since'
    ];

    /**
    * @codeCoverageIgnore
    */
    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
    * @codeCoverageIgnore
    */
    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
