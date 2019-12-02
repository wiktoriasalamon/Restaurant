<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sendToMail;
    public $password;
    public $link;
    private $role;
    private const SUBJECT="Rejestracja w systemie restauracji \"W-17 wydział smaków\"";

    /**
     * RegistrationMail constructor.
     * @param string $password
     * @param string $login
     * @param string $role
     * @codeCoverageIgnore
     */
    public function __construct(string $password, string $login, string $role)
    {
        $this->sendToMail=$login;
        $this->password=$password;
        $this->link=route('login');
        $this->role=$role;
    }

    /**
     * Build the message.
     * @codeCoverageIgnore
     * @return $this
     */
    public function build()
    {
        return $this->checkRole();
    }

    /**
     * @return RegistrationMail
     * different mail for customer and worker
     * @codeCoverageIgnore
     */
    private function checkRole()
    {
        if($this->role=='customer'){
            return $this->view('mails.registrationCustomer') ->subject(self::SUBJECT);
        }
        return $this->view('mails.registration') ->subject(self::SUBJECT);
    }
    /**
     * sends mail to customer
     * @codeCoverageIgnore
     */
    public function sendMail()
    {
        Log::notice("Mail registration to:" . $this->sendToMail);
        Mail::to($this->sendToMail)->queue($this);
    }
}
