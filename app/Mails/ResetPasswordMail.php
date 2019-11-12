<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sendToMail;
    public $link;
    private const SUBJECT="Zmiana hasła w systemie restauracji \"W-17 wydział smaków\"";

    public function __construct(string $token, string $email)
    {
        $this->sendToMail=$email;
        $this->link=route('password.reset',$token);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.resetPassword') ->subject(self::SUBJECT);
    }

    /**
     * sends mail to customer
     */
    public function sendMail()
    {
        Mail::to($this->sendToMail)->queue($this);

    }
}
