<?php

namespace App\Imports;

use App\Models\Sppt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SpptImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnFailure, ShouldQueue
{
    use Importable;
    use SkipsFailures;

    public function model(array $row)
    {
        return Sppt::updateOrCreate([
            'id_sppt' => $row['id_sppt'],
        ], [
            'nop' => $row['nop'] ?? null,
            'tahun' => $row['tahun'] ?? null,
            'njop_bumi' => $row['njop_bumi'] ?? 0,
            'njop_bangunan' => $row['njop_bangunan'] ?? 0,
            'pajak_terhutang' => $row['pajak_terhutang'] ?? 0,
            'status_bayar' => $row['status_bayar'] ?? 'piutang',
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            'id_sppt' => ['required', 'string', 'max:64'],
            'nop' => ['required', 'string', 'max:32'],
            'tahun' => ['required', 'digits:4'],
            'njop_bumi' => ['nullable', 'numeric'],
            'njop_bangunan' => ['nullable', 'numeric'],
            'pajak_terhutang' => ['nullable', 'numeric'],
            'status_bayar' => ['nullable', 'in:piutang,lunas'],
        ];
    }
}
