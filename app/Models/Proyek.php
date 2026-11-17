<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyek extends Model
{
    protected $table = 'proyek';

    protected $fillable = ['profesi_id', 'nama_proyek', 'deskripsi'];

    public function profesi(): BelongsTo
    {
        return $this->belongsTo(Profesi::class);
    }

    public function fase(): HasMany
    {
        return $this->hasMany(FaseKerja::class)->orderBy('urutan');
    }
}
