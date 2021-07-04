<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailCampaign extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $html = "";
    public $fromEmail = "";
    public function __construct($html, $_from)
    {
        //
        $this->html = $html;
        $this->fromEmail = $_from;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("admin@autobox365.com")->subject('New campaign')->view('email.html');
    }
}
