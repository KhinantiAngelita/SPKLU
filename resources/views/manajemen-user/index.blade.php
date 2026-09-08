@extends('layouts.app')

@section('breadcrumb', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')

<style>
    .mu-page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px; }
    .mu-subtitle { color:#64748B; margin:0; font-size:13.5px; }

    .mu-btn { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:9px; font-size:13.3px; font-weight:700; padding:10px 20px; cursor:pointer; transition:all .15s ease; }
    .mu-btn svg { width:15px; height:15px; stroke-width:2.1; }
    .mu-btn-primary { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; box-shadow:0 2px 10px rgba(2,62,138,.25); }
    .mu-btn-primary:hover { transform:translateY(-1px); }
    .mu-btn-outline { background:#fff; color:#1E293B; border:1px solid #e2e8f0; }

    .mu-card-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:18px; margin-bottom:22px; }
    .mu-card { background:#fff; border-radius:16px; padding:20px 22px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05); display:flex; align-items:center; gap:16px; transition:transform .18s ease, box-shadow .18s ease; }
    .mu-card:hover { transform:translateY(-2px); box-shadow:0 4px 8px rgba(15,23,42,.06), 0 14px 28px rgba(15,23,42,.09); }
    .mu-card-icon { width:48px; height:48px; border-radius:13px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:19px; font-weight:800; }
    .mu-card-icon svg { width:22px; height:22px; stroke-width:2; }
    .mu-card-value { font-size:24px; font-weight:800; margin:0; color:#0f172a; }
    .mu-card-label { font-size:12px; font-weight:600; color:#94a3b8; margin:2px 0 0; text-transform:uppercase; letter-spacing:.03em; }

    .mu-table { width:100%; border-collapse:collapse; }
    .mu-table thead th { background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; padding:13px 20px; border-bottom:1px solid #eef1f5; }
    .mu-table td { padding:14px 20px; font-size:13.3px; border-bottom:1px solid #f5f7fa; }
    .mu-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .mu-table tbody tr:hover { background:rgba(0,129,171,.04); }
    .mu-table tbody tr:last-child td { border-bottom:none; }
    .mu-empty { text-align:center; padding:44px 20px; color:#94a3b8; font-size:13.5px; }

    .mu-input-search { padding:10px 16px; border-radius:9px; border:1px solid #e2e8f0; font-size:13.3px; width:280px; margin:0 24px 18px; }
    .mu-input-search:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }

    .mu-avatar { width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#FFC629,#ffab00); color:#023E8A; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:800; }
    .mu-badge-role { display:inline-flex; padding:5px 12px; border-radius:999px; font-size:11px; font-weight:700; }
    .mu-badge-super_admin { background:rgba(2,62,138,.12); color:#023E8A; }
    .mu-badge-pemasaran { background:rgba(0,129,171,.12); color:#0081AB; }
    .mu-badge-pengelola { background:rgba(46,158,91,.12); color:#2E9E5B; }
    .mu-badge-manajemen { background:#eef2f7; color:#64748B; }

    .mu-badge-pending { display:inline-flex; padding:5px 12px; border-radius:999px; font-size:11px; font-weight:700; background:rgba(232,163,23,.14); color:#92660f; }

    .mu-toggle { width:40px; height:22px; border-radius:999px; background:#cbd5e1; border:none; position:relative; cursor:pointer; transition:background .2s ease; }
    .mu-toggle.on { background:#2E9E5B; }
    .mu-toggle:disabled { opacity:.5; cursor:not-allowed; }
    .mu-toggle-knob { position:absolute; top:2px; left:2px; width:18px; height:18px; border-radius:50%; background:#fff; transition:left .2s ease; }
    .mu-toggle.on .mu-toggle-knob { left:20px; }

    .mu-link-btn { background:none; border:none; color:#0081AB; font-size:12px; font-weight:700; cursor:pointer; padding:0; margin-left:10px; }

    .mu-action-group { display:flex; gap:6px; }
    .mu-icon-btn { width:32px; height:32px; border-radius:8px; border:none; background:rgba(255,198,41,.15); color:#92660f; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; transition:background .15s ease; }
    .mu-icon-btn svg { width:14px; height:14px; }
    .mu-icon-btn:hover { background:rgba(255,198,41,.25); }
    .mu-del-btn { width:32px; height:32px; border-radius:8px; border:none; background:rgba(192,57,43,.1); color:#C0392B; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; transition:background .15s ease; }
    .mu-del-btn svg { width:14px; height:14px; }
    .mu-del-btn:hover { background:rgba(192,57,43,.18); }
    .mu-del-btn:disabled { opacity:.4; cursor:not-allowed; }

    .mu-modal-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(2px); align-items:center; justify-content:center; z-index:50; }
    .mu-modal-overlay.show { display:flex; }
    .mu-modal { background:#fff; border-radius:18px; padding:0; width:460px; max-width:92vw; box-shadow:0 24px 60px rgba(0,0,0,.25); overflow:hidden; }
    .mu-modal-header { background:linear-gradient(135deg, rgba(2,62,138,.06), rgba(0,129,171,.09)); padding:20px 24px; }
    .mu-modal-header h3 { margin:0; font-size:16.5px; font-weight:800; color:#023E8A; }
    .mu-modal-body { padding:20px 24px; }
    .mu-modal-body label { display:block; font-size:12.5px; font-weight:700; color:#475569; margin:14px 0 5px; }
    .mu-modal-body label:first-child { margin-top:0; }
    .mu-modal-body input, .mu-modal-body select { width:100%; padding:10px 12px; border-radius:8px; border:1px solid #e2e8f0; font-size:13.5px; }
    .mu-modal-body input:focus, .mu-modal-body select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }

    .mu-tabs { display:flex; border-bottom:1px solid #eef1f5; }
    .mu-tab { flex:1; padding:12px; background:none; border:none; border-bottom:2px solid transparent; font-size:13.3px; font-weight:700; color:#94a3b8; cursor:pointer; }
    .mu-tab.active { color:#0081AB; border-bottom-color:#0081AB; }

    .mu-hint { font-size:11.5px; color:#94a3b8; margin-top:6px; }
</style>

<div class="mu-page-header">
    <p class="mu-subtitle">Kelola akun dan hak akses pengguna sistem SPKLU</p>
    <button class="mu-btn mu-btn-primary" onclick="document.getElementById('modal-tambah-user').classList.add('show')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah User
    </button>
</div>

<div class="mu-card-grid">
    <div class="mu-card">
        <div class="mu-card-icon" style="background:linear-gradient(135deg, rgba(2,62,138,.12), rgba(0,129,171,.12)); color:#023E8A;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <div><p class="mu-card-value">{{ $totalTerdaftar }}</p><p class="mu-card-label">Total Terdaftar</p></div>
    </div>
    <div class="mu-card">
        <div class="mu-card-icon" style="background:linear-gradient(135deg, rgba(46,158,91,.14), rgba(46,158,91,.06)); color:#2E9E5B;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
        <div><p class="mu-card-value">{{ $totalAktif }}</p><p class="mu-card-label">Aktif</p></div>
    </div>
    <div class="mu-card">
        <div class="mu-card-icon" style="background:linear-gradient(135deg, rgba(232,163,23,.15), rgba(232,163,23,.06)); color:#E8A317;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        <div><p class="mu-card-value">{{ $totalPending }}</p><p class="mu-card-label">Menunggu Aktivasi</p></div>
    </div>
</div>

<div class="surface-card">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
            <div><h2>Daftar Pengguna</h2></div>
        </div>
    </div>

    <form method="GET" style="padding:18px 24px 0;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="mu-input-search" style="margin:0;">
    </form>

    <table class="mu-table" style="margin-top:16px;">
        <thead>
            <tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Terakhir Login</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                <td>
                    <div class="row-icon-cell">
                        <div class="mu-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                        <span style="font-weight:600;">{{ $user->name }}</span>
                    </div>
                </td>
                <td>{{ $user->email }}</td>
                <td><span class="mu-badge-role mu-badge-{{ $user->role }}">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span></td>
                <td>
                    @if ($user->status->value === 'pending')
                        <span class="mu-badge-pending">Menunggu Aktivasi</span>
                        <form method="POST" action="{{ route('manajemen-user.resend-invitation', $user) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="mu-link-btn">Kirim Ulang</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('manajemen-user.toggle-status', $user) }}"
                              data-confirm="Status {{ $user->name }} akan diubah menjadi {{ $user->status->value === 'active' ? 'Nonaktif' : 'Aktif' }}."
                              data-confirm-title="Ubah status user ini?">
                            @csrf
                            <button type="submit" class="mu-toggle {{ $user->status->value === 'active' ? 'on' : '' }}"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <span class="mu-toggle-knob"></span>
                            </button>
                        </form>
                    @endif
                </td>
                <td>{{ $user->last_login_at?->translatedFormat('d F Y') ?? '—' }}</td>
                <td>
                    <div class="mu-action-group">
                        <button class="mu-icon-btn" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg></button>

                        <form method="POST" action="{{ route('manajemen-user.destroy', $user) }}"
                              data-confirm="User &quot;{{ $user->name }}&quot; ({{ $user->email }}) akan dihapus permanen dan tidak bisa dibatalkan."
                              data-confirm-title="Hapus user ini?"
                              data-confirm-type="danger">
                            @csrf @method('DELETE')
                            <button type="submit" class="mu-del-btn" title="Hapus" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
                <tr><td colspan="6" class="mu-empty">Belum ada user.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="padding:16px 24px;">{{ $users->links() }}</div>
</div>

{{-- Modal Tambah User --}}
<div id="modal-tambah-user" class="mu-modal-overlay">
    <div class="mu-modal">
        <div class="mu-modal-header"><h3>Tambah User Baru</h3></div>

        <div class="mu-tabs">
            <button type="button" class="mu-tab active" data-tab="invite" onclick="switchUserTab('invite')">Undang via Email</button>
            <button type="button" class="mu-tab" data-tab="direct" onclick="switchUserTab('direct')">Buat Akun Langsung</button>
        </div>

        <form method="POST" action="{{ route('manajemen-user.store') }}" class="mu-modal-body">
            @csrf
            <input type="hidden" name="mode" id="mode-input" value="invite">

            <label>Nama</label>
            <input type="text" name="name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Role</label>
            <select name="role" required>
                <option value="super_admin">Super Admin</option>
                <option value="pemasaran">Pemasaran</option>
                <option value="pengelola">Pengelola</option>
                <option value="manajemen">Manajemen</option>
            </select>

            <p id="panel-invite" class="mu-hint">User akan menerima email undangan, dan bisa memilih aktivasi lewat Google atau kode OTP.</p>

            <div id="panel-direct" style="display:none;">
                <label>Password (kosongkan untuk generate otomatis)</label>
                <input type="password" name="password" minlength="8" placeholder="Otomatis dibuatkan kalau kosong">
                <p class="mu-hint">Akun langsung aktif. Saat login pertama, user diminta verifikasi OTP lalu wajib buat password baru.</p>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:22px;">
                <button type="button" class="mu-btn mu-btn-outline" onclick="document.getElementById('modal-tambah-user').classList.remove('show')">Batal</button>
                <button type="submit" class="mu-btn mu-btn-primary" id="submit-user-btn">Kirim Undangan</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchUserTab(tab) {
    document.querySelectorAll('.mu-tab').forEach(b => b.classList.toggle('active', b.dataset.tab === tab));
    document.getElementById('panel-invite').style.display = tab === 'invite' ? 'block' : 'none';
    document.getElementById('panel-direct').style.display = tab === 'direct' ? 'block' : 'none';
    document.getElementById('mode-input').value = tab;
    document.getElementById('submit-user-btn').textContent = tab === 'invite' ? 'Kirim Undangan' : 'Buat Akun';
}

@if (session('generated_account'))
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        title: 'Akun Berhasil Dibuat',
        html: `
            <p style="font-size:13.5px; color:#64748B; margin-bottom:14px;">Simpan kredensial ini sekarang — password tidak akan ditampilkan lagi.</p>
            <div style="background:#f8fafc; border-radius:10px; padding:14px; text-align:left;">
                <p style="margin:0 0 6px; font-size:13px;"><strong>Email:</strong> {{ session('generated_account')['email'] }}</p>
                <p style="margin:0; font-size:13px;"><strong>Password:</strong> <code style="font-size:15px;">{{ session('generated_account')['password'] }}</code></p>
            </div>
        `,
        icon: 'success',
        confirmButtonText: 'Sudah Disalin, Tutup',
        confirmButtonColor: '#0081AB',
        allowOutsideClick: false,
    });
});
@endif
</script>

@endsection