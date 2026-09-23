<?php

return [

    [
        'label' => 'Dashboard',
        'route' => 'dashboard',
        'icon' => 'layout-dashboard',
        'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
    ],

    [
        'label' => 'Master SPKLU',
        'route' => 'master-spklu.index',
        'icon' => 'zap',
        'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
    ],

    [
        'label' => 'Transaksi',
        'icon' => 'arrow-left-right',
        'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
        'children' => [
            [
                'label' => 'Ringkasan',
                'route' => 'transaksi.index',
                'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
            ],
            [
                'label' => 'Upload Data',
                'route' => 'transaksi.upload',
                'roles' => ['super_admin', 'pengelola'],
            ],
            [
                'label' => 'Proyeksi Energi',
                'route' => 'transaksi.proyeksi',
                'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
            ],
        ],
    ],

    [
        'label' => 'Monitoring SPKLU',
        'icon' => 'activity',
        'roles' => ['super_admin', 'pengelola', 'manajemen'],
        'children' => [
            [
                'label' => 'Probabilitas',
                'route' => 'monitoring.probabilitas.index',
                'roles' => ['super_admin', 'pengelola', 'manajemen'],
            ],
            [
                'label' => 'Tambah Kandidat Baru',
                'route' => 'monitoring.kandidat.create',
                'roles' => ['super_admin', 'pengelola'],
            ],
            [
                'label' => 'Pengajuan',
                'route' => 'monitoring.pengajuan.index',
                'roles' => ['super_admin', 'pengelola'],
            ],
        ],
    ],

    [
        'label' => 'Kandidat',
        'icon' => 'bolt', // ganti sesuai icon set yang dipakai menu lain
        'roles' => ['super_admin', 'pengelola'],
        'children' => [
            [
                'label' => 'Jarak & Poin',
                'route' => 'kandidat-prioritas.index', // route existing (KandidatPrioritasController) - cek nama aslinya di routes/web.php
                'roles' => ['super_admin', 'pengelola'],
            ],
            [
                'label' => 'Peringkat',
                'route' => 'kandidat-peringkat.index', // route baru
                'roles' => ['super_admin', 'pengelola'],
            ],
        ],
    ],

    [
        'label' => 'Rekomendasi Lokasi',
        'route' => 'rekomendasi-lokasi.index',
        'icon' => 'map-pin',
        'roles' => ['super_admin', 'pengelola'],
    ],

    [
        'label' => 'FS Skema',
        'route' => 'fs-skema.index',
        'icon' => 'calculator',
        'roles' => ['super_admin', 'pemasaran', 'pengelola'],
    ],

    [
        'label' => 'Penjadwalan',
        'route' => 'penjadwalan.index',
        'icon' => 'calendar-check',
        'roles' => ['super_admin', 'pemasaran', 'pengelola'],
    ],

    [
        'label' => 'Master Parameter',
        'route' => 'master-parameter.index',
        'icon' => 'settings',
        'roles' => ['super_admin'],
    ],

    [
        'label' => 'Manajemen User',
        'route' => 'manajemen-user.index',
        'icon' => 'users',
        'roles' => ['super_admin'],
    ],

];
