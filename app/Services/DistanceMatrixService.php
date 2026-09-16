<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DistanceMatrixService
{
    protected string $endpoint = 'https://routes.googleapis.com/distanceMatrix/v2:computeRouteMatrix';

    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_routes.key');
    }

    /**
     * @param  array{lat: float, lng: float}  $origin
     * @param  array<int, array{lat: float, lng: float}>  $destinations
     * @return array<int, array{index: int, jarak_km: float|null, durasi_menit: float|null, status: string}>
     */
    public function hitungJarak(array $origin, array $destinations): array
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException('GOOGLE_ROUTES_API_KEY belum diset di .env');
        }

        if (empty($destinations)) {
            return [];
        }

        $payload = [
            'origins' => [
                [
                    'waypoint' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => $origin['lat'],
                                'longitude' => $origin['lng'],
                            ],
                        ],
                    ],
                ],
            ],
            'destinations' => array_map(fn (array $d) => [
                'waypoint' => [
                    'location' => [
                        'latLng' => [
                            'latitude' => $d['lat'],
                            'longitude' => $d['lng'],
                        ],
                    ],
                ],
            ], $destinations),
            'travelMode' => 'DRIVE',
            'routingPreference' => 'TRAFFIC_UNAWARE',
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Goog-Api-Key' => $this->apiKey,
            'X-Goog-FieldMask' => 'originIndex,destinationIndex,distanceMeters,duration,condition,status',
        ])->post($this->endpoint, $payload);

        if ($response->failed()) {
            Log::error('DistanceMatrixService: request ke Google Routes API gagal', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('Gagal menghubungi Google Routes API (HTTP ' . $response->status() . ')');
        }

        $rows = $response->json();

        $hasil = [];
        foreach ($destinations as $i => $d) {
            $hasil[$i] = [
                'index' => $i,
                'jarak_km' => null,
                'durasi_menit' => null,
                'status' => 'TIDAK_ADA_RESPONSE',
            ];
        }

        foreach ($rows as $row) {
            $idx = $row['destinationIndex'] ?? null;
            if ($idx === null || ! array_key_exists($idx, $hasil)) {
                continue;
            }

            $condition = $row['condition'] ?? null;
            $statusCode = $row['status']['code'] ?? null;

            if ($condition === 'ROUTE_EXISTS' && $statusCode === null) {
                $meters = $row['distanceMeters'] ?? null;
                $durationStr = $row['duration'] ?? null;

                $hasil[$idx]['jarak_km'] = $meters !== null ? round($meters / 1000, 2) : null;
                $hasil[$idx]['durasi_menit'] = $durationStr
                    ? round(((float) rtrim($durationStr, 's')) / 60, 1)
                    : null;
                $hasil[$idx]['status'] = 'OK';
            } else {
                $hasil[$idx]['status'] = $condition ?? ('ERROR_CODE_' . $statusCode);
            }
        }

        return array_values($hasil);
    }
}