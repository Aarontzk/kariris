<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profesi extends Model
{
    protected $table = 'profesi';

    protected $fillable = [
        'kategori_profesi_id', 'nama_profesi', 'deskripsi', 'kriteria_skill',
        'slug', 'gambar', 'status', 'gaji', 'demand', 'cara_bergabung', 'burnout_rate',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriProfesi::class, 'kategori_profesi_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CareerReview::class)->latest('tanggal');
    }

    public function rutinitas(): HasMany
    {
        return $this->hasMany(RutinitasHarian::class)->orderBy('id');
    }

    public function proyek(): HasMany
    {
        return $this->hasMany(Proyek::class)->orderBy('id');
    }

    /** @return list<string> */
    public function daftarSkill(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $this->kriteria_skill))));
    }

    public function ratingRata(): float
    {
        $avg = $this->reviews->avg('rating');

        return $avg ? round((float) $avg, 1) : 0.0;
    }

    public function gambarUrl(): string
    {
        return asset($this->gambar ?: 'img/profesi/data-analyst.jpg');
    }

    public function scopeCari(Builder $q, ?string $keyword): Builder
    {
        $keyword = trim((string) $keyword);
        if ($keyword === '') {
            return $q;
        }
        $like = '%'.mb_strtolower($keyword).'%';

        return $q->where(function (Builder $w) use ($like) {
            $w->whereRaw('LOWER(nama_profesi) LIKE ?', [$like])
                ->orWhereRaw('LOWER(kriteria_skill) LIKE ?', [$like]);
        });
    }

    public function scopeKategori(Builder $q, ?string $kategori): Builder
    {
        if (! $kategori || $kategori === 'All') {
            return $q;
        }

        return $q->whereHas('kategori', fn (Builder $k) => $k->where('nama_kategori', $kategori));
    }
}
