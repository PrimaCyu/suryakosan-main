<?php

namespace Tests\Feature;

use App\Models\SosialMedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SosialMediaIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $branchAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $this->branchAdmin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_super_admin_can_access_sosial_media_management(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.sosial.media.index'));
        $response->assertStatus(200);
        $response->assertSee('Kelola Sosial Media');
    }

    public function test_branch_admin_cannot_access_sosial_media_management(): void
    {
        $response = $this->actingAs($this->branchAdmin)->get(route('admin.sosial.media.index'));
        $response->assertStatus(403);
    }

    public function test_super_admin_can_create_update_and_delete_sosial_media(): void
    {
        $this->actingAs($this->superAdmin);

        // 1. Create
        $responseCreate = $this->post(route('admin.sosial.media.insert'), [
            'title' => 'Instagram',
            'url'   => 'https://instagram.com/sinarcitralestari',
        ]);
        $responseCreate->assertRedirect();
        $this->assertDatabaseHas('sosial_media', [
            'title' => 'Instagram',
            'url'   => 'https://instagram.com/sinarcitralestari',
        ]);

        $sm = SosialMedia::where('title', 'Instagram')->first();

        // 2. Update
        $responseUpdate = $this->put(route('admin.sosial.media.update', $sm->id), [
            'title' => 'Instagram Official',
            'url'   => 'https://instagram.com/scl_official',
        ]);
        $responseUpdate->assertRedirect();
        $this->assertDatabaseHas('sosial_media', [
            'id'    => $sm->id,
            'title' => 'Instagram Official',
            'url'   => 'https://instagram.com/scl_official',
        ]);

        // 3. Delete
        $responseDelete = $this->delete(route('admin.sosial.media.delete', $sm->id));
        $responseDelete->assertRedirect();
        $this->assertDatabaseMissing('sosial_media', [
            'id' => $sm->id,
        ]);
    }

    public function test_frontend_reflects_sosial_media_in_footer_and_navbar(): void
    {
        SosialMedia::create([
            'title' => 'Instagram',
            'url'   => 'https://instagram.com/sinarcitralestari',
        ]);

        SosialMedia::create([
            'title' => 'WhatsApp Admin',
            'url'   => 'https://wa.me/628999888777',
        ]);

        // Request homepage as guest
        $response = $this->get(route('home'));
        $response->assertStatus(200);

        // Check footer renders the custom links
        $response->assertSee('https://instagram.com/sinarcitralestari');
        $response->assertSee('fa-instagram');

        // Check navbar & CTA dynamically uses WhatsApp number
        $response->assertSee('628999888777');
    }
}

