<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * nama_kategori di Supabase berbentuk panjang ("Technology & Data").
 * Chip filter di Figma memakai nama singkat ("Technology", "Business", "Creative").
 */
class KategoriProfesi extends Model
{
    public $timestamps = false;

    protected $table = 'kategori_profesi';

    protected $fillable = ['nama_kategori'];

    public function profesi(): HasMany
    {
        return $this->hasMany(Profesi::class);
    }

    /** Label lengkap untuk kartu/hero, contoh "Technology & Data". */
    public function getLabelAttribute(): string
    {
        return $this->nama_kategori;
    }

    /** Nama chip filter: "Design & Creative" -> "Creative", lainnya kata pertama. */
    public function singkat(): string
    {
        if (str_contains($this->nama_kategori, 'Creative')) {
            return 'Creative';
        }

        return strtok($this->nama_kategori, ' &') ?: $this->nama_kategori;
    }
}
