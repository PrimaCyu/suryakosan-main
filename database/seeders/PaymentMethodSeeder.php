<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultMethods = [
            [
                'code'           => 'qris',
                'name'           => 'QRIS (Semua Bank & E-Wallet: BCA, Mandiri, Dana, GoPay)',
                'type'           => 'qris',
                'bank_code'      => 'QRIS',
                'account_number' => null,
                'account_holder' => 'Sinar Citra Lestari',
                'qr_image'       => null,
                'instructions'   => 'Buka aplikasi m-Banking atau e-Wallet favorit Anda, pilih menu QRIS / Scan, lalu selesaikan pembayaran sesuai total tagihan.',
                'is_active'      => true,
                'sort_order'     => 1,
            ],
            [
                'code'           => 'bca',
                'name'           => 'Bank Central Asia (BCA)',
                'type'           => 'bank',
                'bank_code'      => 'BCA',
                'account_number' => '1234567890',
                'account_holder' => 'PT Sinar Citra Lestari',
                'qr_image'       => null,
                'instructions'   => 'Transfer tepat sesuai nominal tagihan ke rekening BCA pengelola kos.',
                'is_active'      => true,
                'sort_order'     => 2,
            ],
            [
                'code'           => 'mandiri',
                'name'           => 'Bank Mandiri',
                'type'           => 'bank',
                'bank_code'      => 'MDR',
                'account_number' => '0987654321',
                'account_holder' => 'PT Sinar Citra Lestari',
                'qr_image'       => null,
                'instructions'   => 'Transfer tepat sesuai nominal tagihan ke rekening Mandiri pengelola kos.',
                'is_active'      => true,
                'sort_order'     => 3,
            ],
            [
                'code'           => 'bni',
                'name'           => 'Bank Negara Indonesia (BNI)',
                'type'           => 'bank',
                'bank_code'      => 'BNI',
                'account_number' => '1111111111',
                'account_holder' => 'PT Sinar Citra Lestari',
                'qr_image'       => null,
                'instructions'   => 'Transfer tepat sesuai nominal tagihan ke rekening BNI pengelola kos.',
                'is_active'      => true,
                'sort_order'     => 4,
            ],
            [
                'code'           => 'cash',
                'name'           => 'Bayar di Tempat (COD / Tunai)',
                'type'           => 'cash',
                'bank_code'      => 'COD',
                'account_number' => null,
                'account_holder' => null,
                'qr_image'       => null,
                'instructions'   => 'Pembayaran dilakukan secara langsung kepada pengelola kos saat serah terima kunci di lokasi. Pastikan nomor kontak aktif untuk konfirmasi.',
                'is_active'      => true,
                'sort_order'     => 5,
            ],
        ];

        foreach ($defaultMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                $method
            );
        }
    }
}

