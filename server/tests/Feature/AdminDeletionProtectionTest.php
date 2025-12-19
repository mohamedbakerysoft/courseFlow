<?php

namespace Tests\Feature;

use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminDeletionProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_delete_own_account(): void
    {
        $this->seed(AdminSeeder::class);
        $admin = User::where('email', config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL))->firstOrFail();

        $response = $this->actingAs($admin)->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

        $response->assertStatus(403);
        $this->assertNotNull(User::find($admin->id));
    }
}
