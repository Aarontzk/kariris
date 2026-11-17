<?php

namespace Tests\Feature;

use App\Models\AkunProfil;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AkunTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function demo(): AkunProfil
    {
        return AkunProfil::where('username', 'christinerotua')->firstOrFail();
    }

    public function test_landing_dan_halaman_auth_tampil(): void
    {
        $this->get('/')->assertOk()->assertSee('KNOW YOUR CAREER');
        $this->get('/login')->assertOk()->assertSee('Account name');
        $this->get('/register')->assertOk()->assertSee('Confirm Password');
    }

    public function test_registrasi_membuat_akun_dan_login(): void
    {
        $this->post('/register', [
            'username' => 'budi_s', 'email' => 'budi@example.com', 'phone_number' => '081234567890',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('welcome'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('akun_profil', ['username' => 'budi_s']);
    }

    public function test_registrasi_menolak_data_tidak_valid(): void
    {
        $this->post('/register', [
            'username' => 'christinerotua', 'email' => 'bukan-email', 'phone_number' => '12',
            'password' => 'a', 'password_confirmation' => 'b',
        ])->assertSessionHasErrors(['username', 'email', 'phone_number', 'password']);
        $this->assertGuest();
    }

    public function test_login_butuh_account_name_email_dan_password(): void
    {
        $this->get('/login')->assertSee('Account name')->assertSee('Email')->assertSee('Password');
        $this->post('/login', ['username' => 'christinerotua', 'email' => '5026241000@its.ac.id', 'password' => \Database\Seeders\DatabaseSeeder::PASSWORD_DEMO])
            ->assertRedirect(route('welcome'));
    }

    public function test_login_gagal_kalau_salah_satu_tidak_cocok(): void
    {
        // email milik akun lain
        $this->post('/login', ['username' => 'christinerotua', 'email' => 'messi@example.com', 'password' => \Database\Seeders\DatabaseSeeder::PASSWORD_DEMO])
            ->assertSessionHasErrors('username');
        // password salah
        $this->post('/login', ['username' => 'christinerotua', 'email' => '5026241000@its.ac.id', 'password' => 'salah'])
            ->assertSessionHasErrors('username');
        // email tidak boleh digabung ke kolom account name
        $this->post('/login', ['username' => '5026241000@its.ac.id', 'password' => \Database\Seeders\DatabaseSeeder::PASSWORD_DEMO])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_halaman_terproteksi_redirect_ke_login(): void
    {
        $this->get('/profile')->assertRedirect('/login');
    }

    public function test_profil_menampilkan_skill_pengalaman_dan_career_match(): void
    {
        $this->actingAs($this->demo())->get('/profile')
            ->assertOk()
            ->assertSee('christinerotua')
            ->assertSee('Story Telling')
            ->assertSee('UI/UX Designer Intern')
            ->assertSee('Career Match');
    }

    public function test_crud_skill(): void
    {
        $akun = $this->demo();
        $this->actingAs($akun)->post('/skills', ['nama_skill' => 'Python', 'level' => 'Basic'])->assertSessionHas('toast', 'Changes Saved');
        $skill = $akun->skills()->where('nama_skill', 'Python')->firstOrFail();

        $this->put("/skills/{$skill->id}", ['nama_skill' => 'Python', 'level' => 'Advanced']);
        $this->assertSame('Advanced', $skill->fresh()->level);

        $this->delete("/skills/{$skill->id}")->assertSessionHas('toast', 'Entry Deleted');
        $this->assertDatabaseMissing('skill', ['id' => $skill->id]);
    }

    public function test_skill_level_tidak_valid_ditolak(): void
    {
        $this->actingAs($this->demo())->post('/skills', ['nama_skill' => 'X', 'level' => 'Expert'])
            ->assertSessionHasErrorsIn('skill', 'level');
    }

    public function test_tidak_bisa_menghapus_skill_milik_orang_lain(): void
    {
        $skill = $this->demo()->skills()->first();
        $lain = AkunProfil::where('username', 'joko')->first();
        $this->actingAs($lain)->delete("/skills/{$skill->id}")->assertForbidden();
    }

    public function test_crud_pengalaman(): void
    {
        $akun = $this->demo();
        $data = [
            'tipe' => 'Freelance', 'posisi' => 'Illustrator', 'perusahaan' => 'Studio A',
            'tanggal_mulai' => '2025-01-01', 'tanggal_selesai' => '2025-03-01', 'deskripsi' => 'Ilustrasi buku',
        ];
        $this->actingAs($akun)->post('/pengalaman', $data)->assertSessionHas('toast');
        $exp = $akun->pengalaman()->where('posisi', 'Illustrator')->firstOrFail();

        $this->put("/pengalaman/{$exp->id}", [...$data, 'posisi' => 'Senior Illustrator']);
        $this->assertSame('Senior Illustrator', $exp->fresh()->posisi);

        $this->delete("/pengalaman/{$exp->id}");
        $this->assertDatabaseMissing('pengalaman', ['id' => $exp->id]);
    }

    public function test_tanggal_selesai_sebelum_mulai_ditolak(): void
    {
        $this->actingAs($this->demo())->post('/pengalaman', [
            'tipe' => 'Internship', 'posisi' => 'A', 'perusahaan' => 'B',
            'tanggal_mulai' => '2025-05-01', 'tanggal_selesai' => '2025-01-01',
        ])->assertSessionHasErrorsIn('pengalaman', 'tanggal_selesai');
    }

    public function test_ganti_username_dibatasi_30_hari(): void
    {
        $akun = $this->demo();
        $this->actingAs($akun)->put('/settings/username', ['username' => 'christine'])->assertSessionHas('toast', 'Username Updated');
        $this->put('/settings/username', ['username' => 'christine2'])->assertSessionHasErrorsIn('username', 'username');
        $this->assertSame('christine', $akun->fresh()->username);
    }

    public function test_ganti_password(): void
    {
        $akun = $this->demo();
        $this->actingAs($akun)->put('/settings/password', ['password' => 'baru12345', 'password_confirmation' => 'baru12345'])
            ->assertSessionHas('toast', 'Password Updated');
        $this->post('/logout');
        $this->post('/login', ['username' => 'christinerotua', 'email' => '5026241000@its.ac.id', 'password' => 'baru12345'])->assertRedirect(route('welcome'));
    }
}
