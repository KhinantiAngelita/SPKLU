@extends('layouts.app')

@section('breadcrumb', 'Pengaturan')
@section('page-title', 'Profile Saya')

@section('content')
<style>
    .prof-container {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* ── Hero Profile Card (Soft, Clean, Elegant) ── */
    .prof-hero-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #eef1f5;
        box-shadow: 0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05);
        padding: 24px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .prof-hero-profile {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .prof-hero-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .prof-hero-avatar {
        width: 68px;
        height: 68px;
        border-radius: 16px;
        background: linear-gradient(135deg, #FFC629 0%, #F59E0B 50%, #EA580C 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        font-weight: 800;
        box-shadow: 0 4px 14px rgba(234, 88, 12, .32);
        overflow: hidden;
    }

    .prof-hero-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .prof-status-dot-online {
        position: absolute;
        bottom: -3px;
        right: -3px;
        width: 16px;
        height: 16px;
        background: #2E9E5B;
        border-radius: 50%;
        border: 2.5px solid #fff;
    }

    .prof-hero-info h1 {
        margin: 0 0 5px;
        font-size: 20px;
        font-weight: 800;
        color: #0F172A;
        letter-spacing: -0.01em;
    }

    .prof-hero-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* Soft Amber / Orange Kekuningan Sesuai Design System */
    .prof-badge-role {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
        background: rgba(232,163,23,.14);
        color: #92660F;
        border: 1px solid rgba(232,163,23,.25);
    }

    .prof-hero-email {
        font-size: 13px;
        color: #64748B;
        font-weight: 500;
    }

    .prof-hero-chips {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .prof-hero-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
    }

    .prof-hero-chip-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(232,163,23,.12);
        color: #E8A317;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .prof-hero-chip-icon svg {
        width: 15px;
        height: 15px;
        stroke-width: 2.2;
    }

    .prof-hero-chip-label {
        font-size: 11px;
        font-weight: 600;
        color: #94A3B8;
        text-transform: uppercase;
        margin: 0;
    }

    .prof-hero-chip-val {
        font-size: 13px;
        font-weight: 700;
        color: #1E293B;
        margin: 1px 0 0;
    }

    /* ── Main Grid: 2 Kolom Sejajar, Lebar Sama (50:50) & Tinggi Sama ── */
    .prof-grid-equal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        align-items: stretch; /* Memastikan kedua card sama tinggi/panjang */
    }

    @media (max-width: 900px) {
        .prof-grid-equal {
            grid-template-columns: 1fr;
        }
    }

    /* ── Card Styling Seragam dengan Menu Lain ── */
    .prof-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #eef1f5;
        box-shadow: 0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* Header Bar Soft Blue */
    .prof-card-header {
        background: linear-gradient(135deg, rgba(2,62,138,.06), rgba(0,129,171,.09));
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border-bottom: 1px solid #eef1f5;
    }

    .prof-card-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .prof-card-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: linear-gradient(135deg, rgba(2,62,138,.12), rgba(0,129,171,.12));
        color: #023E8A;
    }

    .prof-card-header-icon svg {
        width: 17px;
        height: 17px;
        stroke-width: 2.2;
    }

    .prof-card-header h2 {
        font-size: 15px;
        font-weight: 800;
        color: #023E8A;
        margin: 0;
    }

    .prof-card-header p {
        font-size: 12px;
        color: #64748B;
        margin: 2px 0 0;
    }

    .prof-card-body {
        padding: 22px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* ── Info Items di Kolom Kiri ── */
    .prof-detail-group {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .prof-detail-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        background: #F8FAFC;
        border-radius: 10px;
        border: 1px solid #F1F5F9;
        gap: 12px;
    }

    .prof-detail-label {
        font-size: 12.5px;
        font-weight: 600;
        color: #64748B;
        margin: 0;
    }

    .prof-detail-val {
        font-size: 13px;
        font-weight: 700;
        color: #1E293B;
        margin: 0;
        text-align: right;
    }

    .prof-soft-notice {
        background: rgba(232,163,23,.08);
        border: 1px solid rgba(232,163,23,.25);
        border-radius: 10px;
        padding: 14px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 18px;
    }

    .prof-soft-notice svg {
        width: 17px;
        height: 17px;
        color: #E8A317;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .prof-soft-notice p {
        margin: 0;
        font-size: 12px;
        color: #92660F;
        line-height: 1.5;
    }

    /* ── Form Items di Kolom Kanan ── */
    .prof-form-field {
        margin-bottom: 16px;
    }

    .prof-form-field label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .prof-form-input {
        width: 100%;
        height: 40px;
        padding: 0 13px;
        border-radius: 9px;
        border: 1px solid #E2E8F0;
        font-size: 13.5px;
        font-family: inherit;
        color: #0F172A;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
        box-sizing: border-box;
    }

    .prof-form-input:focus {
        outline: none;
        border-color: #0081AB;
        box-shadow: 0 0 0 3px rgba(0,129,171,.12);
    }

    .prof-form-input[disabled] {
        background: #F8FAFC;
        color: #64748B;
        cursor: not-allowed;
    }

    .prof-form-hint {
        font-size: 11.5px;
        color: #94A3B8;
        margin-top: 4px;
    }

    .prof-sub-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #0081AB;
        margin: 18px 0 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .prof-sub-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #F1F5F9;
    }

    .prof-pass-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    @media (max-width: 600px) {
        .prof-pass-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ── Tombol Simpan ── */
    .prof-submit-bar {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #F1F5F9;
    }

    .prof-btn-save {
        height: 40px;
        padding: 0 20px;
        border-radius: 9px;
        background: linear-gradient(135deg, #023E8A, #0081AB);
        color: #fff;
        font-size: 13.5px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        box-shadow: 0 2px 8px rgba(2,62,138,.2);
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .prof-btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(2,62,138,.3);
    }

    .prof-btn-save svg {
        width: 15px;
        height: 15px;
        stroke-width: 2.2;
    }

    /* ── Alert Notifikasi ── */
    .prof-alert-success {
        background: rgba(46,158,91,.1);
        border: 1px solid rgba(46,158,91,.25);
        color: #2E9E5B;
        border-radius: 9px;
        padding: 10px 14px;
        margin-bottom: 16px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .prof-alert-error {
        background: rgba(192,57,43,.08);
        border: 1px solid rgba(192,57,43,.25);
        color: #C0392B;
        border-radius: 9px;
        padding: 10px 14px;
        margin-bottom: 16px;
        font-size: 13px;
    }
</style>

<div class="prof-container">

    {{-- Hero Profile Card (Soft & Clean) --}}
    <div class="prof-hero-card">
        <div class="prof-hero-profile">
            <div class="prof-hero-avatar-wrap">
                <div class="prof-hero-avatar">
                    @if ($user->avatar)
                        <img src="{{ $user->avatar }}" alt="Avatar">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <span class="prof-status-dot-online" title="Status Akun: Aktif"></span>
            </div>
            <div class="prof-hero-info">
                <h1>{{ $user->name }}</h1>
                <div class="prof-hero-meta">
                    <span class="prof-badge-role">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="12" height="12"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                    </span>
                    <span class="prof-hero-email">&bull; {{ $user->email }}</span>
                </div>
            </div>
        </div>

        <div class="prof-hero-chips">
            <div class="prof-hero-chip">
                <div class="prof-hero-chip-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <p class="prof-hero-chip-label">Bergabung</p>
                    <p class="prof-hero-chip-val">{{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}</p>
                </div>
            </div>

            <div class="prof-hero-chip">
                <div class="prof-hero-chip-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div>
                    <p class="prof-hero-chip-label">Metode Login</p>
                    <p class="prof-hero-chip-val">{{ $user->google_id ? 'Google OAuth' : 'Password' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Grid: 2 Kolom Sejajar (Lebar Sama 50:50 & Tinggi Sama) --}}
    <div class="prof-grid-equal">

        {{-- Kolom Kiri: Informasi Akun --}}
        <div class="prof-card">
            <div class="prof-card-header">
                <div class="prof-card-header-left">
                    <div class="prof-card-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <h2>Informasi Akun</h2>
                        <p>Rincian status dan hak akses sistem</p>
                    </div>
                </div>
            </div>

            <div class="prof-card-body">
                <div class="prof-detail-group">
                    <div class="prof-detail-item">
                        <span class="prof-detail-label">Email Terdaftar</span>
                        <span class="prof-detail-val">{{ $user->email }}</span>
                    </div>

                    <div class="prof-detail-item">
                        <span class="prof-detail-label">Tingkat Hak Akses</span>
                        <span class="prof-detail-val" style="color:#B45309;">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>
                    </div>

                    <div class="prof-detail-item">
                        <span class="prof-detail-label">Tanggal Registrasi</span>
                        <span class="prof-detail-val">{{ $user->created_at ? $user->created_at->translatedFormat('l, d F Y') : '-' }}</span>
                    </div>

                    @if ($user->invitedBy)
                        <div class="prof-detail-item">
                            <span class="prof-detail-label">Diundang Oleh</span>
                            <span class="prof-detail-val">{{ $user->invitedBy->name }}</span>
                        </div>
                    @endif

                    @if ($user->first_login_verified_at)
                        <div class="prof-detail-item">
                            <span class="prof-detail-label">Verifikasi Awal OTP</span>
                            <span class="prof-detail-val">{{ $user->first_login_verified_at->translatedFormat('d M Y, H:i') }} WIB</span>
                        </div>
                    @else
                        <div class="prof-detail-item">
                            <span class="prof-detail-label">Status Verifikasi</span>
                            <span class="prof-detail-val" style="color:#2E9E5B;">● Akun Aktif &amp; Terverifikasi</span>
                        </div>
                    @endif
                </div>

                {{-- Soft Warm Notice Box --}}
                <div class="prof-soft-notice">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <p>Akun ini memiliki hak akses <strong>{{ ucwords(str_replace('_', ' ', $user->role)) }}</strong> pada portal SPKLU rEVolution. Pastikan kredensial login Anda selalu terlindungi dan tidak dibagikan kepada pihak lain.</p>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Pengaturan Profil & Keamanan --}}
        <div class="prof-card">
            <div class="prof-card-header">
                <div class="prof-card-header-left">
                    <div class="prof-card-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                    </div>
                    <div>
                        <h2>Pengaturan Akun</h2>
                        <p>Kelola data nama dan kata sandi login</p>
                    </div>
                </div>
            </div>

            <div class="prof-card-body">
                <form method="POST" action="{{ route('profile.update') }}" style="display:flex; flex-direction:column; height:100%; justify-content:space-between;">
                    @csrf
                    @method('PATCH')

                    <div>
                        @if (session('success'))
                            <div class="prof-alert-success">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="prof-alert-error">
                                <strong style="display:block; margin-bottom:4px;">Isian perlu diperbaiki:</strong>
                                <ul style="margin:0; padding-left:18px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="prof-sub-title">
                            <span>Informasi Pribadi</span>
                        </div>

                        <div class="prof-form-field">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" id="name" name="name" class="prof-form-input" value="{{ old('name', $user->name) }}" required placeholder="Nama lengkap Anda">
                        </div>

                        <div class="prof-form-field">
                            <label>Alamat Email</label>
                            <input type="email" class="prof-form-input" value="{{ $user->email }}" disabled>
                            <p class="prof-form-hint">Alamat email dikunci demi integritas riwayat audit log sistem.</p>
                        </div>

                        <div class="prof-sub-title">
                            <span>Keamanan Kata Sandi</span>
                        </div>

                        @if (!$user->google_id)
                            <div class="prof-pass-grid">
                                <div class="prof-form-field" style="margin-bottom:0;">
                                    <label for="password">Kata Sandi Baru</label>
                                    <input type="password" id="password" name="password" class="prof-form-input" placeholder="Minimal 8 karakter" autocomplete="new-password">
                                </div>
                                <div class="prof-form-field" style="margin-bottom:0;">
                                    <label for="password_confirmation">Ulangi Kata Sandi</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="prof-form-input" placeholder="Ulangi sandi baru" autocomplete="new-password">
                                </div>
                            </div>
                            <p class="prof-form-hint" style="margin-top:6px;">Kosongkan bila tidak ingin mengganti kata sandi saat ini.</p>
                        @else
                            <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:9px; padding:12px 14px; font-size:12.5px; color:#475569; display:flex; align-items:center; gap:10px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="color:#0081AB; flex-shrink:0;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                                <span>Akun terhubung ke <strong>Google OAuth</strong>. Autentikasi dikelola oleh Google.</span>
                            </div>
                        @endif
                    </div>

                    <div class="prof-submit-bar">
                        <button type="submit" class="prof-btn-save">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection
