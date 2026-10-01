<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking & Pembayaran Disetujui</title>
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
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
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
            color: #A7F3D0;
            font-weight: 600;
        }
        .email-body {
            padding: 28px 24px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #065F46;
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
            background-color: #D1FAE5;
            color: #065F46;
            border: 1px solid #6EE7B7;
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
        .attachment-box {
            background-color: #ECFDF5;
            border-left: 4px solid #10B981;
            padding: 14px 16px;
            border-radius: 6px;
            font-size: 13px;
            line-height: 1.6;
            color: #065F46;
            margin-bottom: 22px;
        }
        .checkin-guide {
            background-color: #FFFBEB;
            border-left: 4px solid #F59E0B;
            padding: 14px 16px;
            border-radius: 6px;
            font-size: 13px;
            line-height: 1.6;
            color: #92400E;
            margin-bottom: 24px;
        }
        .btn-action {
            display: inline-block;
            background-color: #059669;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 800;
            text-align: center;
        }
        .btn-wa {
            display: inline-block;
            background-color: #25D366;
            color: #ffffff !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            margin-top: 6px;
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
            <h1>Pembayaran & Reservasi Disetujui!</h1>
            <p>{{ config('app.name', 'Sinar Citra Lestari') }}</p>
        </div>

        <div class="email-body">
            <div class="greeting">Selamat, {{ $tamu->name }}! 🎉</div>

            <div>
                <span class="status-pill">✅ Status: Pembayaran Lunas & Terkonfirmasi</span>
            </div>

            <p class="message">
                Kabar gembira! Pembayaran untuk reservasi kamar kos Anda dengan kode booking: <strong style="color: #059669;">#BOOK-{{ str_pad($tamu->id, 5, '0', STR_PAD_LEFT) }}</strong> telah berhasil diverifikasi oleh admin. Unit kamar telah resmi diamankan atas nama Anda.
            </p>

            <div class="summary-card">
                <h3>Rincian Reservasi Sah</h3>
                <div class="info-row">
                    <span class="info-cell-label">Nama Kos:</span>
                    <span class="info-cell-value">{{ $tamu->productKamarKosan->productKosan->title ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-cell-label">Nomor / Unit Kamar:</span>
                    <span class="info-cell-value">{{ $tamu->productKamarKosan->room ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-cell-label">Jadwal Check-In:</span>
                    <span class="info-cell-value">{{ \Carbon\Carbon::parse($tamu->start_date)->translatedFormat('d F Y') }} ({{ $tamu->start_time }})</span>
                </div>
                <div class="info-row">
                    <span class="info-cell-label">Jadwal Check-Out:</span>
                    <span class="info-cell-value">{{ \Carbon\Carbon::parse($tamu->end_date)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-cell-label">Metode Pembayaran:</span>
                    <span class="info-cell-value">{{ strtoupper($tamu->payment_method) }}</span>
                </div>
                <div class="info-row" style="border-top: 1px solid #E9DDD2; padding-top: 8px; margin-top: 4px;">
                    <span class="info-cell-label" style="font-weight: 800; color: #3B2314;">Total Lunas:</span>
                    <span class="info-cell-value" style="font-size: 16px; color: #059669;">Rp {{ number_format($tamu->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="attachment-box">
                📎 <strong>Dokumen Terlampir (PDF):</strong><br>
                File PDF <strong>Kwitansi Pelunasan & Bukti Reservasi Sah</strong> telah kami lampirkan di bagian bawah email ini. Anda dapat mengunduh dan menyimpannya di ponsel Anda.
            </div>

            <div class="checkin-guide">
                🔑 <strong>Panduan Serah Terima Kunci:</strong><br>
                Saat tiba di lokasi kos pada tanggal check-in, tunjukkan bukti booking (atau file PDF terlampir) kepada penjaga kos atau pengelola untuk serah terima kunci kamar dan pengecekan fasilitas bersama.
            </div>

            <div style="text-align: center; margin-top: 10px; margin-bottom: 20px;">
                <a href="{{ route('booking.success', $tamu->access_token) }}" target="_blank" class="btn-action">
                    Buka Halaman Reservasi Anda
                </a>
            </div>

            @php
                $waMsg = "Halo Pengelola Sinar Citra Lestari, saya " . $tamu->name . " (Booking #" . str_pad($tamu->id, 5, '0', STR_PAD_LEFT) . " di " . ($tamu->productKamarKosan->productKosan->title ?? 'Kos') . " - " . ($tamu->productKamarKosan->room ?? '') . ") yang pembayarannya telah disetujui. Saya ingin berkoordinasi mengenai jadwal kedatangan / serah terima kunci.";
                $waLink = "https://wa.me/" . $waNumber . "?text=" . urlencode($waMsg);
            @endphp

            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; text-align: center; margin-bottom: 20px;">
                <p style="margin: 0 0 8px 0; font-size: 13px; color: #475569; font-weight: 600;">
                    Butuh koordinasi langsung dengan pengelola kos?
                </p>
                <a href="{{ $waLink }}" target="_blank" class="btn-wa">
                    💬 Hubungi Pengelola via WhatsApp
                </a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'Sinar Citra Lestari') }}. Seluruh hak cipta dilindungi.<br>
            Email ini merupakan bukti konfirmasi resmi atas pembayaran dan reservasi kos.
        </div>
    </div>

</body>
</html>
