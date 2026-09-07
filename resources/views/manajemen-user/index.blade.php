@extends('layouts.app')

@section('breadcrumb', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px;">
    <p style="color:var(--text-secondary); margin:0;">Kelola akun dan hak akses pengguna sistem SPKLU</p>
    <button class="btn-primary" data-open-modal="modal-tambah-user">+ Tambah User</button>
</div>

<div class="card-grid" style="grid-template-columns: repeat(3, 1fr);">
    <div class="summary-card" style="display:flex; align-items:center; gap:16px;">
        <div class="summary-card-icon icon-blue" style="width:48px; height:48px; font-size:20px;">{{ $totalTerdaftar }}</div>
        <p style="font-weight:600; margin:0;">Total User Terdaftar</p>
    </div>
    <div class="summary-card" style="display:flex; align-items:center; gap:16px;">
        <div class="summary-card-icon icon-green" style="width:48px; height:48px; font-size:20px;">{{ $totalAktif }}</div>
        <p style="font-weight:600; margin:0; color:var(--status-green);">Total User Aktif</p>
    </div>
    <div class="summary-card" style="display:flex; align-items:center; gap:16px;">
        <div class="summary-card-icon icon-red" style="width:48px; height:48px; font-size:20px;">{{ $totalNonaktif }}</div>
        <p style="font-weight:600; margin:0;">Total User Nonaktif</p>
    </div>
</div>

<div class="summary-card" style="padding:0;">
    <div style="padding:20px 20px 0;">
        <h2 style="margin:0 0 16px;">Daftar Pengguna</h2>
        <form method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search" class="input-search">
        </form>
    </div>

    <table class="data-table">
        <thead>
            <tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Terakhir Login</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div class="avatar-circle">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                        {{ $user->name }}
                    </div>
                </td>
                <td>{{ $user->email }}</td>
                <td><span class="badge badge-role-{{ $user->role }}">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span></td>
                <td>
                    @if ($user->status->value === 'pending')
                        <span class="badge badge-amber">Menunggu Aktivasi</span>
                        <form method="POST" action="{{ route('manajemen-user.resend-invitation', $user) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="link-btn">Kirim Ulang</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('manajemen-user.toggle-status', $user) }}">
                            @csrf
                            <button type="submit" class="toggle-switch {{ $user->status->value === 'active' ? 'on' : '' }}"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <span class="toggle-knob"></span>
                            </button>
                        </form>
                    @endif
                </td>
                <td>{{ $user->last_login_at?->translatedFormat('d F Y') ?? '—' }}</td>
                <td><button class="icon-btn-edit" title="Edit">✎</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="padding:16px 20px;">{{ $users->links() }}</div>
</div>

{{-- Modal Tambah User (isi sama persis kayak sebelumnya, gak berubah) --}}
<div id="modal-tambah-user" class="modal-overlay">
    <div class="modal-box">
        <h3>Tambah User Baru</h3>
        <div style="display:flex; border-bottom:1px solid #e2e8f0; margin-bottom:16px;">
            <button type="button" class="tab-btn active" data-tab="invite" onclick="switchUserTab('invite')">Undang via Email</button>
            <button type="button" class="tab-btn" data-tab="direct" onclick="switchUserTab('direct')">Buat Akun Langsung</button>
        </div>
        <form method="POST" action="{{ route('manajemen-user.store') }}">
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
            <div id="panel-invite" style="margin-top:12px; font-size:13px; color:var(--text-secondary);">
                User akan menerima email undangan, dan bisa memilih aktivasi lewat Google atau kode OTP.
            </div>
            <div id="panel-direct" style="display:none;">
                <label>Password (kosongkan untuk generate otomatis)</label>
                <input type="password" name="password" minlength="8" placeholder="Otomatis dibuatkan kalau kosong">
                <p style="font-size:12px; color:var(--text-secondary); margin-top:6px;">Akun langsung aktif. Saat login pertama kali, user diminta verifikasi OTP lalu wajib buat password baru.</p>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:20px;">
                <button type="button" class="btn-outline" data-close-modal>Batal</button>
                <button type="submit" class="btn-primary" id="submit-user-btn">Kirim Undangan</button>
            </div>
        </form>
    </div>
</div>

@if (session('generated_account'))
<div class="modal-overlay" style="display:flex;">
    <div class="modal-box" style="text-align:center;">
        <h3>Akun Berhasil Dibuat</h3>
        <p style="color:var(--text-secondary); font-size:14px;">Simpan/salin kredensial ini sekarang — password tidak akan ditampilkan lagi.</p>
        <div style="background:var(--bg-page); border-radius:8px; padding:16px; margin:16px 0; text-align:left;">
            <p style="margin:0 0 8px;"><strong>Email:</strong> {{ session('generated_account')['email'] }}</p>
            <p style="margin:0; display:flex; align-items:center; gap:8px;">
                <strong>Password:</strong>
                <code id="generated-password" style="font-size:16px;">{{ session('generated_account')['password'] }}</code>
                <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('generated-password').innerText)" class="link-btn">Salin</button>
            </p>
        </div>
        <button class="btn-primary" onclick="this.closest('.modal-overlay').style.display='none'" style="width:100%;">Sudah Disalin, Tutup</button>
    </div>
</div>
@endif

<script>
function switchUserTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.toggle('active', b.dataset.tab === tab));
    document.getElementById('panel-invite').style.display = tab === 'invite' ? 'block' : 'none';
    document.getElementById('panel-direct').style.display = tab === 'direct' ? 'block' : 'none';
    document.getElementById('mode-input').value = tab;
    document.getElementById('submit-user-btn').textContent = tab === 'invite' ? 'Kirim Undangan' : 'Buat Akun';
}
</script>

@endsection