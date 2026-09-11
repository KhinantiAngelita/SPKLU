<?php

// Tabel referensi kW -> Poin (interpolasi log-log, disepakati Agustus 2026)
// Sumber: sheet "Definisi & Aturan" poin 6, sheet Kandidat Excel
return [
    'referensi' => [
        7   => 0.25,
        22  => 0.5,
        30  => 0.5676,
        40  => 0.6383,
        50  => 0.6993,
        60  => 0.7534,
        80  => 1.0,
        120 => 1.0,
        180 => 1.0,
        200 => 1.0,
    ],
];