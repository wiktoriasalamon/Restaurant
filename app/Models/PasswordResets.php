<?php

namespace App\Models;


use App\Mails\ResetPasswordMail;
use Illuminate\Database\Eloquent\Model;

class PasswordResets extends Model
{
    protected $table = 'password_resets';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'email'
    ];


    /**
     * @param string $mail
     */
    public function sendResetEmail(string $mail)
    {
        $this->savePasswordReset($mail);
        (new ResetPasswordMail($this->token, $this->email))->sendMail();
    }


    /**
     * @param string $mail
     */
    private function savePasswordReset(string $mail): void
    {
        $this->email = $mail;
        $this->token = uniqid();
        $this->save();
    }

}
