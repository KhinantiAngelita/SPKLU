<?php

namespace App\Imports;

use App\Models\Spklu;
use App\Models\UlpMapping;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\AfterImport;

class SpkluImport implements ToModel, WithHeadingRow, WithValidation, WithCalculatedFormulas, SkipsOnFailure, SkipsEmptyRows, WithEvents
{
    use SkipsFailures;

    private int $counter = 0;

    /** ID (primary key) SPKLU yang "disentuh" (di-update atau di-insert) di import kali ini. */
    private array $touchedIds = [];

    public function model(array $row): Model|array|null
    {
        $ulpNama = trim((string) ($row['ulp'] ?? ''));

        $ulp = UlpMapping::whereRaw('LOWER(nama_penuh) = ?', [strtolower($ulpNama)])
            ->orWhereRaw('LOWER(nama_singkat) = ?', [strtolower($ulpNama)])
            ->first();

        if (! $ulp) {
            return null;
        }

        $namaSpklu = trim((string) ($row['nama_spklu'] ?? ''));

        $kwRaw = trim((string) ($row['kw'] ?? ''));
        $isKwBersih = is_numeric(str_replace(',', '.', $kwRaw));

        $lat = is_numeric($row['latitude'] ?? null) ? (float) $row['latitude'] : null;
        $lng = is_numeric($row['longitude'] ?? null) ? (float) $row['longitude'] : null;

        $atribut = [
            'kode_unit' => $row['kd_unit'] ?? null,
            'id_spklu_sumber' => $row['id_spklu'] ?? null,
            'nama' => $namaSpklu,
            'ulp_mapping_id' => $ulp->id,
            'type' => strtoupper(trim($row['type'])),
            'kw' => $isKwBersih ? (float) str_replace(',', '.', $kwRaw) : null,
            'kw_detail' => $kwRaw,
            'nozzle' => $row['nozzle'] ?? 1,
            'kepemilikan' => ucfirst(strtolower(trim($row['kepemilikan']))),
            'skema' => $row['skema'] ?? null,
            'latitude' => $lat,
            'longitude' => $lng,
            'status' => 'aktif',
            'sumber' => 'import',
        ];

        $existing = Spklu::withTrashed()
            ->whereRaw('LOWER(TRIM(nama)) = ?', [strtolower($namaSpklu)])
            ->first();

        if ($existing) {
            $existing->fill($atribut);
            $existing->deleted_at = null;
            $this->touchedIds[] = $existing->id;

            return $existing;
        }

        $this->counter++;

        $atribut['id_spklu'] = 'SPKLU-' . str_pad((string) (Spklu::withTrashed()->max('id') + $this->counter), 3, '0', STR_PAD_LEFT);

        return new Spklu($atribut);
    }

    public function rules(): array
    {
        return [
            'nama_spklu' => 'required|string',
            'ulp' => 'required|string',
            'type' => 'required|in:AC,DC,ac,dc',
            'kw' => 'required',
            'kepemilikan' => 'required|string',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nama_spklu.required' => 'Nama SPKLU wajib diisi',
            'type.in' => 'Type harus AC atau DC',
            'kw.required' => 'Kolom KW wajib diisi',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function () {
                $this->touchedIds = array_merge(
                    $this->touchedIds,
                    Spklu::where('sumber', 'import')
                        ->where('created_at', '>=', now()->subMinutes(5))
                        ->pluck('id')
                        ->all()
                );
            },

            AfterImport::class => function () {
                if (empty($this->touchedIds)) {
                    return;
                }

                Spklu::where('sumber', 'import')
                    ->whereNotIn('id', $this->touchedIds)
                    ->delete();
            },
        ];
    }
}