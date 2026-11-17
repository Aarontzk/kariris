<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    public const LEVELS = ['Basic', 'Intermediate', 'Advanced'];

    protected $table = 'skill';

    protected $fillable = ['akun_profil_id', 'nama_skill', 'level'];

    public function akun(): BelongsTo
    {
        return $this->belongsTo(AkunProfil::class, 'akun_profil_id');
    }

    /** Bobot level untuk perhitungan Career Match. */
    public function bobot(): float
    {
        return match ($this->level) {
            'Advanced' => 1.0,
            'Intermediate' => 0.88,
            default => 0.65,
        };
    }
}
