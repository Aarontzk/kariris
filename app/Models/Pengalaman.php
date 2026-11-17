<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengalaman extends Model
{
    public const TIPE = ['Internship', 'Organization', 'Freelance', 'Part-Time', 'Full-Time', 'Volunteer'];

    protected $table = 'pengalaman';

    protected $fillable = ['akun_profil_id', 'tipe', 'posisi', 'perusahaan', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi'];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function akun(): BelongsTo
    {
        return $this->belongsTo(AkunProfil::class, 'akun_profil_id');
    }

    /** Contoh: "Jun - Agu 2024", "2023 - 2024", "Feb 2026 - Jun 2026". */
    public function periode(): string
    {
        $mulai = $this->tanggal_mulai->locale('id');
        if ($this->tanggal_selesai === null) {
            return $mulai->translatedFormat('M Y').' - Sekarang';
        }
        $selesai = $this->tanggal_selesai->locale('id');

        if ($mulai->year === $selesai->year) {
            return $mulai->translatedFormat('M').' - '.$selesai->translatedFormat('M Y');
        }

        return $mulai->translatedFormat('M Y').' - '.$selesai->translatedFormat('M Y');
    }
}
