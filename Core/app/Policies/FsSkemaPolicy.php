<?php

namespace App\Policies;

use App\Models\FsSkema;
use App\Models\User;

class FsSkemaPolicy
{
    // Semua role bisa lihat & bikin (CR minimal), sesuai matriks: FS Skema 2&3 -> Super Admin/Pengelola CRUD, Pemasaran/Manajemen CR
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'pemasaran', 'pengelola', 'manajemen']);
    }

    public function view(User $user, FsSkema $fsSkema): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'pemasaran', 'pengelola', 'manajemen']);
    }

    public function update(User $user, FsSkema $fsSkema): bool
    {
        return in_array($user->role, ['super_admin', 'pengelola']);
    }

    public function delete(User $user, FsSkema $fsSkema): bool
    {
        return in_array($user->role, ['super_admin', 'pengelola']);
    }
}