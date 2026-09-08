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
        'route' => 'transaksi.index',
        'icon'  => 'arrow-left-right',
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

    [
        'label'    => 'Monitoring SPKLU',
        'icon'     => 'activity',
        'roles'    => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
        'children' => [
            [
                'label' => 'Probabilitas',
                'route' => 'monitoring.probabilitas.index',
                'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
            ],
            [
                'label' => 'Pengajuan',
                'route' => 'monitoring.pengajuan.index',
                'roles' => ['super_admin', 'pemasaran', 'pengelola', 'manajemen'],
            ],
        ],
    ],

];