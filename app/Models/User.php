<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    protected $guard_name = 'web';
//    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @codeCoverageIgnore
     * @return HasMany
     */
    public function orderWorker()
    {
        return $this->hasMany(Order::class, 'id', 'worker_id');
    }

    /**
     * @codeCoverageIgnore
     * @return HasMany
     */
    public function orderCustomer()
    {
        return $this->hasMany(Order::class, 'id', 'customer_id');
    }

    /**
     * @param $request
     * @codeCoverageIgnore
     */
    public function fillUser($request)
    {
        $this->name = $request->name;
        $this->surname = $request->surname;
        $this->email = $request->email;
        $this->address = json_encode($request->address);
        $this->phone = $request->phone;
        if (!$this->remember_token) {
            $this->remember_token = Str::random(10);
        }
    }

    /**
     * @return array
     */
    public function fetchUserData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone
        ];
    }

    /**
     * @codeCoverageIgnore
     * @return bool
     */
    public function ifAuth(): bool
    {
        return $this === Auth::user();
    }

    /**
     * @codeCoverageIgnore
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = Hash::make($password);
    }
}
