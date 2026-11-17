<?php

namespace Tests\Feature;

use App\Models\AkunProfil;
use App\Models\FaseKerja;
use App\Models\Profesi;
use App\Models\RutinitasHarian;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfesiTest extends TestCase
{
    use RefreshDatabase;

    private AkunProfil $akun;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->akun = AkunProfil::where('username', 'christinerotua')->firstOrFail();
        $this->actingAs($this->akun);
    }

    public function test_search_menampilkan_semua_profesi(): void
    {
        $this->get('/search')->assertOk()->assertSee('Search Menu')->assertSee('Data Analyst')->assertSee('Content Writer');
    }

    public function test_search_kata_kunci(): void
    {
        $this->get('/search?q=data')->assertOk()
            ->assertSee('4 Professions Found') // 3 profesi Data + Product Manager (skill Data Analysis)
            ->assertSee('Data Engineer')
            ->assertDontSee('SAP Consultant');
    }

    public function test_filter_kategori(): void
    {
        $this->get('/search?kategori=Business')->assertOk()
            ->assertSee('SAP Consultant')
            ->assertDontSee('Data Scientist');
    }

    public function test_detail_profesi(): void
    {
        $this->get('/profesi/data-analyst')->assertOk()
            ->assertSee('Career Profile')->assertSee('SUMMARY')->assertSee('How To Join')
            ->assertSee('What Professional Say')->assertSee('Burnout Rate');
    }

    public function test_review_json(): void
    {
        $this->getJson('/profesi/data-analyst/review')->assertOk()
            ->assertJsonPath('profesi', 'Data Analyst')
            ->assertJsonCount(2, 'reviews');
    }

    public function test_bookmark_simpan_lihat_hapus(): void
    {
        $this->post('/profesi/uiux-designer/bookmark')->assertSessionHas('toast');
        $this->post('/profesi/uiux-designer/bookmark'); // idempoten
        $this->assertSame(1, $this->akun->bookmarks()->count());

        $this->get('/bookmarks')->assertOk()->assertSee('UI/UX Designer')->assertDontSee('SAP Consultant');

        $this->delete('/profesi/uiux-designer/bookmark');
        $this->assertSame(0, $this->akun->bookmarks()->count());
        $this->get('/bookmarks')->assertSee('Belum ada profesi');
    }

    public function test_career_match_mengurutkan_uiux_teratas(): void
    {
        $res = $this->getJson('/career-match')->assertOk()->assertJsonCount(3);
        $this->assertSame('UI/UX Designer', $res->json('0.profesi'));
        $this->assertSame([93, 78, 61], array_column($res->json(), 'persen'));
        $this->assertContains('Figma', $res->json('0.dimiliki'));
    }

    public function test_career_match_kosong_tanpa_skill(): void
    {
        $this->akun->skills()->delete();
        $this->getJson('/career-match')->assertOk()->assertJsonCount(0);
    }

    public function test_day_in_the_life_rutin_dan_project(): void
    {
        $this->get('/profesi/data-analyst/day-in-the-life')->assertOk()
            ->assertSee('RUTINITAS HARIAN')->assertSee('Daily Standup')->assertSee('8 jam')
            ->assertSee('Fase 1 | Requirement')->assertSee('4 minggu');
    }

    public function test_detail_rutinitas_dan_fase(): void
    {
        $r = RutinitasHarian::where('kegiatan', 'Daily Standup')->first();
        $this->getJson("/rutinitas/{$r->id}")->assertJson(['jam' => '09.00', 'durasi' => '120 menit']);

        $f = FaseKerja::where('nama_fase', 'Requirement')->first();
        $this->getJson("/fase/{$f->id}")->assertJson(['durasi' => '1 minggu', 'sprint' => 'Sprint 1']);
    }

    public function test_profesi_tidak_ada_404(): void
    {
        $this->get('/profesi/tidak-ada')->assertNotFound();
        $this->assertSame(9, Profesi::count());
    }
}
