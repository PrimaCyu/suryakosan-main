<?php

namespace App\Mail;

use App\Models\Tamu;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tamu;
    public $pdfOutput;

    /**
     * Create a new message instance.
     */
    public function __construct(Tamu $tamu, string $pdfOutput)
    {
        $this->tamu = $tamu;
        $this->pdfOutput = $pdfOutput;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Booking Kos #' . str_pad($this->tamu->id, 5, '0', STR_PAD_LEFT),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_confirmation',
            with: [
                'tamu' => $this->tamu,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $filename = 'Bukti-Booking-' . str_pad($this->tamu->id, 5, '0', STR_PAD_LEFT) . '.pdf';

        return [
            Attachment::fromData(fn () => $this->pdfOutput, $filename)
                ->withMime('application/pdf'),
        ];
    }
}
