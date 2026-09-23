<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AktivitasNotifikasi extends Model
{
    protected $table = 'aktivitas_notifikasis';

    protected $fillable = [
        'user_id',
        'kategori',
        'judul',
        'pesan',
        'icon',
        'url',
        'target_roles',
    ];

    protected $casts = [
        'target_roles' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope query untuk membatasi notifikasi sesuai hak akses role.
     * Jika target_roles null, maka notifikasi bersifat publik untuk semua role.
     * Jika target_roles terisi, hanya role yang tertera yang dapat melihatnya.
     */
    public function scopeForRole(Builder $query, ?string $role): Builder
    {
        if (! $role) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($role) {
            $q->whereNull('target_roles')
                ->orWhereJsonContains('target_roles', $role);
        });
    }
}
