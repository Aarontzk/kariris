<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Skema mengikuti Updated Domain Model (ER-Diagram) CDR KARIRIS Kelompok 7.
 * Kolom bertanda "tambahan UI" tidak ada di ERD, tetapi dibutuhkan tampilan Hi-Fi Figma.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('akun_profil_id')->constrained('akun_profil')->cascadeOnDelete();
            $table->string('nama_skill', 60);
            $table->string('level', 20); // Basic | Intermediate | Advanced
            $table->timestamps();
        });

        Schema::create('pengalaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('akun_profil_id')->constrained('akun_profil')->cascadeOnDelete();
            $table->string('tipe', 30); // tambahan UI: Internship, Organization, ...
            $table->string('posisi', 100);
            $table->string('perusahaan', 100);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('kategori_profesi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 40); // label filter: Technology, Business, Creative
            $table->string('label', 60);         // tambahan UI: "Technology & Data"
            $table->timestamps();
        });

        Schema::create('profesi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_profesi_id')->constrained('kategori_profesi')->cascadeOnDelete();
            $table->string('nama_profesi', 80);
            $table->text('deskripsi');
            $table->text('kriteria_skill'); // dipisah koma
            // tambahan UI
            $table->string('slug', 80)->unique();
            $table->string('gambar')->nullable();
            $table->string('status', 20)->default('Popular'); // Popular | Competitive | Emerging | Stable
            $table->string('gaji', 30)->nullable();
            $table->string('demand', 20)->nullable();
            $table->text('cara_bergabung')->nullable();
            $table->unsignedTinyInteger('burnout_rate')->default(50);
            $table->timestamps();
        });

        Schema::create('bookmarked_profesi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('akun_profil_id')->constrained('akun_profil')->cascadeOnDelete();
            $table->foreignId('profesi_id')->constrained('profesi')->cascadeOnDelete();
            $table->date('tanggal_ditandai');
            $table->timestamps();
            $table->unique(['akun_profil_id', 'profesi_id']);
        });

        Schema::create('career_review', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profesi_id')->constrained('profesi')->cascadeOnDelete();
            $table->foreignId('akun_profil_id')->constrained('akun_profil')->cascadeOnDelete();
            $table->text('isi_ulasan');
            $table->unsignedTinyInteger('rating');
            $table->date('tanggal');
            $table->string('jabatan_reviewer', 80)->nullable(); // tambahan UI: "Blackrock 2000 - Present"
            $table->timestamps();
        });

        Schema::create('rutinitas_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profesi_id')->constrained('profesi')->cascadeOnDelete();
            $table->string('jam', 20); // "09.00 - 11.00"
            $table->string('kegiatan', 80);
            $table->text('deskripsi');
            $table->string('ikon', 20)->default('task'); // tambahan UI
            $table->timestamps();
        });

        Schema::create('proyek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profesi_id')->constrained('profesi')->cascadeOnDelete();
            $table->string('nama_proyek', 100);
            $table->text('deskripsi');
            $table->timestamps();
        });

        Schema::create('fase_kerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained('proyek')->cascadeOnDelete();
            $table->unsignedTinyInteger('urutan');
            $table->string('nama_fase', 60);
            $table->text('deskripsi');
            // tambahan UI
            $table->string('ringkasan', 80)->nullable();
            $table->string('durasi', 20)->nullable();
            $table->string('sprint', 20)->nullable();
            $table->string('ikon', 20)->default('task');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['fase_kerja', 'proyek', 'rutinitas_harian', 'career_review', 'bookmarked_profesi', 'profesi', 'kategori_profesi', 'pengalaman', 'skill'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
