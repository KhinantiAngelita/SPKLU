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
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class TransaksiImport implements ToCollection, WithHeadingRow, WithChunkReading, WithCustomCsvSettings
{
    protected array $aggregates = [];
    protected ?array $spkluCacheLoose = null;
    protected ?array $spkluCacheTight = null;
    protected ?array $aliasCache = null;
    public array $unmatched = [];
    public int $totalRowsProcessed = 0;
    protected string $csvDelimiter;

    public function __construct(string $csvDelimiter = ',')
    {
        $this->csvDelimiter = $csvDelimiter;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => $this->csvDelimiter,
            'enclosure' => '"',
            'input_encoding' => 'UTF-8',
        ];
    }

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
            $this->aggregates[$key]['kwh'] += $this->parseAngka($row['kwh'] ?? 0);
            $this->aggregates[$key]['rp'] += $this->parseAngka($row['rppakai'] ?? 0);
        }
    }

    protected function primeCachesIfNeeded(): void
    {
        if ($this->spkluCacheLoose === null) {
            $this->spkluCacheLoose = [];
            $this->spkluCacheTight = [];

            foreach (Spklu::withTrashed()->get(['id', 'nama']) as $spklu) {
                $loose = $this->normalize($spklu->nama);
                $tight = str_replace(' ', '', $loose);

                $this->spkluCacheLoose[$loose] = $spklu->id;
                $this->spkluCacheTight[$tight] = $spklu->id;
            }
        }

        if ($this->aliasCache === null) {
            $this->aliasCache = SpkluAlias::pluck('spklu_id', 'nama_asli')->all();
        }
    }

    protected function resolveSpkluId(string $namaRaw): ?int
    {
        if (isset($this->aliasCache[$namaRaw])) {
            return $this->aliasCache[$namaRaw];
        }

        $loose = $this->normalize($namaRaw);

        if (isset($this->spkluCacheLoose[$loose])) {
            return $this->spkluCacheLoose[$loose];
        }

        $tight = str_replace(' ', '', $loose);
        if (isset($this->spkluCacheTight[$tight])) {
            return $this->spkluCacheTight[$tight];
        }

        return null;
    }

    protected function normalize(string $s): string
    {
        $s = strtoupper($s);
        $s = str_replace('+', ' ', $s);
        $s = preg_replace('/\bPLUS\b/', ' ', $s);
        $s = str_replace(['.', ','], ' ', $s);
        $s = preg_replace('/[^A-Z0-9 ]/', '', $s);
        $s = preg_replace('/\s+/', ' ', $s);
        return trim($s);
    }

    protected function parseTanggal(string $raw): ?string
    {
        $datePart = trim(explode(' ', $raw)[0] ?? '');
        try {
            return Carbon::createFromFormat('d-m-Y', $datePart)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function parseAngka($value): float
    {
        $value = trim((string) $value);
        if ($value === '') {
            return 0.0;
        }
        return (float) str_replace(',', '.', $value);
    }

    public function chunkSize(): int
    {
        return 5000;
    }

    public function getAggregates(): array
    {
        return $this->aggregates;
    }

    /** Sekarang butuh transaksiUploadId, buat nandain baris ini hasil dari upload yang mana. */
    public function flushToDatabase(int $uploadedBy, int $transaksiUploadId): int
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
                'transaksi_upload_id' => $transaksiUploadId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('transaksis')->upsert(
                $chunk,
                ['spklu_id', 'tanggal'],
                ['jumlah_transaksi', 'energi_kwh', 'pendapatan_rp', 'diupload_oleh', 'transaksi_upload_id', 'updated_at']
            );
        }

        return count($rows);
    }
}