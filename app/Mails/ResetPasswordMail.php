<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sendToMail;
    public $link;
    private const SUBJECT="Zmiana hasła w systemie restauracji \"W-17 wydział smaków\"";

    /**
     * ResetPasswordMail constructor.
     * @param string $token
     * @param string $email
     * @codeCoverageIgnore
     */
    public function __construct(string $token, string $email)
    {
        $this->sendToMail=$email;
        $this->link=route('password.reset',$token);
    }

    /**
     * Build the message.
     *  @codeCoverageIgnore
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.resetPassword') ->subject(self::SUBJECT);
    }

    /**
     * sends mail to customer
     * @codeCoverageIgnore
     */
    public function sendMail()
    {
        Log::notice("Mail reset password to:" . $this->sendToMail);
        Mail::to($this->sendToMail)->queue($this);
    }
}
