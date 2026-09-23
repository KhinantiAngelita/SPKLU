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

    $searchableMenus = [];
    foreach (config('menu', []) as $item) {
        if (!in_array($roleKey, $item['roles'] ?? [])) {
            continue;
        }

        if (!empty($item['children'])) {
            foreach ($item['children'] as $child) {
                if (in_array($roleKey, $child['roles'] ?? [])) {
                    $searchableMenus[] = [
                        'title' => $child['label'],
                        'parent' => $item['label'],
                        'full_title' => $item['label'] . ' › ' . $child['label'],
                        'url' => route($child['route']),
                        'icon' => $item['icon'] ?? 'circle',
                        'keywords' => strtolower($item['label'] . ' ' . $child['label']),
                    ];
                }
            }
        } else {
            $searchableMenus[] = [
                'title' => $item['label'],
                'parent' => null,
                'full_title' => $item['label'],
                'url' => route($item['route']),
                'icon' => $item['icon'] ?? 'circle',
                'keywords' => strtolower($item['label']),
            ];
        }
    }
@endphp

<header class="topbar">

    <div class="topbar-left">
        {{-- BARU: tombol minimize/expand sidebar. Statenya (collapsed atau
             enggak) disimpan ke localStorage lewat script di app.blade.php,
             jadi nempel walau pindah halaman. --}}
        <button type="button" class="sidebar-toggle-btn" id="sidebar-toggle-btn" title="Perkecil/perbesar sidebar">
            <i data-lucide="panel-left"></i>
        </button>

        <div class="topbar-search" id="topbar-search-container">
            <i data-lucide="search"></i>
            <input type="text"
                   id="topbar-menu-search-input"
                   placeholder="Cari menu & sub menu..."
                   autocomplete="off"
                   spellcheck="false">
            <span class="topbar-search-kbd">⌘K</span>

            {{-- Dropdown Hasil Pencarian Menu & Sub Menu --}}
            <div class="topbar-search-dropdown" id="topbar-search-dropdown">
                <div class="topbar-search-header">
                    <span>Menu Navigasi</span>
                    <span id="topbar-search-count">{{ count($searchableMenus) }} menu</span>
                </div>
                <div class="topbar-search-list" id="topbar-search-list"></div>
                <div class="topbar-search-footer">
                    <div class="topbar-search-footer-group">
                        <button type="button" class="topbar-search-kbd-btn" id="topbar-search-btn-up" title="Pilih menu sebelumnya (Panah Atas)">
                            <kbd>↑</kbd>
                        </button>
                        <button type="button" class="topbar-search-kbd-btn" id="topbar-search-btn-down" title="Pilih menu berikutnya (Panah Bawah)">
                            <kbd>↓</kbd>
                        </button>
                        <span class="topbar-search-footer-text">navigasi</span>
                    </div>

                    <div class="topbar-search-footer-group">
                        <button type="button" class="topbar-search-kbd-btn" id="topbar-search-btn-enter" title="Buka menu yang dipilih (Enter)">
                            <kbd>Enter</kbd> <span>buka</span>
                        </button>
                    </div>

                    <div class="topbar-search-footer-group">
                        <button type="button" class="topbar-search-kbd-btn" id="topbar-search-btn-esc" title="Tutup pencarian (Esc)">
                            <kbd>Esc</kbd> <span>tutup</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="topbar-actions">

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
                    <p>Pembaruan aktivitas sistem operasional</p>
                </div>

                <div class="topbar-notif-list">
                    @forelse ($notifications ?? [] as $notif)
                        @php
                            $isUnread = !auth()->user()->last_read_notification_at || $notif->created_at > auth()->user()->last_read_notification_at;
                        @endphp
                        @if ($notif->url)
                            <a href="{{ $notif->url }}" class="topbar-notif-item {{ $isUnread ? 'is-unread' : '' }}" style="text-decoration: none; color: inherit;">
                        @else
                            <div class="topbar-notif-item {{ $isUnread ? 'is-unread' : '' }}">
                        @endif
                            <div class="topbar-notif-icon">
                                <i data-lucide="{{ $notif->icon ?? 'bell' }}"></i>
                            </div>
                            <div class="topbar-notif-text">
                                @if ($notif->judul)
                                    <strong style="display: block; font-size: 12.5px; font-weight: 700; color: var(--text-primary); margin-bottom: 2px;">{{ $notif->judul }}</strong>
                                @endif
                                <p>{{ $notif->pesan }}</p>
                                <span>{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                        @if ($notif->url)
                            </a>
                        @else
                            </div>
                        @endif
                    @empty
                        <div class="topbar-dropdown-empty">
                            <i data-lucide="bell-off"></i>
                            <p>Belum ada notifikasi aktivitas.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="topbar-divider"></div>

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
                    <a href="{{ route('profile.index') }}" class="topbar-dropdown-menu-item">
                        <i data-lucide="user"></i>
                        Profile Saya
                    </a>
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

<style>
.topbar-search-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    width: 380px;
    max-width: calc(100vw - 32px);
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12), 0 4px 12px rgba(15, 23, 42, 0.06);
    overflow: hidden;
    z-index: 1050;
    display: none;
    flex-direction: column;
    animation: topbarSearchFade .16s ease-out;
}
@keyframes topbarSearchFade {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}
.topbar-search-dropdown.show {
    display: flex;
}
.topbar-search-header {
    padding: 10px 14px 7px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #94a3b8;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fafbfc;
    border-bottom: 1px solid #f1f5f9;
}
.topbar-search-list {
    max-height: 320px;
    overflow-y: auto;
    padding: 6px;
}
.topbar-search-list::-webkit-scrollbar {
    width: 5px;
}
.topbar-search-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.topbar-search-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 12px;
    border-radius: 9px;
    text-decoration: none;
    color: #1e293b;
    cursor: pointer;
    transition: background .12s ease, color .12s ease;
}
.topbar-search-item:hover,
.topbar-search-item.active {
    background: linear-gradient(135deg, rgba(2,62,138,0.06), rgba(0,129,171,0.10));
    color: #023e8a;
}
.topbar-search-item-icon {
    width: 32px !important;
    height: 32px !important;
    min-width: 32px !important;
    border-radius: 8px !important;
    background: rgba(0, 129, 171, 0.08) !important;
    color: #0081ab !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex-shrink: 0 !important;
    transition: all .12s ease !important;
}
.topbar-search-item-icon svg {
    position: static !important;
    left: auto !important;
    top: auto !important;
    transform: none !important;
    width: 16px !important;
    height: 16px !important;
    display: block !important;
    color: inherit !important;
    stroke: currentColor !important;
    stroke-width: 2 !important;
    pointer-events: auto !important;
}
.topbar-search-item:hover .topbar-search-item-icon,
.topbar-search-item.active .topbar-search-item-icon {
    background: #0081ab !important;
    color: #ffffff !important;
}
.topbar-search-item-info {
    flex: 1;
    min-width: 0;
}
.topbar-search-item-parent {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 600;
    line-height: 1.2;
    display: block;
    margin-bottom: 2px;
}
.topbar-search-item-title {
    font-size: 13.3px;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.3;
    display: block;
}
.topbar-search-item:hover .topbar-search-item-title,
.topbar-search-item.active .topbar-search-item-title {
    color: #023e8a;
}
.topbar-search-item-arrow {
    position: static !important;
    left: auto !important;
    top: auto !important;
    transform: none !important;
    width: 14px !important;
    height: 14px !important;
    display: block !important;
    margin-left: auto !important;
    color: #cbd5e1 !important;
    opacity: 0 !important;
    transition: all .15s ease !important;
    flex-shrink: 0 !important;
}
.topbar-search-item:hover .topbar-search-item-arrow,
.topbar-search-item.active .topbar-search-item-arrow {
    opacity: 1 !important;
    transform: translateX(2px) !important;
    color: #0081ab !important;
}
.topbar-search-empty {
    padding: 24px 16px;
    text-align: center;
    color: #94a3b8;
    font-size: 13px;
}
.topbar-search-empty svg {
    margin: 0 auto 8px;
    display: block;
    color: #cbd5e1;
}
.topbar-search-footer {
    padding: 8px 12px;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    color: #94a3b8;
    user-select: none;
}
.topbar-search-footer-group {
    display: flex;
    align-items: center;
    gap: 4px;
}
.topbar-search-footer-text {
    font-size: 11px;
    color: #94a3b8;
    margin-left: 2px;
}
.topbar-search-kbd-btn {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 3px 8px;
    font-family: inherit;
    font-size: 11px;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all .15s ease;
    box-shadow: 0 1px 2px rgba(15,23,42,.04);
}
.topbar-search-kbd-btn:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #023e8a;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(15,23,42,.08);
}
.topbar-search-kbd-btn:active {
    transform: translateY(0);
    box-shadow: none;
}
.topbar-search-kbd-btn kbd {
    font-family: inherit;
    font-size: 10px;
    font-weight: 700;
    color: #023e8a;
    background: #eef2f6;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 1px 4px;
}
.topbar-search-highlight {
    background: rgba(255, 198, 41, 0.4);
    color: #023e8a;
    border-radius: 2px;
    padding: 0 1px;
    font-weight: 700;
}
</style>

<script>
(function () {
    const searchableMenus = @json($searchableMenus);
    const ICON_SVGS = {
        'layout-dashboard': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>',
        'zap': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
        'arrow-left-right': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 3 4 4-4 4"/><path d="M20 7H4"/><path d="m8 21-4-4 4-4"/><path d="M4 17h16"/></svg>',
        'activity': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
        'bolt': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>',
        'map-pin': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>',
        'calculator': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2"/><line x1="8" x2="16" y1="6" y2="6"/><line x1="16" x2="16" y1="14" y2="18"/><path d="M16 10h.01"/><path d="M12 10h.01"/><path d="M8 10h.01"/><path d="M12 14h.01"/><path d="M8 14h.01"/><path d="M12 18h.01"/><path d="M8 18h.01"/></svg>',
        'calendar-check': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>',
        'settings': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>',
        'users': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'circle': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>'
    };

    function initSearch() {
        const container = document.getElementById('topbar-search-container');
        const input = document.getElementById('topbar-menu-search-input');
        const dropdown = document.getElementById('topbar-search-dropdown');
        const listEl = document.getElementById('topbar-search-list');
        const countEl = document.getElementById('topbar-search-count');
        const btnUp = document.getElementById('topbar-search-btn-up');
        const btnDown = document.getElementById('topbar-search-btn-down');
        const btnEnter = document.getElementById('topbar-search-btn-enter');
        const btnEsc = document.getElementById('topbar-search-btn-esc');

        if (!container || !input || !dropdown || !listEl) return;

        let activeIndex = -1;
        let currentResults = [];

        function escapeHtml(str) {
            return (str || '').replace(/[&<>"']/g, m => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
            }[m]));
        }

        function highlightMatch(text, query) {
            if (!query) return escapeHtml(text);
            const escaped = escapeHtml(text);
            const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            return escaped.replace(regex, '<mark class="topbar-search-highlight">$1</mark>');
        }

        function renderResults(query) {
            const q = (query || '').trim().toLowerCase();
            if (!q) {
                currentResults = searchableMenus;
            } else {
                currentResults = searchableMenus.filter(m => 
                    m.keywords.includes(q) || 
                    m.title.toLowerCase().includes(q) || 
                    (m.parent && m.parent.toLowerCase().includes(q))
                );
            }

            activeIndex = currentResults.length > 0 ? 0 : -1;

            if (countEl) {
                countEl.textContent = `${currentResults.length} menu`;
            }

            if (currentResults.length === 0) {
                listEl.innerHTML = `
                    <div class="topbar-search-empty">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <p style="margin:0 0 4px;font-weight:600;color:#64748b;">Menu tidak ditemukan</p>
                        <span style="font-size:11.5px;color:#94a3b8;">Tidak ada menu/sub menu yang cocok dengan "${escapeHtml(query)}"</span>
                    </div>
                `;
                dropdown.classList.add('show');
                return;
            }

            listEl.innerHTML = currentResults.map((item, index) => {
                const parentHtml = item.parent ? `<span class="topbar-search-item-parent">${highlightMatch(item.parent, q)}</span>` : '';
                const titleHtml = `<span class="topbar-search-item-title">${highlightMatch(item.title, q)}</span>`;
                const iconSvg = ICON_SVGS[item.icon] || ICON_SVGS['circle'];

                return `
                    <a href="${item.url}" class="topbar-search-item ${index === activeIndex ? 'active' : ''}" data-index="${index}">
                        <div class="topbar-search-item-icon">
                            ${iconSvg}
                        </div>
                        <div class="topbar-search-item-info">
                            ${parentHtml}
                            ${titleHtml}
                        </div>
                        <svg class="topbar-search-item-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </a>
                `;
            }).join('');

            // Add hover listener on rendered items
            listEl.querySelectorAll('.topbar-search-item').forEach((itemEl) => {
                itemEl.addEventListener('mouseenter', function () {
                    const idx = parseInt(this.getAttribute('data-index'), 10);
                    if (!isNaN(idx)) {
                        activeIndex = idx;
                        updateActiveItem(false);
                    }
                });
            });

            dropdown.classList.add('show');
        }

        function updateActiveItem(shouldScroll = true) {
            const items = listEl.querySelectorAll('.topbar-search-item');
            items.forEach((el, idx) => {
                if (idx === activeIndex) {
                    el.classList.add('active');
                    if (shouldScroll) {
                        el.scrollIntoView({ block: 'nearest' });
                    }
                } else {
                    el.classList.remove('active');
                }
            });
        }

        function navigateSelection(delta) {
            if (currentResults.length === 0) return;
            if (activeIndex === -1) {
                activeIndex = delta > 0 ? 0 : currentResults.length - 1;
            } else {
                activeIndex = (activeIndex + delta + currentResults.length) % currentResults.length;
            }
            updateActiveItem(true);
        }

        function openActiveSelection() {
            if (activeIndex >= 0 && activeIndex < currentResults.length) {
                const targetUrl = currentResults[activeIndex].url;
                if (targetUrl) {
                    window.location.href = targetUrl;
                }
            }
        }

        function closeDropdown() {
            dropdown.classList.remove('show');
            input.blur();
        }

        input.addEventListener('focus', function () {
            renderResults(this.value);
        });

        input.addEventListener('input', function () {
            renderResults(this.value);
        });

        input.addEventListener('keydown', function (e) {
            if (!dropdown.classList.contains('show')) {
                if (e.key === 'ArrowDown' || e.key === 'Enter') {
                    renderResults(this.value);
                    e.preventDefault();
                }
                return;
            }

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                navigateSelection(1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                navigateSelection(-1);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                openActiveSelection();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                closeDropdown();
            }
        });

        // Clickable footer button actions
        if (btnUp) {
            btnUp.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                navigateSelection(-1);
                input.focus();
            });
        }

        if (btnDown) {
            btnDown.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                navigateSelection(1);
                input.focus();
            });
        }

        if (btnEnter) {
            btnEnter.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openActiveSelection();
            });
        }

        if (btnEsc) {
            btnEsc.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                closeDropdown();
            });
        }

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            if (!container.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Global shortcut: Ctrl+K / Cmd+K or "/"
        document.addEventListener('keydown', function (e) {
            const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
            const cmdKey = isMac ? e.metaKey : e.ctrlKey;
            if (cmdKey && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                input.focus();
                input.select();
                renderResults(input.value);
            } else if (e.key === '/' && document.activeElement !== input && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                e.preventDefault();
                input.focus();
                input.select();
                renderResults(input.value);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearch);
    } else {
        initSearch();
    }
})();
</script>