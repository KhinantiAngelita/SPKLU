<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Probabilitas;

class ProbabilitasPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // semua role bisa lihat
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'pengelola']);
    }

    public function update(User $user, Probabilitas $probabilitas): bool
    {
        return in_array($user->role, ['super_admin', 'pengelola']);
    }

    public function delete(User $user, Probabilitas $probabilitas): bool
    {
        return $user->role === 'super_admin';
    }
}