<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RutinitasHarian extends Model
{
    protected $table = 'rutinitas_harian';

    protected $fillable = ['profesi_id', 'jam', 'kegiatan', 'deskripsi', 'ikon'];

    public function profesi(): BelongsTo
    {
        return $this->belongsTo(Profesi::class);
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
