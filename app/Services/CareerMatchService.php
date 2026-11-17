<?php

namespace App\Services;

use App\Models\AkunProfil;
use App\Models\Profesi;
use Illuminate\Support\Collection;

/**
 * UC Career Match: persentase kecocokan skill + pengalaman user terhadap kriteria_skill profesi.
 */
class CareerMatchService
{
    private const BOBOT_SKILL = 0.85;

    private const BOBOT_PENGALAMAN = 0.15;

    /**
     * @return Collection<int, array{profesi: Profesi, persen: int, dimiliki: list<string>, kurang: list<string>}>
     */
    public function hitung(AkunProfil $akun, int $limit = 3): Collection
    {
        $skills = $akun->skills;
        if ($skills->isEmpty()) {
            return collect();
        }

        $skillUser = $skills->mapWithKeys(fn ($s) => [$this->normal($s->nama_skill) => $s->bobot()]);
        $teksPengalaman = $akun->pengalaman->map(fn ($p) => $this->normal($p->posisi.' '.$p->deskripsi))->all();

        return Profesi::with('kategori')->get()
            ->map(fn (Profesi $p) => $this->nilaiProfesi($p, $skillUser->all(), $teksPengalaman))
            ->filter(fn (array $m) => $m['persen'] > 0) // profesi tanpa kecocokan tidak ditampilkan
            ->sortByDesc('persen')
            ->take($limit)
            ->values();
    }

    /**
     * @param  array<string, float>  $skillUser
     * @param  list<string>  $teksPengalaman  teks tiap pengalaman
     */
    private function nilaiProfesi(Profesi $p, array $skillUser, array $teksPengalaman): array
    {
        $kriteria = $p->daftarSkill();
        $dimiliki = [];
        $kurang = [];
        $skorSkill = 0.0;

        foreach ($kriteria as $k) {
            $kunci = $this->normal($k);
            if (isset($skillUser[$kunci])) {
                $dimiliki[] = $k;
                $skorSkill += $skillUser[$kunci];
            } else {
                $kurang[] = $k;
            }
        }

        // Rasio dihitung terhadap jumlah skill user agar profil kecil tetap bisa cocok tinggi.
        $pembagi = max(1, min(count($kriteria), count($skillUser)));
        $rasioSkill = min(1.0, $skorSkill / $pembagi);

        // Bonus pengalaman proporsional: berapa bagian pengalaman user yang relevan dengan profesi.
        $namaProfesi = $this->normal($p->nama_profesi);
        $relevan = collect($teksPengalaman)->filter(fn (string $teks) => str_contains($teks, $namaProfesi)
            || collect($kriteria)->contains(fn ($k) => str_contains($teks, $this->normal($k))))->count();
        $rasioPengalaman = $teksPengalaman === [] ? 0 : $relevan / count($teksPengalaman);

        $persen = (int) round(100 * ($rasioSkill * self::BOBOT_SKILL + $rasioPengalaman * self::BOBOT_PENGALAMAN));

        return ['profesi' => $p, 'persen' => min(100, $persen), 'dimiliki' => $dimiliki, 'kurang' => $kurang];
    }

    private function normal(string $teks): string
    {
        return mb_strtolower(trim($teks));
    }
}
