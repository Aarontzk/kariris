<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerReview extends Model
{
    protected $table = 'career_review';

    protected $fillable = ['profesi_id', 'akun_profil_id', 'isi_ulasan', 'rating', 'tanggal', 'jabatan_reviewer'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function profesi(): BelongsTo
    {
        return $this->belongsTo(Profesi::class);
    }

    public function akun(): BelongsTo
    {
        return $this->belongsTo(AkunProfil::class, 'akun_profil_id');
    }
}
