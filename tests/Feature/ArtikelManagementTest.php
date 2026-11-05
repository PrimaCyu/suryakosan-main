<?php

namespace Tests\Feature;

use App\Models\Artikel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArtikelManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'email' => 'superadmin@test.com',
        ]);
    }

    public function test_super_admin_can_view_artikel_index_page(): void
    {
        Artikel::create([
            'title' => 'Panduan Memilih Kos Nyaman',
            'slug' => 'panduan-memilih-kos-nyaman',
            'deskripsi' => '<p>Tips memilih kos yang nyaman dan strategis.</p>',
            'view' => 12,
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.artikel.index'));

        $response->assertOk();
        $response->assertSee('Kelola Artikel Blog');
        $response->assertSee('Panduan Memilih Kos Nyaman');
        $response->assertSee('Gaya Teks');
    }

    public function test_artikel_ajax_search_returns_filtered_results(): void
    {
        Artikel::create([
            'title' => 'Tips Hemat Anak Kos',
            'slug' => 'tips-hemat-anak-kos',
            'deskripsi' => '<p>Cara mengelola keuangan bulanan kos.</p>',
            'view' => 5,
        ]);

        Artikel::create([
            'title' => 'Dekorasi Kamar Minimalis',
            'slug' => 'dekorasi-kamar-minimalis',
            'deskripsi' => '<p>Inspirasi kamar tidur rapi dan aesthetic.</p>',
            'view' => 10,
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->getJson(route('admin.artikel.search.ajax', ['search' => 'Hemat']));

        $response->assertOk();
        $response->assertJsonPath('status', 'success');
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Tips Hemat Anak Kos', $response->json('data.0.title'));
    }

    public function test_super_admin_can_create_article_with_image(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('cover.jpg', 800, 600);

        $response = $this->actingAs($this->superAdmin)->post(route('admin.artikel.insert'), [
            'title' => 'Artikel Baru Edukasi Kos',
            'image' => $image,
            'deskripsi' => '<h2>Sub Bab</h2><p>Ini adalah naskah lengkap artikel dengan formatting.</p>',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('artikels', [
            'title' => 'Artikel Baru Edukasi Kos',
            'slug' => 'artikel-baru-edukasi-kos',
        ]);

        $artikel = Artikel::where('slug', 'artikel-baru-edukasi-kos')->first();
        $this->assertNotNull($artikel->image);
        Storage::disk('public')->assertExists($artikel->image);
    }

    public function test_super_admin_can_update_article(): void
    {
        $artikel = Artikel::create([
            'title' => 'Judul Lama',
            'slug' => 'judul-lama',
            'deskripsi' => '<p>Konten artikel lama yang akan diubah.</p>',
            'view' => 0,
        ]);

        $response = $this->actingAs($this->superAdmin)->put(route('admin.artikel.update', $artikel->id), [
            'title' => 'Judul Baru Setelah Edit',
            'deskripsi' => '<p>Konten artikel yang telah diperbarui secara profesional.</p>',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('artikels', [
            'id' => $artikel->id,
            'title' => 'Judul Baru Setelah Edit',
            'slug' => 'judul-baru-setelah-edit',
        ]);
    }

    public function test_super_admin_can_delete_article(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('test.jpg');
        $path = $image->store('artikels', 'public');

        $artikel = Artikel::create([
            'title' => 'Artikel Akan Dihapus',
            'slug' => 'artikel-akan-dihapus',
            'image' => $path,
            'deskripsi' => '<p>Konten artikel yang akan segera dihapus permanen.</p>',
            'view' => 1,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->superAdmin)->delete(route('admin.artikel.delete', $artikel->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('artikels', ['id' => $artikel->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_article_validation_prevents_empty_fields(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('admin.artikel.insert'), [
            'title' => '',
            'deskripsi' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'deskripsi']);
    }
}

