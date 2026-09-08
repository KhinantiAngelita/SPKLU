<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem SPKLU' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('styles')
    
</head>
<body>

    <div class="app-shell">

        @include('layouts.partials.sidebar')

        <div class="app-main">

            @include('layouts.partials.topbar')

            <main class="app-content">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                @yield('content')
            </main>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            document.querySelectorAll('[data-toggle-submenu]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const submenu = document.getElementById(btn.dataset.toggleSubmenu);
                    submenu.classList.toggle('open');
                    btn.classList.toggle('open');
                });
            });

            document.querySelectorAll('[data-open-modal]').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById(btn.dataset.openModal).style.display = 'flex';
                });
            });
            document.querySelectorAll('[data-close-modal]').forEach(btn => {
                btn.addEventListener('click', () => {
                    btn.closest('.modal-overlay').style.display = 'none';
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>