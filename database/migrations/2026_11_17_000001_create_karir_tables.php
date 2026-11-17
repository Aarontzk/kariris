<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Skema inti mengikuti tabel Supabase tim KARIRIS (ERD CDR Kelompok 7).
 * Idempoten: tabel yang sudah ada di Supabase dilewati, jadi data tim tidak tersentuh.
 * Kolom tambahan khusus tampilan Figma ada di migration add_ui_columns.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->buat('skill', function (Blueprint $t) {
            $t->id();
            $t->foreignId('akun_profil_id')->constrained('akun_profil')->cascadeOnDelete();
            $t->string('nama_skill', 100);
            $t->string('level', 20); // Basic | Intermediate | Advanced
            $t->timestamps();
            $t->unique(['akun_profil_id', 'nama_skill']);
        });

        $this->buat('pengalaman', function (Blueprint $t) {
            $t->id();
            $t->foreignId('akun_profil_id')->constrained('akun_profil')->cascadeOnDelete();
            $t->string('tipe', 20); // Internship | Organization | Freelance | Part-time | Full-time | Volunteer
            $t->string('posisi', 150);
            $t->string('perusahaan', 150);
            $t->date('tanggal_mulai');
            $t->date('tanggal_selesai')->nullable();
            $t->text('deskripsi')->nullable();
            $t->timestamps();
        });

        $this->buat('kategori_profesi', function (Blueprint $t) {
            $t->id();
            $t->string('nama_kategori', 100)->unique();
        });

        $this->buat('profesi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('kategori_profesi_id')->constrained('kategori_profesi')->restrictOnDelete();
            $t->string('nama_profesi', 150)->unique();
            $t->text('deskripsi');
            $t->text('kriteria_skill'); // dipisah koma
            $t->string('status', 20)->nullable(); // Popular | Competitive | Emerging | Stable
            $t->decimal('gaji_min_juta', 6, 1)->nullable();
            $t->decimal('gaji_max_juta', 6, 1)->nullable();
            $t->string('tingkat_permintaan', 20)->nullable(); // High | Steady | ...
            $t->decimal('rating', 2, 1)->nullable();
            $t->text('cara_bergabung')->nullable();
            $t->smallInteger('burnout_rate')->nullable();
            $t->timestamps();
        });

        $this->buat('bookmarked_profesi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('akun_profil_id')->constrained('akun_profil')->cascadeOnDelete();
            $t->foreignId('profesi_id')->constrained('profesi')->cascadeOnDelete();
            $t->date('tanggal_ditandai');
            $t->unique(['akun_profil_id', 'profesi_id']);
        });

        $this->buat('career_review', function (Blueprint $t) {
            $t->id();
            $t->foreignId('profesi_id')->constrained('profesi')->cascadeOnDelete();
            $t->foreignId('akun_profil_id')->constrained('akun_profil')->cascadeOnDelete();
            $t->text('isi_ulasan');
            $t->smallInteger('rating');
            $t->date('tanggal');
            $t->timestamps();
        });

        $this->buat('rutinitas_harian', function (Blueprint $t) {
            $t->id();
            $t->foreignId('profesi_id')->constrained('profesi')->cascadeOnDelete();
            $t->string('jam', 20); // "09.00 - 11.00"
            $t->string('kegiatan', 150);
            $t->text('deskripsi')->nullable();
        });

        $this->buat('proyek', function (Blueprint $t) {
            $t->id();
            $t->foreignId('profesi_id')->constrained('profesi')->cascadeOnDelete();
            $t->string('nama_proyek', 150);
            $t->text('deskripsi')->nullable();
        });

        $this->buat('fase_kerja', function (Blueprint $t) {
            $t->id();
            $t->foreignId('proyek_id')->constrained('proyek')->cascadeOnDelete();
            $t->smallInteger('urutan');
            $t->string('nama_fase', 150);
            $t->text('deskripsi')->nullable();
            $t->unique(['proyek_id', 'urutan']);
        });
    }

    public function down(): void
    {
        foreach (['fase_kerja', 'proyek', 'rutinitas_harian', 'career_review', 'bookmarked_profesi', 'profesi', 'kategori_profesi', 'pengalaman', 'skill'] as $t) {
            Schema::dropIfExists($t);
        }
    }

    private function buat(string $tabel, Closure $definisi): void
    {
        if (! Schema::hasTable($tabel)) {
            Schema::create($tabel, $definisi);
        }
    }
};
