<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriProfesi extends Model
{
    protected $table = 'kategori_profesi';

    protected $fillable = ['nama_kategori', 'label'];

    public function profesi(): HasMany
    {
        return $this->hasMany(Profesi::class);
    }
}
