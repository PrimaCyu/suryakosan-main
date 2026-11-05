<?php

namespace Tests\Feature;

use App\Models\FasilitasKamar;
use App\Models\PriceKamar;
use App\Models\ProductKamarKosan;
use App\Models\ProductKosan;
use App\Models\Tamu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ComprehensiveSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $branchAdmin;
    protected ProductKosan $kosanA;
    protected ProductKosan $kosanB;
    protected ProductKamarKosan $kamarA;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::fake('public');

        // Create Super Admin
        $this->superAdmin = User::create([
            'name'     => 'Super Administrator',
            'email'    => 'superadmin@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'super_admin',
        ]);

        // Create Branch Admin
        $this->branchAdmin = User::create([
            'name'     => 'Branch Admin',
            'email'    => 'branchadmin@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
        ]);

        // Create Kosan A and assign to Branch Admin
        $this->kosanA = ProductKosan::create([
            'title'       => 'Kos Sakura Indah',
            'slug'        => 'kos-sakura-indah',
            'description' => 'Kos nyaman dan bersih.',
            'fasilitas'   => 'WiFi, AC, Parkir',
            'wilayah'     => 'Denpasar',
            'view'        => 0,
            'tersedia'    => 5,
        ]);
        $this->branchAdmin->kosans()->attach($this->kosanA->id);

        // Create Kosan B (not assigned to Branch Admin)
        $this->kosanB = ProductKosan::create([
            'title'       => 'Kos Melati Harmoni',
            'slug'        => 'kos-melati-harmoni',
            'description' => 'Kos tengah kota.',
            'fasilitas'   => 'WiFi, Dapur',
            'wilayah'     => 'Badung',
            'view'        => 0,
            'tersedia'    => 3,
        ]);

        // Create Kamar for Kosan A
        $this->kamarA = ProductKamarKosan::create([
            'product_kosan_id'    => $this->kosanA->id,
            'room'                => 'Kamar 101 Standard',
            'description'         => 'Kamar lantai 1',
            'fasilitas'           => 'Kasur, Lemari, Meja',
            'cumulative_discount' => 10, // 10% discount
            'views'               => 0,
        ]);

        // Add monthly & yearly price to Kamar A
        PriceKamar::create([
            'product_kamar_kosan_id' => $this->kamarA->id,
            'kategori'               => 'bulan',
            'price'                  => 1000000,
            'discount'               => 10,
        ]);

        PriceKamar::create([
            'product_kamar_kosan_id' => $this->kamarA->id,
            'kategori'               => 'tahun',
            'price'                  => 11000000,
            'discount'               => 10,
        ]);
    }

    /**
     * 1. Test Security Headers Middleware
     */
    public function test_security_headers_are_present_on_web_responses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    /**
     * 2. Test Anti-Price Tampering on Booking
     */
    public function test_booking_total_price_is_strictly_computed_server_side(): void
    {
        $startDate = now()->addDays(5)->format('Y-m-d');
        $file = UploadedFile::fake()->image('bukti_transfer.jpg');

        // Attacker attempts to send fake client total_price of 500 rupiah
        $payload = [
            'name'              => 'Budi Santoso',
            'email'             => 'budi@example.com',
            'telp'              => '081234567890',
            'start_date'        => $startDate,
            'start_time'        => '09:00',
            'bulan'             => 2, // 2 months: 2 * 1,000,000 = 2,000,000 - 10% discount = 1,800,000
            'tahun'             => 0,
            'payment_method'    => 'transfer',
            'proof_of_transfer' => $file,
            'total_price'       => 500, // Attacker tamper attempt
        ];

        $response = $this->post(route('tamu.booking', $this->kamarA->id), $payload);

        $response->assertRedirect();

        $tamu = Tamu::where('email', 'budi@example.com')->first();
        $this->assertNotNull($tamu);
        // Server MUST calculate 2,016,000 (1,800,000 + 12% PPN) and completely ignore the 500 rupiah client payload
        $this->assertEquals(2016000, (float)$tamu->total_price);
        $this->assertEquals('pending', $tamu->status);
    }

    /**
     * 3. Test Proof of Transfer Validation & Private Disk Storage
     */
    public function test_proof_of_transfer_is_required_for_transfer_and_stored_privately(): void
    {
        $startDate = now()->addDays(10)->format('Y-m-d');

        // Attempt transfer without proof_of_transfer
        $payloadNoProof = [
            'name'           => 'Rina Melati',
            'email'          => 'rina@example.com',
            'telp'           => '081987654321',
            'start_date'     => $startDate,
            'start_time'     => '10:00',
            'bulan'          => 1,
            'tahun'          => 0,
            'payment_method' => 'transfer',
        ];

        $response = $this->post(route('tamu.booking', $this->kamarA->id), $payloadNoProof);
        $response->assertSessionHasErrors(['proof_of_transfer']);

        // Now submit with valid proof_of_transfer file
        $file = UploadedFile::fake()->image('struk_atm.png');
        $payloadWithProof = array_merge($payloadNoProof, ['proof_of_transfer' => $file]);

        $responseSuccess = $this->post(route('tamu.booking', $this->kamarA->id), $payloadWithProof);
        $responseSuccess->assertRedirect();

        $tamu = Tamu::where('email', 'rina@example.com')->first();
        $this->assertNotNull($tamu);
        $this->assertNotEmpty($tamu->proof_of_transfer);

        // Verify stored in private local disk, NOT in public
        Storage::disk('local')->assertExists($tamu->proof_of_transfer);
        Storage::disk('public')->assertMissing($tamu->proof_of_transfer);
    }

    /**
     * 4. Test Sub-resource IDOR Protection in KamarKosanController
     */
    public function test_branch_admin_cannot_access_or_modify_unauthorized_kosan_subresources(): void
    {
        // Create room in Kosan B (which branch admin does not manage)
        $kamarB = ProductKamarKosan::create([
            'product_kosan_id' => $this->kosanB->id,
            'room'             => 'Kamar 202 Melati',
            'views'            => 0,
        ]);

        $this->actingAs($this->branchAdmin);

        // Attempt to insert facility to Kamar B
        $response = $this->post(route('admin.product.kosan.kamar.fasilitas.insert', [
            'product_kosan'       => $this->kosanB->id,
            'product_kamar_kosan' => $kamarB->id,
        ]), [
            'fasilitasKamar' => [
                ['title' => 'AC Inverter'],
            ],
        ]);

        // Must be rejected with 403 Forbidden
        $response->assertStatus(403);
    }

    /**
     * 5. Test Super Admin Authorization on Global Modules
     */
    public function test_branch_admin_cannot_access_super_admin_social_media_and_users(): void
    {
        $this->actingAs($this->branchAdmin);

        // Attempt to access users management
        $responseUsers = $this->get(route('admin.users.index'));
        $responseUsers->assertStatus(403);

        // Attempt to access social media management
        $responseSosmed = $this->get(route('admin.sosial.media.index'));
        $responseSosmed->assertStatus(403);

        // Super Admin CAN access both
        $this->actingAs($this->superAdmin);
        $this->get(route('admin.users.index'))->assertStatus(200);
        $this->get(route('admin.sosial.media.index'))->assertStatus(200);
    }

    /**
     * 6. Test Soft Deletes Preserving Booking Ledger
     */
    public function test_soft_deletes_preserves_tenant_records_when_room_is_deleted(): void
    {
        $this->actingAs($this->superAdmin);

        // Create completed past tenant booking
        $tamu = Tamu::create([
            'product_kamar_kosan_id' => $this->kamarA->id,
            'name'                   => 'Hendra Wijaya',
            'email'                  => 'hendra@example.com',
            'telp'                   => '081233445566',
            'start_date'             => now()->subMonths(2)->format('Y-m-d'),
            'end_date'               => now()->subMonth()->format('Y-m-d'),
            'start_time'             => '12:00',
            'payment_method'         => 'cod',
            'total_price'            => 1000000,
            'status'                 => 'approved',
        ]);

        // Delete Kamar A
        $response = $this->delete(route('admin.product.kosan.kamar.delete', [
            'product_kosan'       => $this->kosanA->id,
            'product_kamar_kosan' => $this->kamarA->id,
        ]));

        $response->assertRedirect();

        // Kamar is soft deleted (deleted_at is set)
        $this->assertSoftDeleted('product_kamar_kosans', ['id' => $this->kamarA->id]);

        // Tamu record STILL EXISTS in database!
        $this->assertDatabaseHas('tamus', ['id' => $tamu->id]);
    }

    /**
     * 7. Test Active Tenant Protects Room from Deletion
     */
    public function test_cannot_delete_room_with_active_occupant(): void
    {
        $this->actingAs($this->superAdmin);

        // Create active occupying tenant
        Tamu::create([
            'product_kamar_kosan_id' => $this->kamarA->id,
            'name'                   => 'Penyewa Aktif',
            'email'                  => 'aktif@example.com',
            'telp'                   => '08111222333',
            'start_date'             => now()->subDays(5)->format('Y-m-d'),
            'end_date'               => now()->addDays(20)->format('Y-m-d'),
            'start_time'             => '12:00',
            'payment_method'         => 'transfer',
            'total_price'            => 1000000,
            'status'                 => 'approved',
        ]);

        $response = $this->delete(route('admin.product.kosan.kamar.delete', [
            'product_kosan'       => $this->kosanA->id,
            'product_kamar_kosan' => $this->kamarA->id,
        ]));

        $response->assertSessionHas('failed');
        $this->assertNotSoftDeleted('product_kamar_kosans', ['id' => $this->kamarA->id]);
    }

    /**
     * 8. Test Automatic Collision Rejection upon Approval
     */
    public function test_approving_booking_auto_rejects_colliding_pending_bookings(): void
    {
        $this->actingAs($this->superAdmin);

        $start = now()->addDays(2)->format('Y-m-d');
        $end = now()->addDays(32)->format('Y-m-d');

        // Booking 1 (To be approved)
        $booking1 = Tamu::create([
            'product_kamar_kosan_id' => $this->kamarA->id,
            'name'                   => 'Pemohon Utama',
            'email'                  => 'utama@example.com',
            'telp'                   => '08122334455',
            'start_date'             => $start,
            'end_date'               => $end,
            'start_time'             => '10:00',
            'payment_method'         => 'transfer',
            'total_price'            => 1000000,
            'status'                 => 'pending',
        ]);

        // Booking 2 (Colliding dates, pending)
        $booking2 = Tamu::create([
            'product_kamar_kosan_id' => $this->kamarA->id,
            'name'                   => 'Pemohon Bertabrakan',
            'email'                  => 'tabrakan@example.com',
            'telp'                   => '08199887766',
            'start_date'             => now()->addDays(10)->format('Y-m-d'),
            'end_date'               => now()->addDays(40)->format('Y-m-d'),
            'start_time'             => '14:00',
            'payment_method'         => 'transfer',
            'total_price'            => 1000000,
            'status'                 => 'pending',
        ]);

        // Approve Booking 1
        $response = $this->put(route('admin.booking.approve', $booking1->id));
        $response->assertSessionHas('success');

        $booking1->refresh();
        $booking2->refresh();

        $this->assertEquals('approved', $booking1->status);
        $this->assertEquals('reject', $booking2->status);
        $this->assertStringContainsString('Kamar telah disetujui untuk pemohon booking lain', $booking2->rejection_reason);
    }

    /**
     * 9. Test Stale Pending Expiration Command
     */
    public function test_expire_pending_command_cleans_stale_bookings(): void
    {
        // Stale booking created 3 hours ago
        $staleBooking = Tamu::create([
            'product_kamar_kosan_id' => $this->kamarA->id,
            'name'                   => 'Pemohon Menggantung',
            'email'                  => 'gantung@example.com',
            'telp'                   => '08133221100',
            'start_date'             => now()->addDays(5)->format('Y-m-d'),
            'end_date'               => now()->addDays(35)->format('Y-m-d'),
            'start_time'             => '10:00',
            'payment_method'         => 'transfer',
            'total_price'            => 1000000,
            'status'                 => 'pending',
        ]);
        Tamu::where('id', $staleBooking->id)->update(['created_at' => now()->subHours(3)]);

        // Fresh booking created 30 minutes ago
        $freshBooking = Tamu::create([
            'product_kamar_kosan_id' => $this->kamarA->id,
            'name'                   => 'Pemohon Baru',
            'email'                  => 'baru@example.com',
            'telp'                   => '08177665544',
            'start_date'             => now()->addDays(60)->format('Y-m-d'),
            'end_date'               => now()->addDays(90)->format('Y-m-d'),
            'start_time'             => '11:00',
            'payment_method'         => 'transfer',
            'total_price'            => 1000000,
            'status'                 => 'pending',
            'created_at'             => now()->subMinutes(30),
        ]);

        Artisan::call('kosan:expire-pending', ['--hours' => 2]);

        $staleBooking->refresh();
        $freshBooking->refresh();

        $this->assertEquals('reject', $staleBooking->status);
        $this->assertStringContainsString('otomatis kedaluwarsa', $staleBooking->rejection_reason);
        $this->assertEquals('pending', $freshBooking->status);
    }
}
