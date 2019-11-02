<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservation';

    protected $fillable = [
        'date',
        'category_id',
        'start_time',
        'table_id',
        'email',
        'phone'
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function findTable(int $size):bool
    {
        if(Carbon::now()->format('Y-m-d')==$this->start_time){
            $table= Table::where('size',$size)->where('occupied_since',null)->whereDoesntHave('reservation', function ($query)  {
                $query->where('date', 'like', $this->date)->where('start_time','>=',$this->start_time);
            })->first();

        }else{
            $table= Table::where('size',$size)->whereDoesntHave('reservation', function ($query)  {
                $query->where('date', 'like', $this->date);
            })->first();
        }
        if($table){
            $this->table()->associate($table);
            return true;
        }
        return false;
    }
    public function setCustomer(string $email, string $phone)
    {
        if($email){
            $this->email=$email;
        }else{

        }
        if($phone){
            $this->phone=$phone;
        }


    }
}
