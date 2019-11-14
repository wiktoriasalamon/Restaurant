<?php

namespace App\Mails;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class OrderOnlineMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sendToMail;
    public $link;
    private const SUBJECT="Zamówienie online w systemie restauracji \"W-17 wydział smaków\"";

    public function __construct(string $email, string $token)
    {
        $this->sendToMail=$email;
        $this->link=route('order.show',$token);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.order') ->subject(self::SUBJECT);
    }

    /**
     * sends mail to customer
     */
    public function sendMail()
    {
        Mail::to($this->sendToMail)->queue($this);

    }
}
