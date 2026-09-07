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

        <button class="topbar-icon-btn">
            <i data-lucide="bell"></i>
            @if (($notifCount ?? 0) > 0)
                <span class="topbar-notif-dot"></span>
            @endif
        </button>

        <div class="topbar-user">
            <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <p class="topbar-user-name">{{ auth()->user()->name }}</p>
                <p class="topbar-user-role">{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</p>
            </div>
        </div>
    </div>
</header>