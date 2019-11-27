<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    /**
     * @param int $size
     * @return bool
     */
    public function findTable(int $size):bool
    {
        if(Carbon::now()->format('Y-m-d')==$this->date){
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

    /**
     * @param  $email
     * @codeCoverageIgnore
     * @param  $phone
     */
    public function setCustomer($email,$phone)
    {
        $auth=Auth::user();
        $this->email=$auth->email;
        $this->phone=$auth->phone;
        if($email){
            $this->email=$email;
        }
        if($phone){
            $this->phone=$phone;
        }
    }


}
