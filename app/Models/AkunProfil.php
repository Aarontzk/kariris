<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AkunProfil extends Authenticatable
{
    use Notifiable;

    protected $table = 'akun_profil';

    protected $fillable = ['username', 'email', 'phone_number', 'password', 'foto', 'username_diubah_pada'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'username_diubah_pada' => 'datetime',
        ];
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->orderBy('id');
    }

    public function pengalaman(): HasMany
    {
        return $this->hasMany(Pengalaman::class)->orderByDesc('tanggal_mulai');
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(Profesi::class, 'bookmarked_profesi')
            ->withPivot('tanggal_ditandai')
            ->withTimestamps();
    }

    /** Batas ganti username: sekali per 30 hari (sesuai copy popup Change Username). */
    public function bisaGantiUsername(): bool
    {
        return $this->username_diubah_pada === null
            || $this->username_diubah_pada->lte(now()->subDays(30));
    }

    public function fotoUrl(): string
    {
        return asset($this->foto ?: 'img/avatar.png');
    }
}
