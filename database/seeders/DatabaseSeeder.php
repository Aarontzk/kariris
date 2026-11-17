<?php

namespace Database\Seeders;

use App\Models\AkunProfil;
use App\Models\KategoriProfesi;
use App\Models\Profesi;
use App\Models\RutinitasHarian;
use Illuminate\Database\Seeder;
use RuntimeException;

/**
 * Data mengikuti isi Supabase tim KARIRIS (database/seeders/data/kariris.json).
 *
 * Idempoten: baris dicari lewat natural key (username, nama_profesi, kegiatan, urutan, ...).
 * Baris yang sudah ada tidak diubah, hanya kolom tambahan UI yang masih kosong diisi.
 * Jadi aman dijalankan di Supabase yang sudah berisi data maupun di database kosong.
 */
class DatabaseSeeder extends Seeder
{
    /** Password akun contoh, sama dengan yang dipakai tim di Supabase. */
    public const PASSWORD_DEMO = 'kariris123';

    public function run(): void
    {
        $data = $this->bacaData();

        foreach ($data['kategori'] as $nama) {
            KategoriProfesi::firstOrCreate(['nama_kategori' => $nama]);
        }

        $akun = [];
        foreach ($data['akun'] as $a) {
            $akun[$a['username']] = $this->seedAkun($a);
        }

        foreach ($data['profesi'] as $p) {
            $this->seedProfesi($p, $akun);
        }

        foreach ($data['bookmarks'] as $b) {
            $profesi = Profesi::where('nama_profesi', $b['profesi'])->firstOrFail();
            $akun[$b['username']]->bookmarks()->syncWithoutDetaching([
                $profesi->id => ['tanggal_ditandai' => $b['tanggal_ditandai']],
            ]);
        }
    }

    /** @return array<string, mixed> */
    private function bacaData(): array
    {
        $path = database_path('seeders/data/kariris.json');
        $data = json_decode((string) file_get_contents($path), true);
        if (! is_array($data)) {
            throw new RuntimeException("Data seeder tidak valid: {$path}");
        }

        return $data;
    }

    /** @param  array<string, mixed>  $a */
    private function seedAkun(array $a): AkunProfil
    {
        $user = AkunProfil::firstOrCreate(
            ['username' => $a['username']],
            ['email' => $a['email'], 'phone_number' => $a['phone_number'], 'password' => self::PASSWORD_DEMO],
        );

        foreach ($a['skills'] as $s) {
            $user->skills()->firstOrCreate(['nama_skill' => $s['nama_skill']], ['level' => $s['level']]);
        }
        foreach ($a['pengalaman'] as $p) {
            $user->pengalaman()->firstOrCreate(
                ['posisi' => $p['posisi'], 'perusahaan' => $p['perusahaan']],
                $p,
            );
        }

        return $user;
    }

    /**
     * @param  array<string, mixed>  $p
     * @param  array<string, AkunProfil>  $akun
     */
    private function seedProfesi(array $p, array $akun): void
    {
        $kategori = KategoriProfesi::where('nama_kategori', $p['kategori'])->firstOrFail();
        $atribut = collect($p)->except(['kategori', 'rutinitas', 'proyek', 'reviews'])->all();

        $profesi = Profesi::firstOrCreate(
            ['nama_profesi' => $p['nama_profesi']],
            $atribut + ['kategori_profesi_id' => $kategori->id],
        );
        // save() memicu hook yang mengisi slug & gambar bila masih kosong.
        $profesi->save();

        foreach ($p['rutinitas'] as $r) {
            $row = $profesi->rutinitas()->firstOrCreate(['kegiatan' => $r['kegiatan']], $r);
            $this->isiKosong($row, ['ikon' => RutinitasHarian::tebakIkon($r['kegiatan'])]);
        }

        foreach ($p['proyek'] as $pr) {
            $proyek = $profesi->proyek()->firstOrCreate(['nama_proyek' => $pr['nama_proyek']], ['deskripsi' => $pr['deskripsi']]);
            foreach ($pr['fase'] as $f) {
                $fase = $proyek->fase()->firstOrCreate(['urutan' => $f['urutan']], $f);
                $this->isiKosong($fase, collect($f)->only(['ringkasan', 'durasi', 'sprint', 'ikon'])->all());
            }
        }

        foreach ($p['reviews'] as $r) {
            $profesi->reviews()->firstOrCreate(
                ['akun_profil_id' => $akun[$r['username']]->id],
                collect($r)->except('username')->all(),
            );
        }
    }

    /**
     * Isi kolom tambahan UI hanya bila masih NULL di database (nilai tim tidak ditimpa).
     *
     * @param  array<string, mixed>  $nilai
     */
    private function isiKosong(\Illuminate\Database\Eloquent\Model $model, array $nilai): void
    {
        $kosong = array_filter($nilai, fn ($v, $k) => $model->getRawOriginal($k) === null, ARRAY_FILTER_USE_BOTH);
        if ($kosong !== []) {
            $model->forceFill($kosong)->save();
        }
    }
}
