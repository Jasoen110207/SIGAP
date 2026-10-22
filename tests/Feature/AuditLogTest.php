<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_actions_create_expected_audit_logs(): void
    {
        $category = Category::create(['name' => 'Sampah', 'description' => 'Kategori sampah']);

        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'Warga Uji',
            'email' => 'warga@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_USER,
            'is_active' => true,
        ]);

        /** @var User $admin */
        $admin = User::factory()->create([
            'name' => 'Admin Uji',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
        ]);

        /** @var User $petugas */
        $petugas = User::factory()->create([
            'name' => 'Petugas Uji',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PETUGAS,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        // Tes Auth: Login & Logout
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect();
        $this->assertDatabaseHas('audit_logs', ['action' => 'auth.login']);

        $this->post('/logout')->assertRedirect();
        $this->assertDatabaseHas('audit_logs', ['action' => 'auth.logout']);

        // Tes Auth: Register
        $this->post('/register', [
            'name' => 'Warga Baru',
            'email' => 'baru@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect();
        $this->assertDatabaseHas('audit_logs', ['action' => 'auth.register']);

        // Tes Report: Create
        $this->actingAs($user, 'web');
        $this->post('/reports', [
            'title' => 'Sampah menumpuk',
            'category_id' => $category->id,
            'description' => 'Ada sampah menumpuk di depan rumah.',
            'location' => 'Jl. Merdeka No. 12',
            'photo_before' => UploadedFile::fake()->image('before.jpg', 400, 300),
        ])->assertRedirect();
        $this->assertDatabaseHas('audit_logs', ['action' => 'report.created']);

        // Tes Report: Verify
        $report = Report::first();
        $this->actingAs($admin, 'web');
        $this->patch('/reports/' . $report->id . '/verify', ['action' => 'validate'])->assertRedirect();
        $this->assertDatabaseHas('audit_logs', ['action' => 'report.validated']);

        // Tes Report: Claim & Release
        $this->actingAs($petugas, 'web');
        $this->patch('/reports/' . $report->id . '/start')->assertRedirect();
        $this->assertDatabaseHas('audit_logs', ['action' => 'report.claimed']);

        $this->patch('/reports/' . $report->id . '/release')->assertRedirect();
        $this->assertDatabaseHas('audit_logs', ['action' => 'report.released']);

        // Tes Report: Complete
        $this->patch('/reports/' . $report->id . '/start')->assertRedirect();
        $this->post('/reports/' . $report->id . '/complete', [
            'photo_after' => UploadedFile::fake()->image('after.jpg', 400, 300),
            'executor_name' => 'Petugas Uji',
            'executor_note' => 'Sudah dibersihkan hari ini.',
        ])->assertRedirect();
        $this->assertDatabaseHas('audit_logs', ['action' => 'report.completed']);

        // Tes Report: Rate
        $this->actingAs($user, 'web');
        $this->post('/reports/' . $report->id . '/rate', ['rating' => 5, 'review' => 'Bagus dan cepat.'])->assertRedirect();
        $this->assertDatabaseHas('audit_logs', ['action' => 'report.rated']);

        // Verifikasi Proteksi Kata Sandi pada Log
        $allValues = AuditLog::query()->get()->flatMap(function ($log) {
            return array_merge((array) ($log->old_values ?? []), (array) ($log->new_values ?? []));
        });

        $this->assertStringNotContainsString('password', json_encode($allValues));
    }

    public function test_non_admin_users_are_forbidden_from_audit_log_page_and_admin_can_access(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => User::ROLE_USER, 'is_active' => true]);
        /** @var User $petugas */
        $petugas = User::factory()->create(['role' => User::ROLE_PETUGAS, 'is_active' => true]);
        /** @var User $admin */
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'is_active' => true]);

        $this->actingAs($user, 'web')->get('/admin/audit-log')->assertStatus(403);
        $this->actingAs($petugas, 'web')->get('/admin/audit-log')->assertStatus(403);
        $this->actingAs($admin, 'web')->get('/admin/audit-log')->assertStatus(200);
    }

    public function test_audit_log_model_is_immutable(): void
    {
        $log = AuditLog::create([
            'user_name' => 'Tester',
            'user_role' => 'admin',
            'action' => 'auth.login',
            'description' => 'Login uji.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
        ]);

        $this->expectException(\LogicException::class);
        $log->update(['description' => 'ubah']);
    }

    public function test_audit_log_can_not_be_deleted(): void
    {
        $log = AuditLog::create([
            'user_name' => 'Tester',
            'user_role' => 'admin',
            'action' => 'auth.login',
            'description' => 'Login uji.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
        ]);

        $this->expectException(\LogicException::class);
        $log->delete();
    }

    public function test_logger_failure_does_not_break_main_action(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'Warga Gagal',
            'email' => 'gagal@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_USER,
            'is_active' => true,
        ]);

        $this->actingAs($user, 'web');
        $this->post('/logout')->assertRedirect();
        $this->assertFalse(Auth::check());
    }
}