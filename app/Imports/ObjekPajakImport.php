<?php

namespace App\Imports;

use App\Models\ObjekPajak;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ObjekPajakImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnFailure, ShouldQueue
{
    use Importable;
    use SkipsFailures;

    public function model(array $row)
    {
        return ObjekPajak::updateOrCreate([
            'nop' => $row['nop'],
        ], [
            'nik_pemilik' => $row['nik_pemilik'] ?? null,
            'letak_objek' => $row['letak_objek'] ?? null,
            'luas_bumi' => $row['luas_bumi'] ?? 0,
            'luas_bangunan' => $row['luas_bangunan'] ?? 0,
            'status_aktif' => $row['status_aktif'] ?? 'aktif',
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            'nop' => ['required', 'string', 'max:32'],
            'nik_pemilik' => ['required', 'string', 'max:20', 'exists:subjek_pajak,nik'],
            'letak_objek' => ['required', 'string'],
            'luas_bumi' => ['nullable', 'numeric'],
            'luas_bangunan' => ['nullable', 'numeric'],
            'status_aktif' => ['nullable', 'in:aktif,nonaktif'],
        ];
    }
}
