<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima Booking Kos</title>
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
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #E9DDD2;
        }
        .email-header {
            background: linear-gradient(135deg, #3B2314 0%, #54331d 100%);
            border-bottom: 3px solid #F3A833;
            color: #ffffff;
            padding: 28px 24px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .email-header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: #F3A833;
            font-weight: 600;
        }
        .email-body {
            padding: 28px 24px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #3B2314;
            margin-bottom: 12px;
        }
        .message {
            font-size: 14px;
            line-height: 1.6;
            color: #554438;
            margin-bottom: 20px;
        }
        .status-pill {
            display: inline-block;
            background-color: #FEF3C7;
            color: #92400E;
            border: 1px solid #FCD34D;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }
        .summary-card {
            background-color: #FFF8F1;
            border: 1px solid #E9DDD2;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 20px;
        }
        .summary-card h3 {
            margin: 0 0 12px 0;
            font-size: 14px;
            color: #3B2314;
            border-bottom: 1px solid #E9DDD2;
            padding-bottom: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            font-size: 13px;
        }
        .info-cell-label {
            display: table-cell;
            color: #7B6759;
            padding-bottom: 6px;
        }
        .info-cell-value {
            display: table-cell;
            font-weight: 700;
            text-align: right;
            color: #3B2314;
            padding-bottom: 6px;
        }
        .notice-box {
            background-color: #F8FAFC;
            border-left: 4px solid #F3A833;
            padding: 14px 16px;
            border-radius: 6px;
            font-size: 13px;
            line-height: 1.5;
            color: #475569;
            margin-bottom: 24px;
        }
        .btn-action {
            display: inline-block;
            background-color: #E60049;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 800;
            text-align: center;
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
            <h1>Tanda Terima Permohonan Booking</h1>
            <p>{{ config('app.name', 'Sinar Citra Lestari') }}</p>
        </div>

        <div class="email-body">
            <div class="greeting">Halo, {{ $tamu->name }}! 👋</div>

            <div>
                <span class="status-pill">⏳ Menunggu Verifikasi Pembayaran</span>
            </div>

            <p class="message">
                Terima kasih telah mengajukan pemesanan kamar kos melalui website kami. Reservasi Anda telah tercatat di sistem kami dengan kode booking: <strong style="color: #E60049;">#BOOK-{{ str_pad($tamu->id, 5, '0', STR_PAD_LEFT) }}</strong>.
            </p>

            <div class="summary-card">
                <h3>Rincian Reservasi</h3>
                <div class="info-row">
                    <span class="info-cell-label">Nama Kos:</span>
                    <span class="info-cell-value">{{ $tamu->productKamarKosan->productKosan->title ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-cell-label">Nomor / Unit Kamar:</span>
                    <span class="info-cell-value">{{ $tamu->productKamarKosan->room ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-cell-label">Tanggal Mulai Masuk (Check-In):</span>
                    <span class="info-cell-value">{{ \Carbon\Carbon::parse($tamu->start_date)->translatedFormat('d F Y') }} ({{ $tamu->start_time }})</span>
                </div>
                <div class="info-row">
                    <span class="info-cell-label">Tanggal Selesai Sewa:</span>
                    <span class="info-cell-value">{{ \Carbon\Carbon::parse($tamu->end_date)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-cell-label">Metode Pembayaran:</span>
                    <span class="info-cell-value">{{ $tamu->payment_method_label }}</span>
                </div>
                <div class="info-row" style="border-top: 1px solid #E9DDD2; padding-top: 8px; margin-top: 4px;">
                    <span class="info-cell-label" style="font-weight: 800; color: #3B2314;">Total Biaya Sewa:</span>
                    <span class="info-cell-value" style="font-size: 16px; color: #E60049;">Rp {{ number_format($tamu->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="notice-box">
                ℹ️ <strong>Langkah Berikutnya:</strong><br>
                Tim pengelola kami sedang memverifikasi bukti pembayaran yang Anda kirimkan. Proses verifikasi membutuhkan waktu maksimal <strong>1x24 jam</strong>.<br><br>
                Begitu pembayaran Anda disetujui, kami akan mengirimkan <strong>Email Konfirmasi Resmi</strong> yang disertai <strong>Lampiran File PDF (Kwitansi Pelunasan & Bukti Reservasi Sah)</strong>.
            </div>

            <div style="text-align: center; margin-top: 10px; margin-bottom: 25px;">
                <a href="{{ route('booking.success', $tamu->access_token) }}" target="_blank" class="btn-action">
                    Cek Status Reservasi Anda
                </a>
            </div>

            <p class="message" style="margin-bottom: 0; font-size: 13px; color: #8F7765;">
                Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi tim pengelola kami dengan menyebutkan kode booking Anda.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'Sinar Citra Lestari') }}. Seluruh hak cipta dilindungi.<br>
            Email ini dikirim otomatis sebagai konfirmasi permohonan reservasi awal.
        </div>
    </div>

</body>
</html>
