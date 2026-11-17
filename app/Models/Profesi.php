<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Profesi extends Model
{
    protected $table = 'profesi';

    protected $fillable = [
        'kategori_profesi_id', 'nama_profesi', 'deskripsi', 'kriteria_skill',
        'status', 'gaji_min_juta', 'gaji_max_juta', 'tingkat_permintaan', 'rating',
        'cara_bergabung', 'burnout_rate', 'slug', 'gambar',
    ];

    protected function casts(): array
    {
        return [
            'gaji_min_juta' => 'float',
            'gaji_max_juta' => 'float',
            'rating' => 'float',
            'burnout_rate' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        // slug & gambar adalah kolom tambahan UI: diisi otomatis dari nama_profesi.
        static::saving(function (Profesi $p) {
            $p->slug = $p->slug ?: self::buatSlug($p->nama_profesi);
            if (! $p->gambar && is_file(public_path('img/profesi/'.$p->slug.'.jpg'))) {
                $p->gambar = 'img/profesi/'.$p->slug.'.jpg';
            }
        });
    }

    public static function buatSlug(string $nama): string
    {
        return Str::slug($nama);
    }

    /** "IDR 6-15 M" dari gaji_min_juta / gaji_max_juta. */
    public function getGajiAttribute(): string
    {
        $fmt = fn (?float $v) => $v === null ? '?' : rtrim(rtrim(number_format($v, 1, '.', ''), '0'), '.');
        if ($this->gaji_min_juta === null && $this->gaji_max_juta === null) {
            return '-';
        }

        return 'IDR '.$fmt($this->gaji_min_juta).'-'.$fmt($this->gaji_max_juta).' M';
    }

    public function getDemandAttribute(): string
    {
        return $this->tingkat_permintaan ?: '-';
    }

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

    /** Rating kurasi tim (kolom rating); kalau kosong pakai rata-rata review. */
    public function ratingRata(): float
    {
        if ($this->rating !== null) {
            return round((float) $this->rating, 1);
        }
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
        $ids = KategoriProfesi::all()
            ->filter(fn (KategoriProfesi $k) => $k->singkat() === $kategori || $k->nama_kategori === $kategori)
            ->modelKeys();

        return $q->whereIn('kategori_profesi_id', $ids);
    }
}
