<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistem SPKLU' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --brand-dark: #023E8A;
            --brand-mid: #0081AB;
            --brand-light: #4FC3E0;
            --accent-yellow: #FFC629;
            --status-green: #2E9E5B;
            --status-amber: #E8A317;
            --status-red: #C0392B;
            --bg-page: #F6F8FA;
            --text-primary: #0F172A;
            --text-secondary: #64748B;
        }

        * { box-sizing: border-box; }
        button, input, select, textarea { font-family: inherit; }
        body { margin: 0; font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; background-color: var(--bg-page); color: var(--text-primary); -webkit-font-smoothing: antialiased; }
        a { text-decoration: none; color: inherit; }

        .app-shell { display: flex; height: 100vh; overflow: hidden; }
        .app-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .app-content { flex: 1; overflow-y: auto; padding: 26px 28px; animation: contentFadeIn .35s ease; }

        @keyframes contentFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== Class global dipakai lintas halaman: header section biru ===== */
        .section-header-bar {
            background: linear-gradient(135deg, rgba(2,62,138,.06), rgba(0,129,171,.09));
            padding: 20px 24px;
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
        }
        .section-header-bar-left { display: flex; align-items: center; gap: 12px; }
        .section-header-bar-icon {
            width: 34px; height: 34px; min-width: 34px; border-radius: 10px;
            background: linear-gradient(135deg, var(--brand-dark), var(--brand-mid));
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 3px 8px rgba(2,62,138,.25);
        }
        .section-header-bar-icon svg { width: 17px; height: 17px; color: #fff; stroke-width: 2; }
        .section-header-bar h2 { margin: 0; font-size: 16.5px; font-weight: 800; color: var(--brand-dark); }
        .section-header-bar p { margin: 3px 0 0; font-size: 12px; color: var(--text-secondary); }

        .surface-card {
            background: #fff; border-radius: 18px; border: 1px solid #eef1f5;
            box-shadow: 0 1px 2px rgba(15,23,42,.04), 0 6px 20px rgba(15,23,42,.05);
            overflow: hidden; margin-bottom: 22px;
        }

        /* ===== Filter bar — grouping rapi, gak "pecah" pas layar sempit ===== */
        .filter-shell {
            background: linear-gradient(135deg, rgba(2,62,138,.04), rgba(0,129,171,.06));
            border: 1px solid rgba(0,129,171,.14);
            border-radius: 16px;
            padding: 18px 22px;
            margin-bottom: 22px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .filter-shell-row {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }
        .filter-shell-row:first-child { justify-content: space-between; }
        .filter-label-inline {
            display: flex; align-items: center; gap: 8px;
            font-size: 17px; font-weight: 800; color: var(--brand-dark); letter-spacing: -0.01em;
            flex-shrink: 0;
        }
        .filter-label-inline svg { width: 18px; height: 18px; stroke-width: 2.2; }
        .filter-divider { width: 1px; height: 22px; background: rgba(0,129,171,.18); flex-shrink: 0; }

        /* ===== Row icon kecil, dipakai di kolom pertama tabel manapun ===== */
        .row-icon-cell { display: flex; align-items: center; gap: 10px; }
        .row-icon-box {
            width: 30px; height: 30px; min-width: 30px; border-radius: 8px;
            background: rgba(0,129,171,.1); color: var(--brand-mid);
            display: flex; align-items: center; justify-content: center;
        }
        .row-icon-box svg { width: 14px; height: 14px; stroke-width: 2; }
        .row-icon-box.amber { background: rgba(232,163,23,.14); color: #92660f; }
        .row-icon-box.green { background: rgba(46,158,91,.12); color: var(--status-green); }

        /* ===== Modal animasi masuk, dipakai semua family modal (.trx-, .msp-, .up-) ===== */
        @keyframes modalPop {
            from { opacity: 0; transform: scale(.96) translateY(6px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        [class*="-modal-overlay"] > [class*="-modal"] { animation: modalPop .18s cubic-bezier(.34,1.56,.64,1); }

        /* ===== Sidebar ===== */

        .sidebar {
            width: 252px; flex-shrink: 0; color: #fff; display: flex; flex-direction: column;
            background: linear-gradient(160deg, #023E8A 0%, #034d9e 35%, #0081AB 100%);
            position: relative;
        }
        .sidebar::after {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(circle at 85% 0%, rgba(255,255,255,0.07), transparent 45%);
        }

        .sidebar-logo { display: flex; align-items: center; gap: 12px; padding: 26px 22px 22px; position: relative; z-index: 1; }
        .sidebar-logo-icon {
            width: 40px; height: 40px; border-radius: 12px;
            background: linear-gradient(135deg, var(--accent-yellow), #ffab00);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(255,198,41,.35);
        }
        /* FIX: "i" diganti lucide jadi <svg>, jadi selector harus cover keduanya */
        .sidebar-logo-icon i, .sidebar-logo-icon svg { width: 21px; height: 21px; color: var(--brand-dark); }
        .sidebar-logo-text .title { font-weight: 800; font-size: 15px; letter-spacing: -0.01em; line-height: 1.2; margin: 0; }
        .sidebar-logo-text .subtitle { font-size: 11.5px; color: rgba(255,255,255,0.65); line-height: 1.2; margin: 2px 0 0; font-weight: 500; }

        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px 14px 16px; position: relative; z-index: 1; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

        .sidebar-nav > * { opacity: 0; animation: menuSlideIn .4s ease forwards; }
        .sidebar-nav > *:nth-child(1) { animation-delay: .02s; }
        .sidebar-nav > *:nth-child(2) { animation-delay: .06s; }
        .sidebar-nav > *:nth-child(3) { animation-delay: .10s; }
        .sidebar-nav > *:nth-child(4) { animation-delay: .14s; }
        .sidebar-nav > *:nth-child(5) { animation-delay: .18s; }
        .sidebar-nav > *:nth-child(6) { animation-delay: .22s; }
        .sidebar-nav > *:nth-child(7) { animation-delay: .26s; }
        .sidebar-nav > *:nth-child(8) { animation-delay: .30s; }
        .sidebar-nav > *:nth-child(9) { animation-delay: .34s; }
        .sidebar-nav > *:nth-child(10) { animation-delay: .38s; }

        @keyframes menuSlideIn {
            from { opacity: 0; transform: translateX(-8px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .sidebar-link {
            display: flex; align-items: center; justify-content: flex-start; gap: 12px; padding: 10px 14px; border-radius: 11px;
            font-size: 13.8px; font-weight: 500; color: rgba(255,255,255,0.75);
            margin-bottom: 4px; cursor: pointer; border: none; background: none; width: 100%; text-align: left;
            transition: background-color .2s ease, color .2s ease, transform .15s ease, padding-left .2s ease;
            position: relative;
        }
        .sidebar-link:hover { color: #fff; background-color: rgba(255,255,255,0.09); transform: translateX(2px); }
        .sidebar-link.active {
            color: #fff; font-weight: 600;
            background: linear-gradient(90deg, rgba(255,198,41,0.2), rgba(255,255,255,0.06));
            border-left: 3px solid var(--accent-yellow); padding-left: 11px;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.06);
        }
        .sidebar-link-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-link i, .sidebar-link svg {
            width: 17px; height: 17px; flex-shrink: 0; transition: transform .2s ease, filter .2s ease;
        }
        .sidebar-link:hover i, .sidebar-link:hover svg { transform: scale(1.12); }
        .sidebar-link.active i, .sidebar-link.active svg { filter: drop-shadow(0 0 5px rgba(255,198,41,.55)); }

        .sidebar-group-toggle { display: flex; align-items: center; justify-content: space-between; }
        .sidebar-group-toggle .chevron { width: 14px; height: 14px; transition: transform 0.25s cubic-bezier(.4,0,.2,1); opacity: 0.7; }
        .sidebar-group-toggle.open .chevron { transform: rotate(180deg); }

        .sidebar-submenu {
            margin: 0 0 0 30px; padding-left: 13px; border-left: 1px solid rgba(255,255,255,0.14);
            max-height: 0; opacity: 0; overflow: hidden;
            transition: max-height .3s cubic-bezier(.4,0,.2,1), opacity .25s ease, margin .3s ease;
        }
        .sidebar-submenu.open { max-height: 300px; opacity: 1; margin: 3px 0 8px 30px; }

        .sidebar-sublink {
            display: block; padding: 8px 12px; border-radius: 8px; font-size: 13.3px;
            color: rgba(255,255,255,0.62); margin-bottom: 2px; transition: background-color .15s ease, color .15s ease, transform .15s ease;
        }
        .sidebar-sublink:hover { color: #fff; background-color: rgba(255,255,255,0.07); transform: translateX(2px); }
        .sidebar-sublink.active {
            color: #fff; background-color: rgba(255,255,255,0.11); font-weight: 600;
            border-left: 3px solid var(--accent-yellow); margin-left: -14px; padding-left: 11px;
        }

        .sidebar-footer { padding: 14px 14px 18px; border-top: 1px solid rgba(255,255,255,0.12); position: relative; z-index: 1; }
        .sidebar-user { display: flex; align-items: center; gap: 11px; padding: 8px 10px; border-radius: 10px; transition: background-color .15s ease; }
        .sidebar-user:hover { background-color: rgba(255,255,255,.05); }
        .sidebar-avatar {
            width: 34px; height: 34px; border-radius: 50%; background: rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center; font-size: 12.5px; font-weight: 700; flex-shrink: 0;
            border: 1.5px solid rgba(255,255,255,0.3);
        }
        .sidebar-user-name { font-size: 13.5px; font-weight: 600; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-logout-form button {
            display: flex; align-items: center; gap: 12px; width: 100%; padding: 9px 10px; border-radius: 10px;
            font-size: 13.3px; color: rgba(255,255,255,0.62); background: none; border: none; cursor: pointer;
            transition: background-color .15s ease, color .15s ease; margin-top: 4px;
        }
        .sidebar-logout-form button:hover { color: #fff; background-color: rgba(192,57,43,0.28); }
        .sidebar-logout-form i, .sidebar-logout-form svg { width: 16px; height: 16px; }

        /* ===== Topbar ===== */

        .topbar {
            background: #fff; padding: 15px 28px; display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #eef1f5;
            box-shadow: 0 1px 0 rgba(15,23,42,.02);
            position: relative; z-index: 20;
        }
        .topbar::after {
            content: ''; position: absolute; left: 0; right: 0; bottom: -1px; height: 3px;
            background: linear-gradient(90deg, var(--brand-dark), var(--brand-mid), transparent);
            opacity: .55;
        }

        .topbar-breadcrumb { font-size: 11.5px; color: var(--text-secondary); margin: 0 0 5px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
        .topbar-title { font-size: 20px; font-weight: 800; margin: 0; color: var(--text-primary); letter-spacing: -0.015em; }
        .topbar-actions { display: flex; align-items: center; gap: 8px; }

        .topbar-search {
            position: relative;
            display: flex;
            align-items: center;
        }
        .topbar-search input {
            padding: 10px 16px 10px 40px; font-size: 13.5px; border-radius: 12px; border: 1px solid #e7ebf0;
            background-color: var(--bg-page); width: 280px; transition: all .2s ease;
        }
        .topbar-search input:focus { outline: none; border-color: var(--brand-light); box-shadow: 0 0 0 4px rgba(0,129,171,0.1); background: #fff; }
        /* FIX: cover "i" (sebelum diganti) DAN "svg" (setelah lucide.createIcons() jalan) */
        .topbar-search i, .topbar-search svg {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            width: 16px; height: 16px; color: var(--text-secondary); pointer-events: none;
        }

        .topbar-divider { width: 1px; height: 26px; background: #eef1f5; margin: 0 6px; flex-shrink: 0; }

        .topbar-icon-btn {
            position: relative; width: 40px; height: 40px; border-radius: 12px; border: none; background: var(--bg-page);
            display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s ease;
        }
        .topbar-icon-btn:hover { background-color: #eef2f6; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(15,23,42,.06); }
        .topbar-icon-btn.is-open { background-color: #e0f0f5; box-shadow: inset 0 0 0 1.5px rgba(0,129,171,.35); }
        .topbar-icon-btn i, .topbar-icon-btn svg { width: 18px; height: 18px; color: var(--text-secondary); }
        .topbar-icon-btn.is-open i, .topbar-icon-btn.is-open svg { color: var(--brand-mid); }
        .topbar-notif-dot {
            position: absolute; top: 6px; right: 6px; min-width: 16px; height: 16px; padding: 0 3px; border-radius: 999px;
            background-color: var(--status-red); border: 2px solid #fff; color: #fff; font-size: 9.5px; font-weight: 800;
            display: flex; align-items: center; justify-content: center; line-height: 1;
        }

        /* ===== Dropdown generic (notifikasi & profil) ===== */
        .topbar-dropdown-wrap { position: relative; }
        .topbar-dropdown-panel {
            position: absolute; top: calc(100% + 12px); right: 0; width: 320px;
            background: #fff; border-radius: 16px; border: 1px solid #eef1f5;
            box-shadow: 0 12px 32px rgba(15,23,42,.14), 0 2px 6px rgba(15,23,42,.06);
            opacity: 0; visibility: hidden; transform: translateY(-6px) scale(.98);
            transition: opacity .16s ease, transform .16s ease, visibility .16s;
            z-index: 30; overflow: hidden;
        }
        .topbar-dropdown-panel.show { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }
        .topbar-dropdown-panel::before {
            content: ''; position: absolute; top: -6px; right: 16px; width: 12px; height: 12px;
            background: #fff; border-left: 1px solid #eef1f5; border-top: 1px solid #eef1f5; transform: rotate(45deg);
        }

        .topbar-dropdown-header { padding: 16px 18px 12px; border-bottom: 1px solid #f5f7fa; }
        .topbar-dropdown-header h4 { margin: 0; font-size: 14px; font-weight: 800; color: var(--text-primary); }
        .topbar-dropdown-header p { margin: 2px 0 0; font-size: 11.5px; color: var(--text-secondary); }

        .topbar-notif-list { max-height: 320px; overflow-y: auto; }
        .topbar-notif-item {
            display: flex; gap: 12px; padding: 13px 18px; border-bottom: 1px solid #f5f7fa; cursor: pointer;
            transition: background-color .15s ease;
        }
        .topbar-notif-item:last-child { border-bottom: none; }
        .topbar-notif-item:hover { background: rgba(0,129,171,.05); }
        .topbar-notif-icon {
            width: 34px; height: 34px; min-width: 34px; border-radius: 10px; background: rgba(0,129,171,.1); color: var(--brand-mid);
            display: flex; align-items: center; justify-content: center;
        }
        .topbar-notif-icon i, .topbar-notif-icon svg { width: 16px; height: 16px; }
        .topbar-notif-text p { margin: 0; font-size: 12.8px; color: var(--text-primary); line-height: 1.4; }
        .topbar-notif-text span { font-size: 11px; color: var(--text-secondary); }

        .topbar-dropdown-empty { padding: 34px 20px; text-align: center; color: var(--text-secondary); }
        .topbar-dropdown-empty i, .topbar-dropdown-empty svg { width: 30px; height: 30px; color: #cbd5e1; margin-bottom: 8px; }
        .topbar-dropdown-empty p { margin: 0; font-size: 13px; }

        .topbar-profile-summary { display: flex; align-items: center; gap: 12px; padding: 18px; }
        .topbar-profile-avatar {
            width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, var(--brand-dark), var(--brand-mid));
            color: #fff; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 700; flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(2,62,138,.28);
        }
        .topbar-profile-summary h4 { margin: 0; font-size: 14.5px; font-weight: 800; color: var(--text-primary); }
        .topbar-profile-summary p { margin: 2px 0 0; font-size: 12px; color: var(--text-secondary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 190px; }

        .topbar-role-badge {
            display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 999px;
            font-size: 10.5px; font-weight: 700; margin-top: 6px;
        }
        .topbar-role-badge svg { width: 11px; height: 11px; stroke-width: 2.4; }
        .role-badge-super_admin { background: rgba(2,62,138,.12); color: var(--brand-dark); }
        .role-badge-pemasaran { background: rgba(0,129,171,.12); color: var(--brand-mid); }
        .role-badge-pengelola { background: rgba(46,158,91,.12); color: var(--status-green); }
        .role-badge-manajemen { background: #eef2f7; color: var(--text-secondary); }

        .topbar-dropdown-menu { padding: 8px; border-top: 1px solid #f5f7fa; }
        .topbar-dropdown-menu-item {
            display: flex; align-items: center; gap: 11px; width: 100%; padding: 10px 12px; border-radius: 10px;
            font-size: 13.3px; font-weight: 600; color: var(--text-primary); background: none; border: none; cursor: pointer;
            transition: background-color .15s ease;
        }
        .topbar-dropdown-menu-item:hover { background: var(--bg-page); }
        .topbar-dropdown-menu-item.danger { color: var(--status-red); }
        .topbar-dropdown-menu-item.danger:hover { background: rgba(192,57,43,.08); }
        .topbar-dropdown-menu-item i, .topbar-dropdown-menu-item svg { width: 16px; height: 16px; flex-shrink: 0; }

        .topbar-user {
            display: flex; align-items: center; gap: 10px; padding: 6px 12px 6px 6px;
            border-radius: 14px; cursor: pointer; transition: background-color .2s ease; border: none; background: none;
        }
        .topbar-user:hover, .topbar-user.is-open { background-color: var(--bg-page); }
        .topbar-avatar {
            width: 38px; height: 38px; border-radius: 11px; background: linear-gradient(135deg, var(--brand-dark), var(--brand-mid));
            color: #fff; display: flex; align-items: center; justify-content: center; font-size: 13.5px; font-weight: 700; flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(2,62,138,.28); position: relative;
        }
        .topbar-avatar::after {
            content: ''; position: absolute; bottom: -1px; right: -1px; width: 10px; height: 10px; border-radius: 50%;
            background: var(--status-green); border: 2px solid #fff;
        }
        .topbar-user-name { font-size: 13.8px; font-weight: 700; margin: 0; color: var(--text-primary); line-height: 1.3; }
        .topbar-user-role { font-size: 11.5px; margin: 0; color: var(--text-secondary); font-weight: 500; line-height: 1.3; }
        .topbar-user-chevron { width: 15px; height: 15px; color: var(--text-secondary); transition: transform .2s ease; flex-shrink: 0; }
        .topbar-user.is-open .topbar-user-chevron { transform: rotate(180deg); }

        @media (max-width: 768px) {
            .topbar-search { display: none; }
            .topbar-user-name, .topbar-user-role, .topbar-user-chevron { display: none; }
            .topbar-dropdown-panel { width: 280px; }
        }
    </style>
    @stack('styles')
    
</head>
<body>

    <div class="app-shell">

        @include('layouts.partials.sidebar')

        <div class="app-main">

            @include('layouts.partials.topbar')

            <main class="app-content">
                @yield('content')
            </main>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            // ===== Submenu sidebar =====
            document.querySelectorAll('[data-toggle-submenu]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const submenu = document.getElementById(btn.dataset.toggleSubmenu);
                    const isOpening = !submenu.classList.contains('open');

                    submenu.classList.toggle('open');
                    btn.classList.toggle('open');

                    if (isOpening) {
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                    } else {
                        submenu.style.maxHeight = '0px';
                    }
                });
            });

            document.querySelectorAll('.sidebar-submenu.open').forEach(submenu => {
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
            });

            // ===== Modal generic open/close =====
            document.querySelectorAll('[data-open-modal]').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById(btn.dataset.openModal).style.display = 'flex';
                });
            });
            document.querySelectorAll('[data-close-modal]').forEach(btn => {
                btn.addEventListener('click', () => {
                    btn.closest('.modal-overlay, .msp-modal-overlay, .trx-modal-overlay, .up-modal-overlay').style.display = 'none';
                });
            });

            // ===== Konfirmasi SweetAlert, dipakai lintas halaman via atribut data-confirm =====
            document.querySelectorAll('form[data-confirm]').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const isDanger = form.dataset.confirmType === 'danger';

                    Swal.fire({
                        title: form.dataset.confirmTitle || 'Yakin?',
                        text: form.dataset.confirm,
                        icon: isDanger ? 'warning' : 'question',
                        showCancelButton: true,
                        confirmButtonColor: isDanger ? '#C0392B' : '#0081AB',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: isDanger ? 'Ya, Hapus' : 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        buttonsStyling: true,
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // ===== Toast notifikasi terpusat, SATU sumber, gak dobel lagi =====
            @if (session('success'))
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success',
                    title: @json(session('success')),
                    showConfirmButton: false, timer: 4500, timerProgressBar: true,
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'error',
                    title: @json(session('error')),
                    showConfirmButton: false, timer: 5500, timerProgressBar: true,
                });
            @endif

            // ===== Dropdown topbar (notifikasi & profil) — generic, dipakai berapa pun jumlahnya =====
            const dropdownTriggers = document.querySelectorAll('[data-dropdown-trigger]');

            function closeAllDropdowns(except = null) {
                document.querySelectorAll('.topbar-dropdown-panel.show').forEach(panel => {
                    if (panel !== except) panel.classList.remove('show');
                });
                document.querySelectorAll('.is-open').forEach(el => {
                    if (el !== except) el.classList.remove('is-open');
                });
            }

            dropdownTriggers.forEach(trigger => {
                const panel = document.getElementById(trigger.dataset.dropdownTrigger);
                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const willOpen = !panel.classList.contains('show');
                    closeAllDropdowns(willOpen ? panel : null);
                    panel.classList.toggle('show', willOpen);
                    trigger.classList.toggle('is-open', willOpen);
                });
            });

            document.addEventListener('click', () => closeAllDropdowns());
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAllDropdowns(); });
        });
    </script>

    @stack('scripts')
</body>
</html>