<!-- resources/views/layouts/partials/topbar.blade.php -->
@php
    $roleLabels = [
        'super_admin' => 'Super Admin',
        'pemasaran' => 'Pemasaran',
        'pengelola' => 'Pengelola',
        'manajemen' => 'Manajemen',
    ];
    $roleKey = auth()->user()->role;
    $roleLabel = $roleLabels[$roleKey] ?? ucwords(str_replace('_', ' ', $roleKey));
@endphp

<header class="topbar">

    <div>
        <p class="topbar-breadcrumb">@yield('breadcrumb', 'Menu')</p>
        <h1 class="topbar-title">@yield('page-title', $title ?? 'Halaman')</h1>
    </div>

    <div class="topbar-actions">

        <div class="topbar-search">
            <i data-lucide="search"></i>
            <input type="text" placeholder="Cari lokasi, SPKLU...">
        </div>

        <div class="topbar-divider"></div>

        {{-- Notifikasi --}}
        <div class="topbar-dropdown-wrap">
            <button class="topbar-icon-btn" type="button" data-dropdown-trigger="panel-notifikasi">
                <i data-lucide="bell"></i>
                @if (($notifCount ?? 0) > 0)
                    <span class="topbar-notif-dot">{{ $notifCount > 9 ? '9+' : $notifCount }}</span>
                @endif
            </button>

            <div class="topbar-dropdown-panel" id="panel-notifikasi">
                <div class="topbar-dropdown-header">
                    <h4>Notifikasi</h4>
                    <p>Pembaruan terbaru dari sistem</p>
                </div>

                <div class="topbar-notif-list">
                    @forelse ($notifications ?? [] as $notif)
                        <div class="topbar-notif-item">
                            <div class="topbar-notif-icon"><i data-lucide="{{ $notif->icon ?? 'bell' }}"></i></div>
                            <div class="topbar-notif-text">
                                <p>{{ $notif->pesan }}</p>
                                <span>{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="topbar-dropdown-empty">
                            <i data-lucide="bell-off"></i>
                            <p>Belum ada notifikasi baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Profil user --}}
        <div class="topbar-dropdown-wrap">
            <button class="topbar-user" type="button" data-dropdown-trigger="panel-profil">
                <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <p class="topbar-user-name">{{ auth()->user()->name }}</p>
                    <p class="topbar-user-role">{{ $roleLabel }}</p>
                </div>
                <i data-lucide="chevron-down" class="topbar-user-chevron"></i>
            </button>

            <div class="topbar-dropdown-panel" id="panel-profil">
                <div class="topbar-profile-summary">
                    <div class="topbar-profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div>
                        <h4>{{ auth()->user()->name }}</h4>
                        <p>{{ auth()->user()->email }}</p>
                        <span class="topbar-role-badge role-badge-{{ $roleKey }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            {{ $roleLabel }}
                        </span>
                    </div>
                </div>

                <div class="topbar-dropdown-menu">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="topbar-dropdown-menu-item danger">
                            <i data-lucide="log-out"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>