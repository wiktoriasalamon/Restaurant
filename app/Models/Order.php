<?php

namespace App\Models;

use App\Interfaces\StatusTypesInterface;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Filter order with given status
     * @param Builder $query
     * @param string $statusTypes
     * @return Builder
     */
    public function scopeStatus(Builder $query, string $statusTypes): Builder
    {
        return $query->where('status', $statusTypes);
    }
    /**
     * Filter order yhat are different that given status
     * @param Builder $query
     * @param string $statusTypes
     * @return Builder
     */
    public function scopeStatusNotEqual(Builder $query, string $statusTypes): Builder
    {
        return $query->where('status',"!=", $statusTypes);
    }
    /**
     * Filter order which were ordered in restaurant
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderedLocal(Builder $query): Builder
    {
        return $query->where('address', null)->where('takeaway', false);
    }
    /**
     * Filter takeaway order
     * @param Builder $query
     * @return Builder
     */
    public function scopeTakeaway(Builder $query): Builder
    {
        return $query->where('address', null)->where('takeaway', true);
    }
    /**
     * Filter online order
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnline(Builder $query): Builder
    {
        return $query->where('address',"!=", null)->where('takeaway', true);
    }
}
