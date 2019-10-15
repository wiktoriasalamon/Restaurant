<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';

    protected $fillable = [
        'worker_id',
        'table_id',
        'status',
        'customer_id',
        'address',
        'deliverer_location',
        'comment'
    ];

    public function check()
    {
        return $this->hasMany(Check::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class,  'worker_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class,  'customer_id', 'id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
