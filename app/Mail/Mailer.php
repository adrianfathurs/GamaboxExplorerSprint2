<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Mailer extends Mailable
{
    use Queueable, SerializesModels;
     
    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $demo;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($demo)
    {
        $this->demo = $demo;
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->demo['senderEmail'])
                    ->view('mail')
                    ->to($this->demo['emails']);
                    // ->text('plain_mail')
                    // ->with(
                    //   [
                    //         'testVarOne' => '1',
                    //         'testVarTwo' => '2',
                    //   ])
                    // ->attach(public_path('/images').'/demo.jpg', [
                    //         'as' => 'demo.jpg',
                    //         'mime' => 'image/jpeg',
                    // ]);
    }
}
