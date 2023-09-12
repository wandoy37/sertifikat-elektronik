<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifySertifikat extends Mailable
{
    use Queueable, SerializesModels;
    public $data_notify;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data_notify)
    {
        $this->data_notify = $data_notify;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->data_notify['subject'],
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mail.sertifikat',
            with: [
                'nama' => $this->data_notify['nama'],
                'kegiatan' => $this->data_notify['kegiatan'],
                'sertifikat' => $this->data_notify['sertifikat'],
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
