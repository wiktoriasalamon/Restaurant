<?php

namespace App\Models;

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

    public function findTable(string $size):bool
    {

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
