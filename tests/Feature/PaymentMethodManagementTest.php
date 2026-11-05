<?php

namespace Tests\Feature;

use App\Models\PaymentMethod;
use App\Models\ProductKamarKosan;
use App\Models\ProductKosan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentMethodManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $branchAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');

        $this->superAdmin = User::create([
            'name'     => 'Super Administrator',
            'email'    => 'superadmin@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'super_admin',
        ]);

        $this->branchAdmin = User::create([
            'name'     => 'Branch Admin',
            'email'    => 'branchadmin@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
        ]);
    }

    public function test_super_admin_can_view_payment_methods_page(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.payment-methods.index'));

        $response->assertStatus(200);
        $response->assertSee('Metode Pembayaran');
    }

    public function test_branch_admin_is_forbidden_from_payment_methods(): void
    {
        $response = $this->actingAs($this->branchAdmin)
            ->get(route('admin.payment-methods.index'));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_create_new_bank_payment_method(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.payment-methods.store'), [
                'name'           => 'Bank Rakyat Indonesia (BRI)',
                'type'           => 'bank',
                'bank_code'      => 'BRI',
                'code'           => 'bri',
                'account_number' => '012345678910',
                'account_holder' => 'PT Sinar Citra Lestari',
                'instructions'   => 'Transfer tepat sesuai nominal tagihan ke rekening BRI.',
                'is_active'      => 1,
                'sort_order'     => 1,
            ]);

        $response->assertRedirect(route('admin.payment-methods.index'));
        $this->assertDatabaseHas('payment_methods', [
            'code'           => 'bri',
            'name'           => 'Bank Rakyat Indonesia (BRI)',
            'account_number' => '012345678910',
            'is_active'      => 1,
        ]);
    }

    public function test_super_admin_can_upload_qris_image(): void
    {
        $qrisImage = UploadedFile::fake()->image('qris_official.png', 500, 500);

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.payment-methods.store'), [
                'name'           => 'QRIS Dinamis Semua Bank',
                'type'           => 'qris',
                'bank_code'      => 'QRIS',
                'code'           => 'qris_dinamis',
                'account_holder' => 'Sinar Citra Lestari',
                'qr_image'       => $qrisImage,
                'is_active'      => 1,
            ]);

        $response->assertRedirect(route('admin.payment-methods.index'));

        $pm = PaymentMethod::where('code', 'qris_dinamis')->first();
        $this->assertNotNull($pm);
        $this->assertNotNull($pm->qr_image);
        Storage::disk('public')->assertExists($pm->qr_image);
    }

    public function test_cannot_deactivate_or_delete_the_only_active_method(): void
    {
        // Hanya ada 1 metode aktif
        $method = PaymentMethod::create([
            'name'      => 'Cash Tunai',
            'code'      => 'cash',
            'type'      => 'cash',
            'is_active' => true,
        ]);

        // Coba toggle off
        $responseToggle = $this->actingAs($this->superAdmin)
            ->patch(route('admin.payment-methods.toggle', $method->id));

        $responseToggle->assertSessionHas('failed');
        $this->assertTrue($method->fresh()->is_active);

        // Coba delete
        $responseDelete = $this->actingAs($this->superAdmin)
            ->delete(route('admin.payment-methods.destroy', $method->id));

        $responseDelete->assertSessionHas('failed');
        $this->assertDatabaseHas('payment_methods', ['id' => $method->id]);
    }

    public function test_customer_can_book_with_newly_added_active_method(): void
    {
        $pmBri = PaymentMethod::create([
            'name'           => 'Bank BRI',
            'code'           => 'bri',
            'type'           => 'bank',
            'account_number' => '999888777',
            'account_holder' => 'PT SCL',
            'is_active'      => true,
        ]);

        $kosan = ProductKosan::create([
            'title'        => 'Kosan Diamond',
            'slug'         => 'kosan-diamond',
            'wilayah'      => 'Jakarta Selatan',
            'description'  => 'Kos nyaman',
            'gmaps'        => 'https://maps.google.com',
            'total_kamar'  => 5,
            'kamar_kosong' => 5,
        ]);

        $kamar = ProductKamarKosan::create([
            'product_kosan_id' => $kosan->id,
            'room'             => 'Room 101',
            'floor'            => 1,
            'status'           => 'kosong',
        ]);

        $kamar->priceKamar()->create([
            'kategori' => 'bulan',
            'price'    => 1500000,
            'discount' => 0,
        ]);

        $proofFile = UploadedFile::fake()->image('struk_bri.jpg');

        $response = $this->post(route('tamu.booking', $kamar->id), [
            'name'              => 'Doni Permana',
            'telp'              => '081299887766',
            'email'             => 'doni@example.com',
            'start_date'        => now()->addDays(2)->format('Y-m-d'),
            'start_time'        => '11:00',
            'bulan'             => 1,
            'payment_method'    => 'bri', // Menggunakan metode baru yang dibuat admin
            'proof_of_transfer' => $proofFile,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tamus', [
            'email'          => 'doni@example.com',
            'payment_method' => 'bri',
            'status'         => 'pending',
        ]);
    }
}

