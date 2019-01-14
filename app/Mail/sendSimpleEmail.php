<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class sendSimpleEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $content)
    {
        $this->subject = $subject;
        $this->content = $content;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('indsocks@gmail.com', Auth::user()->name)
            ->bcc(Auth::user()->email)
            ->replyTo(Auth::user()->email, Auth::user()->name)
            ->subject($this->subject)
            ->view('emails.emailContent')->with(['content' => $this->content]);
    }
}
