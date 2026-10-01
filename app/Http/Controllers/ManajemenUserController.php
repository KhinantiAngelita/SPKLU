<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\UserInvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManajemenUserController extends Controller
{
    public function __construct(protected UserInvitationService $invitationService) {}

    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn ($q) => $q->where(function ($sub) use ($request) {
                $sub->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            }))
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('up3'), fn ($q) => $q->where('up3', $request->up3))
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
            'up3' => 'nullable|string|in:'.implode(',', User::DAFTAR_UP3),
            'password' => 'nullable|min:8',
        ]);

        if ($request->mode === 'invite') {
            $this->invitationService->invite($request->name, $request->email, $request->role, $request->user(), $request->up3);

            return back()->with('success', 'Undangan berhasil dikirim ke '.$request->email);
        }

        $result = $this->invitationService->createDirectly(
            $request->name,
            $request->email,
            $request->role,
            $request->password,
            $request->user(),
            $request->up3
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

        AuditLog::create([
            'user_id' => auth()->id(),
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'action' => 'resend_invitation',
            'new_values' => ['email' => $user->email, 'sent_at' => now()->toDateTimeString()],
        ]);

        return back()->with('success', 'Undangan dikirim ulang.');
    }

    public function toggleStatus(User $user)
    {
        abort_if($user->status === UserStatus::Pending, 400, 'User belum aktivasi, tidak bisa diubah statusnya.');
        abort_if($user->id === auth()->id(), 400, 'Tidak bisa menonaktifkan akun sendiri.');

        $oldStatus = $user->status->value;
        $newStatus = $user->status === UserStatus::Active ? UserStatus::Nonaktif : UserStatus::Active;

        $user->update([
            'status' => $newStatus,
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'action' => 'toggle_status',
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $newStatus->value],
        ]);

        return back()->with('success', 'Status user diperbarui.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:super_admin,pemasaran,pengelola,manajemen',
            'up3' => 'nullable|string|in:'.implode(',', User::DAFTAR_UP3),
        ]);

        $oldValues = $user->only(['name', 'role', 'up3']);
        $user->update($request->only('name', 'role', 'up3'));

        AuditLog::create([
            'user_id' => auth()->id(),
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'action' => 'updated_user',
            'old_values' => $oldValues,
            'new_values' => $user->only(['name', 'role', 'up3']),
        ]);

        return back()->with('success', 'Data user diperbarui.');
    }

    public function detail(User $user)
    {
        $loginLogs = AuditLog::where('auditable_type', User::class)
            ->where('auditable_id', $user->id)
            ->whereIn('action', ['login', 'logout'])
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'ip' => $log->new_values['ip'] ?? '127.0.0.1',
                    'user_agent' => $log->new_values['user_agent'] ?? null,
                    'device' => Str::contains($log->new_values['user_agent'] ?? '', ['Mobile', 'Android', 'iPhone']) ? 'Mobile' : 'Desktop',
                    'waktu' => $log->created_at->translatedFormat('d M Y, H:i'),
                    'time_ago' => $log->created_at->diffForHumans(),
                ];
            });

        // Fallback jika belum ada baris audit log login tapi user sudah memiliki last_login_at
        if ($loginLogs->isEmpty() && $user->last_login_at) {
            $loginLogs = collect([[
                'id' => 0,
                'action' => 'login',
                'ip' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'Web Browser',
                'device' => 'Desktop',
                'waktu' => $user->last_login_at->translatedFormat('d M Y, H:i'),
                'time_ago' => $user->last_login_at->diffForHumans(),
            ]]);
        }

        $changeLogs = AuditLog::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->whereNotIn('action', ['login', 'logout']);
        })
            ->orWhere(function ($q) use ($user) {
                $q->where('auditable_type', User::class)
                    ->where('auditable_id', $user->id)
                    ->whereNotIn('action', ['login', 'logout']);
            })
            ->with('user')
            ->latest()
            ->take(30)
            ->get()
            ->map(function ($log) use ($user) {
                $modelName = class_basename($log->auditable_type);
                $actionLabel = match ($log->action) {
                    'created' => 'Menambahkan data',
                    'updated' => 'Mengubah data',
                    'deleted' => 'Menghapus data',
                    'toggle_status' => 'Mengubah status akun',
                    'updated_user' => 'Memperbarui profil akun',
                    'created_user' => 'Membuat akun pengguna',
                    'resend_invitation' => 'Mengirim ulang undangan',
                    default => ucfirst(str_replace('_', ' ', $log->action)),
                };

                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'action_label' => $actionLabel,
                    'model' => $modelName,
                    'is_actor' => $log->user_id === $user->id,
                    'actor_name' => $log->user?->name ?? 'Sistem',
                    'old_values' => $log->old_values,
                    'new_values' => $log->new_values,
                    'waktu' => $log->created_at->translatedFormat('d M Y, H:i'),
                    'time_ago' => $log->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'role_label' => ucwords(str_replace('_', ' ', $user->role)),
                'up3' => $user->up3 ?? '—',
                'status' => $user->status->value,
                'status_label' => $user->status->value === 'active' ? 'Aktif' : ($user->status->value === 'pending' ? 'Menunggu Aktivasi' : 'Nonaktif'),
                'initials' => strtoupper(substr($user->name, 0, 2)),
                'is_current_user' => $user->id === auth()->id(),
                'created_at' => $user->created_at ? $user->created_at->translatedFormat('d M Y, H:i') : '—',
                'created_at_human' => $user->created_at ? $user->created_at->diffForHumans() : '—',
                'last_login_at' => $user->last_login_at ? $user->last_login_at->translatedFormat('d M Y, H:i') : null,
                'last_login_human' => $user->last_login_at ? $user->last_login_at->diffForHumans() : null,
            ],
            'login_logs' => $loginLogs,
            'change_logs' => $changeLogs,
        ]);
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 400, 'Tidak bisa menghapus akun sendiri.');

        $namaUser = $user->name;
        $user->delete();

        return back()->with('success', "User \"{$namaUser}\" berhasil dihapus.");
    }
}
