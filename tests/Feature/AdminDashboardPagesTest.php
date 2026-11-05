<?php

namespace Tests\Feature;

use App\Models\ProductKamarKosan;
use App\Models\ProductKosan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_admin_dashboard_pages_render_cleanly_for_super_admin(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $kosan = ProductKosan::create([
            'title' => 'Kosan Sinar Citra Denpasar',
            'slug' => 'kosan-sinar-citra-denpasar',
            'wilayah' => 'Denpasar',
            'description' => 'Kosan nyaman di pusat kota Denpasar',
            'fasilitas' => 'WiFi, Parkir Luas',
            'tersedia' => 5,
            'view' => 10,
        ]);

        ProductKamarKosan::create([
            'product_kosan_id' => $kosan->id,
            'room' => '101',
            'slug' => 'kamar-101',
            'description' => 'Kamar AC kamar mandi dalam',
            'jenis_kos' => 'Campur',
            'ukuran_kamar' => '3x4',
            'tersedia' => 1,
            'status_kamar' => 'Tersedia',
            'fasilitas' => 'AC, WiFi',
        ]);

        $routes = [
            route('admin.dashboard'),
            route('admin.product.kosan.index'),
            route('admin.product.kosan.kamar.index', $kosan->id),
            route('admin.booking.index'),
            route('admin.artikel.index'),
            route('admin.testimoni.index'),
            route('admin.users.index'),
            route('admin.payment-methods.index'),
            route('admin.sosial.media.index'),
            route('admin.profile.edit'),
        ];

        foreach ($routes as $url) {
            $response = $this->actingAs($superAdmin)->get($url);
            $response->assertStatus(200);
        }
    }

    public function test_branch_admin_dashboard_and_assigned_pages_render_cleanly(): void
    {
        $branchAdmin = User::factory()->create([
            'role' => 'admin',
        ]);

        $kosan = ProductKosan::create([
            'title' => 'Kosan Cabang Badung',
            'slug' => 'kosan-cabang-badung',
            'wilayah' => 'Badung',
            'description' => 'Deskripsi cabang Badung',
            'fasilitas' => 'WiFi',
            'tersedia' => 3,
            'view' => 5,
        ]);

        $branchAdmin->kosans()->attach($kosan->id);

        $allowedRoutes = [
            route('admin.dashboard'),
            route('admin.product.kosan.index'),
            route('admin.product.kosan.kamar.index', $kosan->id),
            route('admin.booking.index'),
            route('admin.artikel.index'),
            route('admin.testimoni.index'),
            route('admin.profile.edit'),
        ];

        foreach ($allowedRoutes as $url) {
            $response = $this->actingAs($branchAdmin)->get($url);
            $response->assertStatus(200);
        }
    }
}

