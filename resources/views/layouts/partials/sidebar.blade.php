@php
    $userRole = auth()->user()->role;
    $menuItems = collect(config('menu'))
        ->filter(fn ($item) => in_array($userRole, $item['roles']))
        ->map(function ($item) use ($userRole) {
            if (isset($item['children'])) {
                $item['children'] = collect($item['children'])
                    ->filter(fn ($child) => in_array($userRole, $child['roles']))
                    ->values()
                    ->all();
            }
            return $item;
        });
@endphp

<aside class="sidebar">

    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <i data-lucide="zap"></i>
        </div>
        <div class="sidebar-logo-text">
            <p class="title">SPKLU</p>
            <p class="subtitle">{{ ucwords(str_replace('_', ' ', $userRole)) }}</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        @foreach ($menuItems as $index => $item)

            @if (isset($item['children']) && count($item['children']))

                @php
                    $isParentActive = collect($item['children'])->contains(fn ($c) => request()->routeIs($c['route'].'*'));
                    $submenuId = 'submenu-'.$index;
                @endphp

                <button
                    type="button"
                    class="sidebar-link sidebar-group-toggle {{ $isParentActive ? 'open' : '' }}"
                    data-toggle-submenu="{{ $submenuId }}"
                >
                    <span style="display:flex; align-items:center; gap:12px;">
                        <i data-lucide="{{ $item['icon'] }}"></i>
                        {{ $item['label'] }}
                    </span>
                    <i data-lucide="chevron-down" class="chevron"></i>
                </button>

                <div id="{{ $submenuId }}" class="sidebar-submenu {{ $isParentActive ? 'open' : '' }}">
                    @foreach ($item['children'] as $child)
                        @php $active = request()->routeIs($child['route'].'*'); @endphp
                        <a href="{{ route($child['route']) }}" class="sidebar-sublink {{ $active ? 'active' : '' }}">
                            {{ $child['label'] }}
                        </a>
                    @endforeach
                </div>

            @else

                @php $active = request()->routeIs($item['route'].'*'); @endphp
                <a href="{{ route($item['route']) }}" class="sidebar-link {{ $active ? 'active' : '' }}">
                    <i data-lucide="{{ $item['icon'] }}"></i>
                    {{ $item['label'] }}
                </a>

            @endif

        @endforeach
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <p class="sidebar-user-name">{{ auth()->user()->name }}</p>
        </div>
        <form class="sidebar-logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">
                <i data-lucide="log-out"></i>
                Keluar
            </button>
        </form>
    </div>

</aside>