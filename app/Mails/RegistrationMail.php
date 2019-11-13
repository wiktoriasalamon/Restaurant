<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sendToMail;
    public $password;
    public $link;
    private const SUBJECT="Rejestracja w systemie restauracji \"W-17 wydział smaków\"";

    public function __construct(string $password, string $login)
    {
        $this->sendToMail=$login;
        $this->password=$password;
        $this->link=route('login');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.registration') ->subject(self::SUBJECT);
    }

    /**
     * sends mail to customer
     */
    public function sendMail()
    {
        Mail::to($this->sendToMail)->queue($this);

    }
}
