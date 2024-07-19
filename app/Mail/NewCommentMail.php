<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCommentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    private $infos; // burada Job dan gelen bilgileri alıyoruz. bu bilgiler yorumu yapan userId ve commentId 
    public function __construct($infos)
    {
        $this->infos = $infos;
    }

    public function build(){
        return $this->view("NewCommentMailPage")->subject("New Comment Added")->with("infos", $this->infos);
    }

}
