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

class SpkluImport implements ToModel, WithHeadingRow, WithValidation, WithCalculatedFormulas, SkipsOnFailure
{
    use SkipsFailures;

    private int $counter = 0;

    public function model(array $row): Model|array|null
    {
        $ulpNama = trim((string) ($row['ulp'] ?? ''));

        $ulp = UlpMapping::whereRaw('LOWER(nama_penuh) = ?', [strtolower($ulpNama)])
            ->orWhereRaw('LOWER(nama_singkat) = ?', [strtolower($ulpNama)])
            ->first();

        if (! $ulp) {
            return null;
        }

        $this->counter++;

        $kwRaw = trim((string) ($row['kw'] ?? ''));
        $isKwBersih = is_numeric(str_replace(',', '.', $kwRaw));

        // Latitude/Longitude sekarang sudah berupa hasil kalkulasi (angka), bukan teks formula lagi.
        // Tetap dijaga aman: kalau ternyata masih bukan angka (formula gagal / IFERROR jatuh ke ""), simpan null.
        $lat = is_numeric($row['latitude'] ?? null) ? (float) $row['latitude'] : null;
        $lng = is_numeric($row['longitude'] ?? null) ? (float) $row['longitude'] : null;

        return new Spklu([
            'id_spklu' => 'SPKLU-' . str_pad((string) (Spklu::withTrashed()->max('id') + $this->counter), 3, '0', STR_PAD_LEFT),
            'kode_unit' => $row['kd_unit'] ?? null,
            'nama' => trim($row['nama_spklu']),
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
            'sumber' => 'manual',
        ]);
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
}