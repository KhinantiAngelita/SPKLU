<?php

namespace App\Imports;

use App\Models\Spklu;
use App\Models\SpkluAlias;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TransaksiImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /** Agregat per (spklu_id + tanggal), diakumulasi lintas-chunk selama import berjalan. */
    protected array $aggregates = [];

    /** Cache nama SPKLU (dinormalisasi) -> id, dibangun sekali di awal. */
    protected ?array $spkluCache = null;

    /** Cache alias manual: nama_asli persis -> spklu_id. */
    protected ?array $aliasCache = null;

    /** Nama SPKLU di file yang gagal dicocokkan -> jumlah baris terdampak. */
    public array $unmatched = [];

    public int $totalRowsProcessed = 0;

    public function collection(Collection $rows): void
    {
        $this->primeCachesIfNeeded();

        foreach ($rows as $row) {
            $this->totalRowsProcessed++;

            $namaRaw = trim((string) ($row['spklu'] ?? ''));
            if ($namaRaw === '') {
                continue;
            }

            $spkluId = $this->resolveSpkluId($namaRaw);

            if (! $spkluId) {
                $this->unmatched[$namaRaw] = ($this->unmatched[$namaRaw] ?? 0) + 1;
                continue;
            }

            $tanggal = $this->parseTanggal((string) ($row['tanggal'] ?? ''));
            if (! $tanggal) {
                continue;
            }

            $key = $spkluId . '_' . $tanggal;

            if (! isset($this->aggregates[$key])) {
                $this->aggregates[$key] = [
                    'spklu_id' => $spkluId,
                    'tanggal' => $tanggal,
                    'jumlah' => 0,
                    'kwh' => 0.0,
                    'rp' => 0.0,
                ];
            }

            $this->aggregates[$key]['jumlah']++;
            $this->aggregates[$key]['kwh'] += (float) ($row['kwh'] ?? 0);
            $this->aggregates[$key]['rp'] += (float) ($row['rppakai'] ?? 0);
        }
    }

    protected function primeCachesIfNeeded(): void
    {
        if ($this->spkluCache === null) {
            $this->spkluCache = [];
            foreach (Spklu::withTrashed()->get(['id', 'nama']) as $spklu) {
                $this->spkluCache[$this->normalize($spklu->nama)] = $spklu->id;
            }
        }

        if ($this->aliasCache === null) {
            $this->aliasCache = SpkluAlias::pluck('spklu_id', 'nama_asli')->all();
        }
    }

    protected function resolveSpkluId(string $namaRaw): ?int
    {
        // 1. Cek alias manual dulu (nama PERSIS seperti di file, case-sensitive apa adanya)
        if (isset($this->aliasCache[$namaRaw])) {
            return $this->aliasCache[$namaRaw];
        }

        // 2. Cek nama yang sudah dinormalisasi (menangani beda kapitalisasi/spasi/tanda baca ringan)
        $normalized = $this->normalize($namaRaw);
        return $this->spkluCache[$normalized] ?? null;
    }

    protected function normalize(string $s): string
    {
        $s = strtoupper($s);
        $s = str_replace(['+', '.', ','], ' ', $s);
        $s = preg_replace('/[^A-Z0-9 ]/', '', $s);
        $s = preg_replace('/\s+/', ' ', $s);
        return trim($s);
    }

    protected function parseTanggal(string $raw): ?string
    {
        // Format asli: "01-01-2026 00.07.19,326000000" -> ambil tanggalnya saja
        $datePart = trim(explode(' ', $raw)[0] ?? '');
        try {
            return Carbon::createFromFormat('d-m-Y', $datePart)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function chunkSize(): int
    {
        return 5000;
    }

    /** Dipanggil controller SETELAH Excel::import() selesai — simpan hasil agregasi ke DB sekaligus. */
    public function flushToDatabase(int $uploadedBy): int
    {
        $now = now();
        $rows = [];

        foreach ($this->aggregates as $agg) {
            $rows[] = [
                'spklu_id' => $agg['spklu_id'],
                'tanggal' => $agg['tanggal'],
                'jumlah_transaksi' => $agg['jumlah'],
                'energi_kwh' => round($agg['kwh'], 2),
                'pendapatan_rp' => round($agg['rp'], 2),
                'diupload_oleh' => $uploadedBy,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('transaksis')->upsert(
                $chunk,
                ['spklu_id', 'tanggal'],
                ['jumlah_transaksi', 'energi_kwh', 'pendapatan_rp', 'diupload_oleh', 'updated_at']
            );
        }

        return count($rows);
    }
}