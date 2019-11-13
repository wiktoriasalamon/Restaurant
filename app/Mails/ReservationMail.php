<?php

namespace App\Mails;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class ReservationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sendToMail;
    public $date;
    public $time;
    public $link;
    public $size;
    private const SUBJECT="Rezerwacja w systemie restauracji \"W-17 wydział smaków\"";

    public function __construct(Reservation $reservation)
    {
        $this->sendToMail=$reservation->email;
        $this->date=$reservation->date;
        $this->link=route('reservation.indexUser');//todo: route do podglądu
        $this->time=$reservation->start_time;
        $this->size=$reservation->table->size;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.reservation') ->subject(self::SUBJECT);
    }

    /**
     * sends mail to customer
     */
    public function sendMail()
    {
        Mail::to($this->sendToMail)->queue($this);

    }
}
