<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Status Booking Kos</title>
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
            background: linear-gradient(135deg, #991B1B 0%, #7F1D1D 100%);
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
            color: #FECACA;
            font-weight: 600;
        }
        .email-body {
            padding: 28px 24px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #7F1D1D;
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
            background-color: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }
        .reason-box {
            background-color: #FEF2F2;
            border: 1px solid #FECACA;
            border-left: 4px solid #DC2626;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        .reason-title {
            font-size: 13px;
            font-weight: 800;
            color: #991B1B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .reason-text {
            font-size: 14px;
            line-height: 1.6;
            color: #7F1D1D;
            font-weight: 600;
            margin: 0;
        }
        .summary-card {
            background-color: #FFF8F1;
            border: 1px solid #E9DDD2;
            border-radius: 12px;
            padding: 16px 18px;
            margin-bottom: 22px;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #3B2314;
            border-bottom: 1px solid #E9DDD2;
            padding-bottom: 6px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
            font-size: 13px;
        }
        .info-cell-label {
            display: table-cell;
            color: #7B6759;
            padding-bottom: 4px;
        }
        .info-cell-value {
            display: table-cell;
            font-weight: 700;
            text-align: right;
            color: #3B2314;
            padding-bottom: 4px;
        }
        .solution-box {
            background-color: #F8FAFC;
            border-left: 4px solid #00A896;
            padding: 14px 16px;
            border-radius: 6px;
            font-size: 13px;
            line-height: 1.6;
            color: #334155;
            margin-bottom: 24px;
        }
        .btn-wa {
            display: inline-block;
            background-color: #25D366;
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
            <h1>Pemberitahuan Status Booking</h1>
            <p>{{ config('app.name', 'Sinar Citra Lestari') }}</p>
        </div>

        <div class="email-body">
            <div class="greeting">Halo, {{ $tamu->name }}</div>

            <div>
                <span class="status-pill">❌ Status: Permohonan Tidak Disetujui</span>
            </div>

            <p class="message">
                Terima kasih atas minat Anda untuk menyewa kamar di kos kami. Melalui email ini, kami ingin memberitahukan bahwa permohonan reservasi Anda dengan kode booking: <strong style="color: #991B1B;">#BOOK-{{ str_pad($tamu->id, 5, '0', STR_PAD_LEFT) }}</strong> belum dapat kami setujui.
            </p>

            <!-- KOTAK ALASAN PENOLAKAN -->
            <div class="reason-box">
                <div class="reason-title">Alasan Penolakan dari Admin:</div>
                <p class="reason-text">"{{ $reason }}"</p>
            </div>

            <div class="summary-card">
                <h3>Rincian Pengajuan</h3>
                <div class="info-row">
                    <span class="info-cell-label">Nama Kos:</span>
                    <span class="info-cell-value">{{ $tamu->productKamarKosan->productKosan->title ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-cell-label">Nomor / Unit Kamar:</span>
                    <span class="info-cell-value">{{ $tamu->productKamarKosan->room ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-cell-label">Rencana Check-In:</span>
                    <span class="info-cell-value">{{ \Carbon\Carbon::parse($tamu->start_date)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-cell-label">Metode Pembayaran:</span>
                    <span class="info-cell-value">{{ strtoupper($tamu->payment_method) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-cell-label">Nominal:</span>
                    <span class="info-cell-value">Rp {{ number_format($tamu->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="solution-box">
                💡 <strong>Informasi Pengembalian Dana (Refund) & Konfirmasi:</strong><br>
                Jika Anda telah melakukan transfer dana atau ingin mengunggah bukti pembayaran yang valid, silakan hubungi tim pengelola kami melalui WhatsApp dengan melampirkan kode booking di atas agar tim kami dapat segera membantu proses <strong>pengembalian dana (refund) 100%</strong> atau pemesanan ulang unit yang tersedia.
            </div>

            @php
                $waMsg = "Halo Admin Sinar Citra Lestari, saya " . $tamu->name . " (Kode Booking #" . str_pad($tamu->id, 5, '0', STR_PAD_LEFT) . " di " . ($tamu->productKamarKosan->productKosan->title ?? 'Kos') . ") yang pengajuannya ditolak dengan alasan: '" . $reason . "'. Mohon bantuan untuk tindak lanjut / proses pengembalian dana (refund).";
                $waLink = "https://wa.me/" . $waNumber . "?text=" . urlencode($waMsg);
            @endphp

            <div style="text-align: center; margin-top: 15px; margin-bottom: 25px;">
                <a href="{{ $waLink }}" target="_blank" class="btn-wa">
                    💬 Hubungi Admin via WhatsApp untuk Refund / Bantuan
                </a>
            </div>

            <p class="message" style="margin-bottom: 0; font-size: 13px; color: #8F7765;">
                Kami memohon maaf atas ketidaknyamanan ini dan berharap dapat melayani Anda di kesempatan berikutnya.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'Sinar Citra Lestari') }}. Seluruh hak cipta dilindungi.<br>
            Email ini dikirim otomatis oleh sistem informasi reservasi kos.
        </div>
    </div>

</body>
</html>
