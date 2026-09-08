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