<?php

namespace App\Mail;

use App\Models\Tamu;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tamu;

    /**
     * Create a new message instance (Tanda terima awal, menunggu verifikasi admin).
     */
    public function __construct(Tamu $tamu)
    {
        $this->tamu = $tamu;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Menunggu Verifikasi] Booking Kos #' . str_pad($this->tamu->id, 5, '0', STR_PAD_LEFT) . ' - Sinar Citra Lestari',
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
     * Lampiran: Kosong pada tahap awal (PDF hanya dikirim saat disetujui).
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
