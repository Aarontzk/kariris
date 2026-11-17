<?php

namespace Database\Seeders;

use App\Models\AkunProfil;
use App\Models\KategoriProfesi;
use App\Models\Profesi;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = $this->seedKategori();
        $profesi = $this->seedProfesi($kategori);
        $this->seedDemoUser();
        $this->seedReviews($profesi);
        $this->seedDayInTheLife($profesi);
    }

    /** @return array<string, KategoriProfesi> */
    private function seedKategori(): array
    {
        $data = [
            'Technology' => 'Technology & Data',
            'Business' => 'Business & Consultant',
            'Creative' => 'Design & Creative',
        ];
        $hasil = [];
        foreach ($data as $nama => $label) {
            $hasil[$nama] = KategoriProfesi::create(['nama_kategori' => $nama, 'label' => $label]);
        }

        return $hasil;
    }

    /**
     * @param  array<string, KategoriProfesi>  $kategori
     * @return array<string, Profesi>
     */
    private function seedProfesi(array $kategori): array
    {
        $hasil = [];
        foreach (ProfesiData::all() as $p) {
            $hasil[$p['slug']] = Profesi::create([
                'kategori_profesi_id' => $kategori[$p['kategori']]->id,
                'nama_profesi' => $p['nama'],
                'slug' => $p['slug'],
                'gambar' => 'img/profesi/'.$p['slug'].'.jpg',
                'status' => $p['status'],
                'deskripsi' => $p['deskripsi'],
                'kriteria_skill' => $p['skill'],
                'gaji' => $p['gaji'],
                'demand' => $p['demand'],
                'cara_bergabung' => $p['join'],
                'burnout_rate' => $p['burnout'],
            ]);
        }

        return $hasil;
    }

    private function seedDemoUser(): void
    {
        $user = AkunProfil::create([
            'username' => 'christinerotua',
            'email' => '5026241000@its.ac.id',
            'phone_number' => '083876543211',
            'password' => 'nilaiDPP-A',
        ]);

        foreach ([['Story Telling', 'Intermediate'], ['User Research', 'Intermediate'], ['Figma', 'Advanced']] as [$nama, $level]) {
            $user->skills()->create(['nama_skill' => $nama, 'level' => $level]);
        }

        $user->pengalaman()->createMany([
            [
                'tipe' => 'Internship', 'posisi' => 'UI/UX Designer Intern', 'perusahaan' => 'PT Teknologi Nusantara',
                'tanggal_mulai' => '2024-06-01', 'tanggal_selesai' => '2024-08-31',
                'deskripsi' => 'Merancang wireframe dan prototype aplikasi mobile, melakukan usability testing dengan 12 responden, serta menyusun design system sederhana bersama tim produk.',
            ],
            [
                'tipe' => 'Organization', 'posisi' => 'Staff Desain', 'perusahaan' => 'Himpunan Mahasiswa Sistem Informasi',
                'tanggal_mulai' => '2023-03-01', 'tanggal_selesai' => '2024-03-31',
                'deskripsi' => 'Membuat konten visual untuk media sosial himpunan dengan prinsip visual design, serta merancang branding acara tahunan.',
            ],
        ]);
    }

    /** @param  array<string, Profesi>  $profesi */
    private function seedReviews(array $profesi): void
    {
        $messi = AkunProfil::create(['username' => 'Messi', 'email' => 'messi@kariris.test', 'phone_number' => '081200000001', 'password' => 'password123']);
        $joko = AkunProfil::create(['username' => 'Joko', 'email' => 'joko@kariris.test', 'phone_number' => '081200000002', 'password' => 'password123']);

        foreach ($profesi as $p) {
            $p->reviews()->createMany([
                [
                    'akun_profil_id' => $messi->id, 'rating' => 4, 'tanggal' => '2025-09-12',
                    'jabatan_reviewer' => 'Blackrock 2000 - Present',
                    'isi_ulasan' => "Bekerja sebagai {$p->nama_profesi} itu menantang tapi memuaskan. Setiap hari ada masalah baru yang harus dipecahkan, dan hasil kerja kita benar-benar dipakai untuk mengambil keputusan penting. Tekanan deadline cukup tinggi, jadi manajemen waktu wajib dikuasai sejak awal.",
                ],
                [
                    'akun_profil_id' => $joko->id, 'rating' => 5, 'tanggal' => '2025-11-03',
                    'jabatan_reviewer' => 'Gojek 2019 - Present',
                    'isi_ulasan' => "Jenjang karier {$p->nama_profesi} sangat jelas dan kebutuhan pasarnya terus naik. Kuncinya rajin belajar tools baru dan bangun portofolio yang kuat.",
                ],
            ]);
        }
    }

    /** @param  array<string, Profesi>  $profesi */
    private function seedDayInTheLife(array $profesi): void
    {
        foreach ($profesi as $p) {
            $kategori = $p->kategori->nama_kategori;
            $p->rutinitas()->createMany(ProfesiData::rutinitas($kategori));

            $proyek = $p->proyek()->create([
                'nama_proyek' => "Proyek {$p->nama_profesi}",
                'deskripsi' => "Gambaran alur proyek yang umum dikerjakan seorang {$p->nama_profesi}.",
            ]);
            $proyek->fase()->createMany(ProfesiData::fase());
        }
    }
}
