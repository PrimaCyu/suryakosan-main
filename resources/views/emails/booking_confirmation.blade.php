<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Booking Kos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
            color: #334155;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: #ffffff;
            padding: 30px 25px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .email-header p {
            margin: 8px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px 25px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 15px;
        }
        .message {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 25px;
        }
        .summary-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 18px;
            margin-bottom: 25px;
        }
        .summary-card h3 {
            margin: 0 0 12px 0;
            font-size: 15px;
            color: #1e40af;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }
        .summary-label {
            color: #64748b;
        }
        .summary-value {
            font-weight: 600;
            color: #1e293b;
        }
        .attachment-notice {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 13px;
            color: #1e40af;
            margin-bottom: 25px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    <div class="email-container">
        <div class="email-header">
            <h1>Konfirmasi Reservasi Kos</h1>
            <p>{{ config('app.name', 'Sinar Citra Lestari') }}</p>
        </div>

        <div class="email-body">
            <div class="greeting">Halo, {{ $tamu->name }}! 👋</div>
            
            <p class="message">
                Terima kasih telah melakukan pemesanan kamar kos melalui website kami. Reservasi Anda telah berhasil tercatat di sistem kami dengan kode booking: <strong>#BOOK-{{ str_pad($tamu->id, 5, '0', STR_PAD_LEFT) }}</strong>.
            </p>

            <div class="summary-card">
                <h3>Ringkasan Reservasi</h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Nama Kos:</td>
                        <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #1e293b;">
                            {{ $tamu->productKamarKosan->productKosan->title ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Nomor / Tipe Kamar:</td>
                        <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #1e293b;">
                            {{ $tamu->productKamarKosan->room ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Tanggal Check-In:</td>
                        <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #1e293b;">
                            {{ \Carbon\Carbon::parse($tamu->start_date)->translatedFormat('d F Y') }} ({{ $tamu->start_time }})
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Tanggal Check-Out:</td>
                        <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #1e293b;">
                            {{ \Carbon\Carbon::parse($tamu->end_date)->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Metode Pembayaran:</td>
                        <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #1e293b;">
                            {{ strtoupper($tamu->payment_method) }}
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #e2e8f0;">
                        <td style="padding: 8px 0 0 0; color: #1e40af; font-weight: bold;">Total Bayar:</td>
                        <td style="padding: 8px 0 0 0; font-weight: bold; text-align: right; color: #1d4ed8; font-size: 15px;">
                            Rp {{ number_format($tamu->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="attachment-notice">
                📄 <strong>Lampiran PDF:</strong> Bukti booking / Invoice resmi telah dilampirkan pada email ini dalam format PDF. Anda dapat mengunduh dan menyimpannya.
            </div>

            <p class="message" style="margin-bottom: 0;">
                Jika Anda memiliki pertanyaan mengenai reservasi ini, silakan hubungi tim kami melalui kontak yang tertera pada situs web.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'Sinar Citra Lestari') }}. All rights reserved.<br>
            Email ini dikirim secara otomatis oleh sistem reservasi kos.
        </div>
    </div>

</body>
</html>
