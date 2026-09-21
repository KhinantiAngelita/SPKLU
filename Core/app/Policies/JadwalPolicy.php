<?php

namespace App\Policies;

use App\Models\Jadwal;
use App\Models\User;

class JadwalPolicy
{
    // Semua role bisa lihat, Manajemen cuma "View jadwal" (read-only)
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'pemasaran', 'pengelola', 'manajemen']);
    }

    public function view(User $user, Jadwal $jadwal): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'pemasaran', 'pengelola']);
    }

    public function update(User $user, Jadwal $jadwal): bool
    {
        return in_array($user->role, ['super_admin', 'pemasaran', 'pengelola']);
    }

    public function delete(User $user, Jadwal $jadwal): bool
    {
        return $user->role === 'super_admin';
    }
}