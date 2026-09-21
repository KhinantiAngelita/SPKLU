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
        })
        ->values(); // re-index biar submenu-N konsisten setelah difilter
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

            @if (!empty($item['children']))

                @php
                    $submenuId      = 'submenu-'.$index;
                    $isParentActive = collect($item['children'])
                        ->contains(fn ($child) => request()->routeIs($child['route'].'*'));
                @endphp

                <button
                    type="button"
                    class="sidebar-link sidebar-group-toggle {{ $isParentActive ? 'open' : '' }}"
                    data-toggle-submenu="{{ $submenuId }}"
                >
                    <span class="sidebar-link-content">
                        <i data-lucide="{{ $item['icon'] }}"></i>
                        {{ $item['label'] }}
                    </span>
                    <i data-lucide="chevron-down" class="chevron"></i>
                </button>

                <div id="{{ $submenuId }}" class="sidebar-submenu {{ $isParentActive ? 'open' : '' }}">
                    @foreach ($item['children'] as $child)
                        @php $childActive = request()->routeIs($child['route'].'*'); @endphp
                        <a href="{{ route($child['route']) }}"
                           class="sidebar-sublink {{ $childActive ? 'active' : '' }}">
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