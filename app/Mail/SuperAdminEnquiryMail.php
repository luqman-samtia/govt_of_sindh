<?php

namespace App\Mail;

use App\Models\SuperAdminEnquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class SuperAdminEnquiryMail extends Mailable
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
        $full_name = $this->data['full_name'];
        $email = $this->data['email'];
        $message = $this->data['message'];
        $EmailExists = SuperAdminEnquiry::whereEmail($email)->exists();

        return $this->view('emails.superadmin_enquiry_mail',
            compact('email', 'EmailExists', 'full_name','message'))
            ->markdown('emails.superadmin_enquiry_mail');
    }
}
