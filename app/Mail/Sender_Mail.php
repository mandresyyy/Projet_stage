<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Sender_Mail extends Mailable
{
    use Queueable, SerializesModels;

    public $sujet;
    // public $piece_jointe;
    public $contenu;
    public $duree;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $content,$duree)
    {
        $this->sujet = $subject;
        $this->contenu = $content;
        $this->duree=$duree;
        // $this->piece_jointe = $piece_j;
    }

    public function build()
    {
        return $this
            ->subject($this->sujet)
            ->view($this->contenu)
            ->from(config('mail.from.address'), config('mail.from.name'));

    }
}
