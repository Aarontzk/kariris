<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kolom tambahan (semua nullable) yang dibutuhkan tampilan Hi-Fi Figma tapi tidak ada di ERD.
 * Hanya menambah kolom yang belum ada, tidak mengubah/menghapus kolom milik tim.
 */
return new class extends Migration
{
    private const KOLOM = [
        'akun_profil' => ['foto', 'username_diubah_pada', 'remember_token'],
        'profesi' => ['slug', 'gambar'],
        'career_review' => ['jabatan_reviewer'],
        'rutinitas_harian' => ['ikon'],
        'fase_kerja' => ['ringkasan', 'durasi', 'sprint', 'ikon'],
    ];

    public function up(): void
    {
        $this->tambah('akun_profil', 'foto', fn (Blueprint $t) => $t->string('foto')->nullable());
        $this->tambah('akun_profil', 'username_diubah_pada', fn (Blueprint $t) => $t->timestamp('username_diubah_pada')->nullable());
        $this->tambah('akun_profil', 'remember_token', fn (Blueprint $t) => $t->rememberToken());

        $this->tambah('profesi', 'slug', fn (Blueprint $t) => $t->string('slug', 160)->nullable()->unique());
        $this->tambah('profesi', 'gambar', fn (Blueprint $t) => $t->string('gambar')->nullable());

        $this->tambah('career_review', 'jabatan_reviewer', fn (Blueprint $t) => $t->string('jabatan_reviewer', 80)->nullable());

        $this->tambah('rutinitas_harian', 'ikon', fn (Blueprint $t) => $t->string('ikon', 20)->nullable());

        $this->tambah('fase_kerja', 'ringkasan', fn (Blueprint $t) => $t->string('ringkasan', 120)->nullable());
        $this->tambah('fase_kerja', 'durasi', fn (Blueprint $t) => $t->string('durasi', 20)->nullable());
        $this->tambah('fase_kerja', 'sprint', fn (Blueprint $t) => $t->string('sprint', 20)->nullable());
        $this->tambah('fase_kerja', 'ikon', fn (Blueprint $t) => $t->string('ikon', 20)->nullable());
    }

    public function down(): void
    {
        foreach (self::KOLOM as $tabel => $kolom) {
            $ada = array_values(array_filter($kolom, fn ($k) => Schema::hasColumn($tabel, $k)));
            if ($ada !== []) {
                Schema::table($tabel, fn (Blueprint $t) => $t->dropColumn($ada));
            }
        }
    }

    private function tambah(string $tabel, string $kolom, Closure $definisi): void
    {
        if (! Schema::hasColumn($tabel, $kolom)) {
            Schema::table($tabel, $definisi);
        }
    }
};
