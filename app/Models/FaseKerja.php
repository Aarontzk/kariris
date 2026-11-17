<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaseKerja extends Model
{
    protected $table = 'fase_kerja';

    public $timestamps = false;

    protected $fillable = ['proyek_id', 'urutan', 'nama_fase', 'deskripsi', 'ringkasan', 'durasi', 'sprint', 'ikon'];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }

    public function getIkonAttribute(?string $nilai): string
    {
        return $nilai ?: (['doc', 'design', 'code', 'bug', 'rocket'][($this->urutan ?? 1) - 1] ?? 'task');
    }

    /** "1 minggu" -> 7, "3 hari" -> 3 */
    public function durasiHari(): int
    {
        $angka = (int) $this->durasi;

        return str_contains((string) $this->durasi, 'minggu') ? $angka * 7 : $angka;
    }
}
