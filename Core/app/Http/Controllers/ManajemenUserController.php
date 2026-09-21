<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\User;
use App\Services\UserInvitationService;
use Illuminate\Http\Request;

class ManajemenUserController extends Controller
{
    public function __construct(protected UserInvitationService $invitationService) {}

    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('manajemen-user.index', [
            'users' => $users,
            'totalTerdaftar' => User::count(),
            'totalAktif' => User::where('status', UserStatus::Active)->count(),
            'totalNonaktif' => User::where('status', UserStatus::Nonaktif)->count(),
            'totalPending' => User::where('status', UserStatus::Pending)->count(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:invite,direct',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:super_admin,pemasaran,pengelola,manajemen',
            'password' => 'nullable|min:8',
        ]);

        if ($request->mode === 'invite') {
            $this->invitationService->invite($request->name, $request->email, $request->role, $request->user());

            return back()->with('success', 'Undangan berhasil dikirim ke ' . $request->email);
        }

        $result = $this->invitationService->createDirectly(
            $request->name,
            $request->email,
            $request->role,
            $request->password,
            $request->user()
        );

        return back()->with([
            'success' => 'Akun berhasil dibuat langsung.',
            'generated_account' => [
                'email' => $result['user']->email,
                'password' => $result['plain_password'],
            ],
        ]);
    }

    public function resendInvitation(User $user)
    {
        abort_unless($user->status === UserStatus::Pending, 400, 'User ini sudah aktif.');

        $this->invitationService->resend($user);

        return back()->with('success', 'Undangan dikirim ulang.');
    }

    public function toggleStatus(User $user)
    {
        abort_if($user->status === UserStatus::Pending, 400, 'User belum aktivasi, tidak bisa diubah statusnya.');
        abort_if($user->id === auth()->id(), 400, 'Tidak bisa menonaktifkan akun sendiri.');

        $user->update([
            'status' => $user->status === UserStatus::Active ? UserStatus::Nonaktif : UserStatus::Active,
        ]);

        return back()->with('success', 'Status user diperbarui.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:super_admin,pemasaran,pengelola,manajemen',
        ]);

        $user->update($request->only('name', 'role'));

        return back()->with('success', 'Data user diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 400, 'Tidak bisa menghapus akun sendiri.');

        $namaUser = $user->name;
        $user->delete();

        return back()->with('success', "User \"{$namaUser}\" berhasil dihapus.");
    }
}