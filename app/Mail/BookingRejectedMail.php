<?php

namespace App\Mail;

use App\Models\Tamu;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tamu;
    public $reason;
    public $waNumber;

    /**
     * Create a new message instance (Pemberitahuan Penolakan Booking, tanpa PDF).
     */
    public function __construct(Tamu $tamu, ?string $reason = null, ?string $waNumber = null)
    {
        $this->tamu = $tamu;
        $this->reason = $reason ?? 'Pembayaran tidak memenuhi ketentuan yang berlaku atau unit kamar telah terisi.';
        $this->waNumber = $waNumber ?? '6281234567890';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan Status Booking Kos #' . str_pad($this->tamu->id, 5, '0', STR_PAD_LEFT) . ' - Sinar Citra Lestari',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_rejected',
            with: [
                'tamu' => $this->tamu,
                'reason' => $this->reason,
                'waNumber' => $this->waNumber,
            ],
        );
    }

    /**
     * Tanpa lampiran PDF saat ditolak.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
