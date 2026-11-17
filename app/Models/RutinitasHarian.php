<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RutinitasHarian extends Model
{
    protected $table = 'rutinitas_harian';

    public $timestamps = false;

    protected $fillable = ['profesi_id', 'jam', 'kegiatan', 'deskripsi', 'ikon'];

    public function profesi(): BelongsTo
    {
        return $this->belongsTo(Profesi::class);
    }

    /** Ikon dari kolom tambahan UI; kalau kosong ditebak dari nama kegiatan. */
    public function getIkonAttribute(?string $nilai): string
    {
        return $nilai ?: self::tebakIkon($this->kegiatan ?? '');
    }

    public static function tebakIkon(string $kegiatan): string
    {
        $k = mb_strtolower($kegiatan);
        $aturan = [
            'break' => ['lunch', 'istirahat', 'break'],
            'bug' => ['bug', 'fixing'],
            'code' => ['code', 'coding'],
            'doc' => ['dokumentasi', 'requirement', 'email', 'doc'],
            'chat' => ['review', 'feedback', 'stakeholder', 'interview'],
            'meeting' => ['standup', 'meeting', 'briefing', 'planning'],
            'design' => ['wireframe', 'desain', 'design', 'eksplorasi'],
            'search' => ['testing', 'research', 'riset'],
            'trend' => ['analisis', 'data'],
        ];
        foreach ($aturan as $ikon => $kata) {
            foreach ($kata as $w) {
                if (str_contains($k, $w)) {
                    return $ikon;
                }
            }
        }

        return 'task';
    }

    public function jamMulai(): string
    {
        return trim(explode('-', $this->jam)[0]);
    }

    /** Durasi dalam menit dari string "09.00 - 11.00". */
    public function durasiMenit(): int
    {
        $bagian = array_map('trim', explode('-', $this->jam));
        if (count($bagian) !== 2) {
            return 0;
        }
        $keMenit = function (string $jam): int {
            [$h, $m] = array_pad(array_map('intval', preg_split('/[.:]/', $jam)), 2, 0);

            return $h * 60 + $m;
        };

        return max(0, $keMenit($bagian[1]) - $keMenit($bagian[0]));
    }
}
