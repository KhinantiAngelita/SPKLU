<?php

namespace App\Policies;

use App\Models\Pengajuan;
use App\Models\User;

class PengajuanPolicy
{
    // Semua role bisa lihat, tapi Pemasaran & Manajemen cuma View
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'pemasaran', 'pengelola', 'manajemen']);
    }

    public function view(User $user, Pengajuan $pengajuan): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'pengelola']);
    }

    public function update(User $user, Pengajuan $pengajuan): bool
    {
        return in_array($user->role, ['super_admin', 'pengelola']);
    }

    public function delete(User $user, Pengajuan $pengajuan): bool
    {
        return $user->role === 'super_admin';
    }

    // Custom ability buat tombol ubah status/approve di view
    public function approve(User $user, Pengajuan $pengajuan): bool
    {
        return in_array($user->role, ['super_admin', 'pengelola']);
    }
}