<p align="center">
  <img src="public/scl.png" width="100" height="100" alt="Sinar Citra Lestari Logo">
</p>

<h1 align="center">Sinar Citra Lestari (SCL)</h1>
<h3 align="center">Sistem Informasi Manajemen & Reservasi Hunian Kos Modern</h3>

<p align="center">
  Platform manajemen properti dan reservasi hunian kos berbasis web yang dirancang khusus untuk mempermudah calon penghuni dalam mencari dan menyewa kamar, sekaligus menyediakan kontrol operasional, audit trail, serta laporan keuangan terpadu bagi pemilik dan pengelola kos.
</p>

---

## 📌 Daftar Isi
1. [Tentang Website](#-tentang-website)
2. [Fitur Utama Sistem](#-fitur-utama-sistem)
   - [Portal Publik (Frontend Calon Penyewa)](#1-portal-publik-frontend-calon-penyewa)
   - [Portal Manajemen (Backend Admin & Superadmin)](#2-portal-manajemen-backend-admin--superadmin)
   - [Sistem Notifikasi Email & PDF Otomatis](#3-sistem-notifikasi-email--pdf-otomatis)
   - [Keamanan & Pencegahan Bug Kritis](#4-keamanan--pencegahan-bug-kritis)
3. [Alur Kerja Sistem (Workflow)](#-alur-kerja-sistem-workflow)
   - [Alur Calon Penyewa (Customer Journey)](#a-alur-calon-penyewa-customer-journey)
   - [Alur Verifikasi Pembayaran & Approval Admin](#b-alur-verifikasi-pembayaran--approval-admin)
4. [Teknologi yang Digunakan (Tech Stack)](#-teknologi-yang-digunakan-tech-stack)
5. [Struktur Direktori Utama](#-struktur-direktori-utama)
6. [Panduan Instalasi & Menjalankan Sistem](#-panduan-instalasi--menjalankan-sistem)

---

## 🏢 Tentang Website

**Sinar Citra Lestari (SCL)** adalah platform manajemen hunian sewa kos terintegrasi di Bali. Aplikasi ini menghubungkan calon penghuni kos dengan pengelola secara langsung tanpa perantara calo. 

Website ini dirancang untuk menjawab dua kebutuhan utama:
1. **Bagi Calon Penghuni**: Memberikan transparansi informasi mengenai ketersediaan kamar secara *real-time*, rincian tarif sewa (jam, hari, minggu, bulan, tahun), fasilitas lengkap, lokasi interaktif, hingga kemudahan reservasi online yang aman dan terverifikasi.
2. **Bagi Pengelola / Pemilik Kos**: Memberikan otomasi operasional untuk memantau tingkat okupansi (*occupancy rate*), verifikasi bukti pembayaran, perpanjangan sewa (*tenancy renewal*), pencegahan *double booking*, serta jejak audit (*audit trail*) agar setiap transaksi tercatat dengan akuntabel.

---

## ⚡ Fitur Utama Sistem

### 1. Portal Publik (Frontend Calon Penyewa)
* **Katalog Properti & Filter Fleksibel**:
  - Filter pencarian berdasarkan wilayah (Denpasar, Badung, Gianyar, Tabanan, dll.), rentang tarif sewa, nama properti, maupun fasilitas spesifik.
  - Penanda status hunian *real-time* (**Siap Huni** vs **Terisi Penuh**).
* **Halaman Detail Kos yang Kaya Informasi**:
  - **Galeri Bento Grid**: Tampilan galeri foto properti modern yang responsif dan dilengkapi mode *Lightbox Layar Penuh* (100% tanpa crop).
  - **Peta Interaktif (Leaflet.js & OpenStreetMap)**: Peta lokasi dengan fitur *layer switcher* (Street View & Citra Satelit Esri), tombol rute Google Maps instan, serta proteksi *gesture mobile* (anti scroll-trap saat pengguna menggulir layar di HP).
  - **Tampilan Unit Kamar Cerdas**: Rincian fasilitas kamar, opsi sewa tahunan hemat, dan tombol dinamis: **Pesan Langsung** jika kamar kosong, atau **Waiting List via WhatsApp** jika kamar sedang penuh.
  - **Dukungan Format Deskripsi Fleksibel**: Admin dapat memasukkan tata tertib dan aturan kos secara bebas melalui *rich text* tanpa perlu merombak kode program.
  - **FAQ Interaktif**: Pertanyaan umum seputar survei lokasi, metode pembayaran, dan tata cara check-in.
* **Halaman Detail Kamar**:
  - Tampilan dua tingkat fasilitas (*Two-Tier Facilities*): Membedakan fasilitas khusus kamar tidur dengan fasilitas bersama gedung kos.
  - Rekomendasi unit kamar lain di kos yang sama (*Sibling Rooms Cross-Selling*).
  - Sidebar kalkulator estimasi biaya sewa.
* **Formulir Reservasi Aman**:
  - Pemilihan rentang tanggal masuk (*check-in*) dengan Flatpickr Calendar.
  - Pilihan durasi fleksibel (kombinasi jam, hari, minggu, bulan, atau tahun).
  - Pilihan metode bayar: Transfer Bank (BCA, Mandiri, BNI), QRIS, maupun Tunai (Cash).
  - Pengunggahan bukti transfer dengan validasi format dan ukuran file.
* **Halaman Bukti Booking Mandiri**:
  - Setiap pemesanan memiliki tautan akses unik (*Access Token*) yang aman.
  - Penghuni dapat melihat status reservasi kapan saja dan mengunduh ulang bukti booking/invoice PDF.

---

### 2. Portal Manajemen (Backend Admin & Superadmin)
* **Dual-Role Authorization System**:
  - **Superadmin**: Memiliki akses penuh lintas cabang, manajemen akun admin, pembuatan/penghapusan properti kos, serta audit seluruh transaksi.
  - **Admin Cabang**: Dibatasi hanya untuk mengelola properti kosan tertentu yang ditugaskan oleh Superadmin.
* **Dashboard Metrik & KPI Operasional**:
  - Informasi total properti, total kapasitas kamar, unit terisi, unit siap huni, dan persentase okupansi (*Occupancy Rate*).
  - Monitoring omset pendapatan bulanan dan total akumulasi transaksi yang disetujui.
  - **Kotak Peringatan Darurat (Urgent Action Box)**:
    1. *Permintaan Booking Baru (Pending)*: Antrean verifikasi bukti transfer pembayaran.
    2. *Sewa Jatuh Tempo (Expiring / Overdue)*: Notifikasi otomatis untuk sewa penghuni yang akan habis dalam H-7 hingga H+30 hari.
* **Manajemen Unit Kamar & Sub-Resource**:
  - Tambah, ubah, dan hapus unit kamar kos.
  - Pengaturan multi-foto kamar dengan kompresi dan hapus otomatis berkas fisik dari storage saat data dihapus.
  - Pengaturan tarif harga bertingkat (Bulanan, Tahunan) dan diskon khusus.
* **Audit Trail Pengelolaan Booking**:
  - Setiap persetujuan (*approval*) mencatat siapa admin yang memproses (`processed_by`) dan waktu pemrosesan (`processed_at`).
  - Setiap penolakan (*rejection*) mewajibkan alasan penolakan (`rejection_reason`) yang dapat dipilih dari *preset* cepat atau diketik secara kustom.
* **Perpanjangan Masa Sewa (Tenancy Renewal)**:
  - Admin dapat memperpanjang masa sewa penghuni aktif langsung dari sistem tanpa mengharuskan penghuni mengisi ulang form dari awal.

---

### 3. Sistem Notifikasi Email & PDF Otomatis

Sistem menerapkan arsitektur notifikasi email berjenjang (*3-stage email workflow*) yang efisien:

1. **Email 1: Tanda Terima Antrean (Tanpa Lampiran PDF)**:
   - Terkirim seketika setelah penyewa menekan tombol *Booking*.
   - Memberitahukan bahwa permohonan reservasi telah masuk sistem dan sedang dalam proses verifikasi admin dalam waktu 1x24 jam.
   - *Tidak melampirkan PDF* untuk mencegah penyalahgunaan invoice yang belum sah pembayarannya.
2. **Email 2A: Pembayaran Disetujui / Approved (DENGAN Lampiran PDF Kwitansi Lunas)**:
   - Terkirim otomatis saat admin menekan tombol **Setujui** di dashboard.
   - Melampirkan berkas resmi `Bukti-Reservasi-Lunas-Kos-#{ID}.pdf` berstatus **DISETUJUI / LUNAS** yang dapat dicetak untuk serah terima kunci kamar.
   - Menyediakan panduan *check-in* dan tautan langsung ke WhatsApp pengelola kos setempat.
3. **Email 2B: Pembayaran Ditolak / Rejected (Tanpa Lampiran PDF)**:
   - Terkirim otomatis saat admin menekan tombol **Tolak** di dashboard.
   - Menampilkan alasan penolakan secara transparan dari admin.
   - Menyediakan tombol bantuan langsung ke WhatsApp pengelola untuk proses pengembalian dana (*refund 100%*) atau klarifikasi bukti transfer.

---

### 4. Keamanan & Pencegahan Bug Kritis
* **Pencegahan Double Booking (Race Condition Prevention)**:
  - Validasi bentrok tanggal dan insert data dilakukan di dalam `DB::transaction` dengan **Row-Level Lock** (`lockForUpdate()`).
  - Dua pengguna yang menekan tombol pesan pada detik yang persis sama tidak akan mendapatkan unit kamar yang sama.
* **Proteksi IDOR (Insecure Direct Object Reference)**:
  - Seluruh operasi update dan delete pada sub-resource kamar (fasilitas, foto, harga, dan tamu) diikat secara ketat ke `product_kamar_kosan_id` dan `product_kosan_id` milik admin terkait.
* **Kalkulasi Harga di Sisi Server (Anti Price Tampering)**:
  - Total biaya sewa dihitung ulang secara mutlak oleh backend berdasarkan durasi dan tarif di database untuk mencegah manipulasi harga dari *inspect element* browser.
* **Proteksi Brute-Force & Flooding**:
  - Endpoint login dilindungi *Rate Limiter* (`throttle:5,1`).
  - Endpoint booking dilindungi *Rate Limiter* (`throttle:10,1`).
* **Pencegahan SQL Wildcard Injection**:
  - Filter pencarian publik menggunakan fungsi *escapeLike* untuk menetralkan karakter `%` dan `_`.

---

## 🔄 Alur Kerja Sistem (Workflow)

### A. Alur Calon Penyewa (Customer Journey)

```
[Pengunjung Masuk]
       │
       ▼
[Katalog / Detail Kosan] ───► Melihat foto, lokasi peta, fasilitas & unit kamar
       │
       ▼
[Pilih Unit Kamar] ─────────► [Cek Status Ketersediaan]
                                     │
                 ┌───────────────────┴───────────────────┐
                 ▼                                       ▼
          [Status: Siap Huni]                     [Status: Terisi]
                 │                                       │
                 ▼                                       ▼
       [Klik "Pesan Langsung"]                  [Klik "Waiting List WA"]
                 │                              (Terhubung ke WhatsApp Pengelola)
                 ▼
       [Isi Form Booking]
       - Nama, No. WhatsApp, Email
       - Tanggal & Jam Check-In
       - Pilih Durasi (Bulan / Tahun)
       - Pilih Metode Pembayaran & Upload Bukti Transfer
                 │
                 ▼
       [Submit Booking]
                 │
                 ├───────────────────────────────────────┐
                 ▼                                       ▼
       [Halaman Sukses Booking]            [Email 1 Terkirim Otomatis]
       (Mendapatkan Access Token &          "Tanda Terima Permohonan Booking"
        Ringkasan Kode Booking)             (Status: Menunggu Verifikasi Admin)
```

---

### B. Alur Verifikasi Pembayaran & Approval Admin

```
[Admin / Superadmin Login]
            │
            ▼
[Dashboard / Menu Permintaan Booking]
            │
            ▼
[Cek Rincian Tamu & Bukti Transfer]
            │
      ┌─────┴────────────────────────────────────────────────┐
      ▼                                                      ▼
[KLIK SETUJUI (APPROVE)]                              [KLIK TOLAK (REJECT)]
      │                                                      │
      ├─ Status diubah menjadi 'approved'                    ├─ Admin pilih / ketik alasan penolakan
      ├─ Kunci kamar tersinkron terisi                       ├─ Status diubah menjadi 'reject'
      ├─ Audit trail tercatat                                ├─ Audit trail tercatat
      ├─ Generate PDF Invoice Lunas                          │
      │                                                      ▼
      ▼                                            [Email 2B Terkirim Otomatis]
[Email 2A Terkirim Otomatis]                        - Menampilkan Alasan Penolakan
- Terlampir PDF Kwitansi Pelunasan Resmi            - Petunjuk Refund / Klarifikasi
- Informasi Serah Terima Kunci                      - Tombol Bantuan WhatsApp Admin
- Tombol WhatsApp Pengelola Kos
```

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

### Backend & Database
* **Framework**: Laravel 11.x
* **Bahasa**: PHP 8.2 / 8.3+
* **Database**: MySQL / MariaDB (InnoDB engine)
* **Dokumen Generator**: `barryvdh/laravel-dompdf` (DomPDF Engine)
* **Email Service**: Laravel Mailable dengan SMTP (Gmail TLS)

### Frontend & Styling
* **Template Engine**: Laravel Blade
* **CSS Framework**: Tailwind CSS v3 / v4
* **Ikon**: Font Awesome 6 Pro / Free CDN
* **Peta Digital**: Leaflet.js 1.9.4 dengan OpenStreetMap & Esri World Imagery
* **Datepicker**: Flatpickr (dengan tema kustom SCL)
* **Bundler & Tooling**: Vite 7.x

---

## 📂 Struktur Direktori Utama

```
kosan-main/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/              # Controller dashboard, kosan, kamar, booking, profile
│   │       │   ├── AdminManagementController.php
│   │       │   ├── BookingAdminController.php
│   │       │   ├── DashboardController.php
│   │       │   ├── KamarKosanController.php
│   │       │   └── KosanController.php
│   │       ├── Auth/               # Controller autentikasi (login/logout)
│   │       ├── Frontend/           # Controller beranda, katalog kos, detail, artikel
│   │       └── TamuBookingController.php # Pemrosesan booking & download invoice
│   ├── Mail/                       # Berkas Mailable (Approval, Rejection, Confirmation)
│   │   ├── BookingApprovedMail.php
│   │   ├── BookingConfirmationMail.php
│   │   └── BookingRejectedMail.php
│   ├── Models/                     # Eloquent Models (ProductKosan, Tamu, PriceKamar, dll.)
│   └── Service/
│       └── ProcessBookingDate.php  # Service kalkulasi jadwal & harga sewa
├── database/
│   └── migrations/                 # Skema tabel database (tamus, product_kosans, dll.)
├── resources/
│   ├── views/
│   │   ├── backend/                # Tampilan Dashboard & Admin Panel
│   │   ├── emails/                 # Template email Blade (Approved, Rejected, Confirmation)
│   │   ├── frontend/               # Tampilan halaman publik (Home, Detail Kos, Detail Kamar)
│   │   └── pdf/                    # Template bukti booking & kwitansi PDF
└── routes/
    └── web.php                     # Rute publik dan rute terproteksi admin
```

---

## 🚀 Panduan Instalasi & Menjalankan Sistem

### 1. Kloning Repository & Masuk ke Folder Proyek
```bash
git clone https://github.com/PrimaCyu/suryakosan-main.git
cd suryakosan-main
```

### 2. Instal Dependensi Backend (Composer)
```bash
composer install
```

### 3. Instal Dependensi Frontend (NPM)
```bash
npm install
npm run build
```

### 4. Konfigurasi Environment (`.env`)
Salin berkas contoh environment:
```bash
cp .env.example .env
```
Buka berkas `.env` dan sesuaikan pengaturan database dan konfigurasi email SMTP:
```env
APP_NAME="Sinar Citra Lestari"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kosan_db
DB_USERNAME=root
DB_PASSWORD=

# Konfigurasi Pengiriman Email (Gmail SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=emailanda@gmail.com
MAIL_PASSWORD=app_password_gmail_anda
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="emailanda@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Generate Application Key & Link Storage
```bash
php artisan key:generate
php artisan storage:link
```

### 6. Migrasi Database & Seeding Data Awal
```bash
php artisan migrate --seed
```

### 7. Jalankan Server Lokal
```bash
php artisan serve
```
Buka browser dan akses aplikasi melalui `http://127.0.0.1:8000`.

---

## 🔒 Lisensi & Hak Cipta
Hak Cipta &copy; 2026 **Sinar Citra Lestari**. Seluruh hak cipta dilindungi undang-undang.
Dilarang keras menyalin atau mendistribusikan ulang kode ini tanpa izin tertulis dari pemilik properti.
