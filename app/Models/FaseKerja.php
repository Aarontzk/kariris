<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaseKerja extends Model
{
    protected $table = 'fase_kerja';

    protected $fillable = ['proyek_id', 'urutan', 'nama_fase', 'deskripsi', 'ringkasan', 'durasi', 'sprint', 'ikon'];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }

    /** "1 minggu" -> 7, "3 hari" -> 3 */
    public function durasiHari(): int
    {
        $angka = (int) $this->durasi;

        return str_contains((string) $this->durasi, 'minggu') ? $angka * 7 : $angka;
    }
}
