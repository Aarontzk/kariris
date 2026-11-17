<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Idempoten: di Supabase tabel akun_profil sudah dibuat tim, jadi hanya tabel yang belum ada yang dibuat.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('akun_profil')) {
            Schema::create('akun_profil', function (Blueprint $table) {
                $table->id();
                $table->string('username', 50)->unique();
                $table->string('email')->unique();
                $table->string('phone_number', 20)->nullable();
                $table->string('password');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('akun_profil');
    }
};
