<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExistClientUseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        $clientName = $this->data->full_name;
        $userName = $this->data->full_name;
        $admin = getLogInUser()->full_name;
        $subject = 'Welcome '.$clientName.' to '.getAppName();

        return $this->view('emails.exist_client_user_mail',
            compact('clientName', 'userName','admin'))
            ->markdown('emails.exist_client_user_mail')
            ->subject($subject);
    }
}
