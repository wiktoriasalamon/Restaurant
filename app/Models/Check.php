<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Check extends Model
{
    protected $table = 'check';

    protected $fillable = [
        'amount',
        'order_id',
        'dish_id'
    ];

    /**
     * @codeCoverageIgnore
     * @return BelongsTo
     */
    public function dish()
    {
        return $this->belongsTo(Dish::class,  'dish_id', 'id');
    }

    /**
     * @codeCoverageIgnore
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class,  'order_id', 'id');
    }
}
