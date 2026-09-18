<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bukti Booking #{{ str_pad($tamu->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            margin: 0;
            padding: 20px;
            font-size: 13px;
            line-height: 1.5;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px solid #F3A833;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo-title {
            font-size: 24px;
            font-weight: bold;
            color: #3B2314;
            margin: 0;
        }
        .subtitle {
            font-size: 12px;
            color: #7B6759;
            margin-top: 4px;
        }
        .invoice-title {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #3B2314;
            margin: 0;
        }
        .invoice-num {
            text-align: right;
            font-size: 13px;
            color: #E60049;
            font-weight: bold;
            margin-top: 4px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .info-col {
            width: 50%;
            vertical-align: top;
        }
        .card {
            background-color: #FFF8F1;
            border: 1px solid #E9DDD2;
            border-radius: 8px;
            padding: 12px 16px;
            margin-right: 10px;
        }
        .card-right {
            margin-right: 0;
            margin-left: 10px;
        }
        .card-header {
            font-size: 12px;
            font-weight: bold;
            color: #3B2314;
            border-bottom: 1px solid #E9DDD2;
            padding-bottom: 6px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-row {
            margin-bottom: 5px;
        }
        .data-label {
            color: #7B6759;
            font-size: 11px;
        }
        .data-val {
            font-weight: 600;
            color: #3B2314;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #b45309;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #047857;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .details-table th {
            background-color: #3B2314;
            color: #ffffff;
            font-weight: bold;
            font-size: 12px;
            text-align: left;
            padding: 10px 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #E9DDD2;
            font-size: 12px;
        }
        .details-table tr:nth-child(even) {
            background-color: #FFF8F1;
        }
        .total-section {
            width: 100%;
            margin-bottom: 30px;
        }
        .total-box {
            float: right;
            width: 260px;
            background-color: #FFF8F1;
            border: 1px solid #E9DDD2;
            border-radius: 8px;
            padding: 12px 16px;
        }
        .total-label {
            font-size: 12px;
            color: #7B6759;
            font-weight: bold;
            text-transform: uppercase;
        }
        .total-amount {
            font-size: 19px;
            color: #E60049;
            font-weight: bold;
            text-align: right;
            margin-top: 4px;
        }
        .clearfix {
            clear: both;
        }
        .note-box {
            background-color: #FFF8F1;
            border-left: 4px solid #F3A833;
            padding: 10px 14px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 12px;
            color: #7B6759;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="vertical-align: top;">
                <div class="logo-title">{{ config('app.name', 'Sinar Citra Lestari') }}</div>
                <div class="subtitle">Hunian Kos Nyaman & Terpercaya</div>
            </td>
            <td style="vertical-align: top; text-align: right;">
                <div class="invoice-title">BUKTI BOOKING</div>
                <div class="invoice-num">#BOOK-{{ str_pad($tamu->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                    Tanggal: {{ \Carbon\Carbon::parse($tamu->created_at ?? now())->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="info-col">
                <div class="card">
                    <div class="card-header">Informasi Penyewa</div>
                    <div class="data-row">
                        <span class="data-label">Nama Lengkap:</span><br>
                        <span class="data-val">{{ $tamu->name }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">No. Telepon / WhatsApp:</span><br>
                        <span class="data-val">{{ $tamu->telp }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Email:</span><br>
                        <span class="data-val">{{ $tamu->email }}</span>
                    </div>
                </div>
            </td>
            <td class="info-col">
                <div class="card card-right">
                    <div class="card-header">Informasi Kos & Kamar</div>
                    <div class="data-row">
                        <span class="data-label">Nama Kos:</span><br>
                        <span class="data-val">{{ $tamu->productKamarKosan->productKosan->title ?? '-' }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Nomor / Tipe Kamar:</span><br>
                        <span class="data-val">{{ $tamu->productKamarKosan->room ?? '-' }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Lokasi:</span><br>
                        <span class="data-val">{{ $tamu->productKamarKosan->productKosan->wilayah ?? '-' }}</span>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table class="details-table">
        <thead>
            <tr>
                <th>Deskripsi Sewa</th>
                <th>Tanggal Check-In</th>
                <th>Tanggal Check-Out</th>
                <th>Metode Bayar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Sewa Kamar {{ $tamu->productKamarKosan->room ?? '' }}</strong><br>
                    <small style="color: #64748b;">{{ $tamu->productKamarKosan->productKosan->title ?? '' }}</small>
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($tamu->start_date)->translatedFormat('d M Y') }}<br>
                    <small style="color: #64748b;">Jam: {{ $tamu->start_time ?? '-' }}</small>
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($tamu->end_date)->translatedFormat('d M Y') }}
                </td>
                <td>
                    <strong>{{ strtoupper($tamu->payment_method) }}</strong>
                </td>
                <td>
                    @if($tamu->status == 'approved')
                        <span class="status-badge status-approved">DISETUJUI</span>
                    @else
                        <span class="status-badge status-pending">PENDING</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-box">
            <div class="total-label">Total Pembayaran:</div>
            <div class="total-amount">Rp {{ number_format($tamu->total_price, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="note-box">
        <strong>📌 Catatan Penting:</strong>
        <ul style="margin: 5px 0 0 15px; padding: 0;">
            <li>Simpan bukti booking ini sebagai bukti reservasi kamar kos Anda.</li>
            <li>Jika status masih <em>PENDING</em>, mohon tunggu konfirmasi persetujuan dari pihak pengelola.</li>
            <li>Untuk informasi dan bantuan lebih lanjut, silakan hubungi admin pengelola kos.</li>
        </ul>
    </div>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh sistem {{ config('app.name', 'Sinar Citra Lestari') }} pada {{ date('d/m/Y H:i:s') }}.<br>
        Terima kasih telah mempercayakan hunian Anda kepada kami!
    </div>

</body>
</html>
