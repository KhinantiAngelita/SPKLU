<?php

return [

    [
        'label' => 'Dashboard',
        'route' => 'dashboard',
        'icon'  => 'layout-dashboard',
        'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
    ],

    [
        'label' => 'Master SPKLU',
        'route' => 'master-spklu.index',
        'icon'  => 'zap',
        'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
    ],

    [
        'label' => 'Transaksi',
        'icon'  => 'arrow-left-right',
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
        ],
    ],

    [
        'label' => 'Monitoring SPKLU',
        'icon'  => 'activity',
        'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
        'children' => [
            [
                'label' => 'Probabilitas',
                'route' => 'monitoring.probabilitas.index',
                'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
            ],
            [
                'label' => 'Tambah Kandidat Baru',
                'route' => 'monitoring.kandidat.create',
                'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
            ],
        ],
    ],

    [
        'label' => 'Kandidat',
        'route' => 'kandidat-prioritas.index',
        'icon'  => 'users',
        'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
    ],

    [
        'label' => 'FS Skema',
        'route' => 'fs-skema.index',
        'icon'  => 'calculator',
        'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
    ],

    [
        'label' => 'Penjadwalan',
        'route' => 'penjadwalan.index',
        'icon'  => 'calendar-check',
        'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
    ],

    [
        'label' => 'Master Parameter',
        'route' => 'master-parameter.index',
        'icon'  => 'settings',
        'roles' => ['super_admin'],
    ],

    [
        'label' => 'Manajemen User',
        'route' => 'manajemen-user.index',
        'icon'  => 'users',
        'roles' => ['super_admin'],
    ],

];