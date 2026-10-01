<?php

namespace App\Mail;

use App\Models\Tamu;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tamu;
    public $pdfOutput;
    public $waNumber;

    /**
     * Create a new message instance (Email Persetujuan Booking & Lampiran PDF Kwitansi).
     */
    public function __construct(Tamu $tamu, string $pdfOutput, ?string $waNumber = null)
    {
        $this->tamu = $tamu;
        $this->pdfOutput = $pdfOutput;
        $this->waNumber = $waNumber ?? '6281234567890';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Terkonfirmasi] Pembayaran Disetujui - Reservasi Kos #' . str_pad($this->tamu->id, 5, '0', STR_PAD_LEFT),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_approved',
            with: [
                'tamu' => $this->tamu,
                'waNumber' => $this->waNumber,
            ],
        );
    }

    /**
     * Get the attachments for the message (Melampirkan Kwitansi & Bukti Reservasi Resmi Lunas).
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $filename = 'Bukti-Reservasi-Lunas-Kos-' . str_pad($this->tamu->id, 5, '0', STR_PAD_LEFT) . '.pdf';

        return [
            Attachment::fromData(fn () => $this->pdfOutput, $filename)
                ->withMime('application/pdf'),
        ];
    }
}
