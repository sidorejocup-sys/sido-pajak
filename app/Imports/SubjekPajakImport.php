<?php

namespace App\Imports;

use App\Models\SubjekPajak;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SubjekPajakImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnFailure
{
    use Importable;
    use SkipsFailures;

    protected int $rowCount = 0;

    public function model(array $row)
    {
        $this->rowCount++;

        return SubjekPajak::updateOrCreate([
            'nik' => $row['nik'],
        ], [
            'nama' => $row['nama'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'rt' => $row['rt'] ?? null,
            'rw' => $row['rw'] ?? null,
            'no_hp' => $row['no_hp'] ?? null,
        ]);
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'rt' => ['nullable', 'string', 'max:5'],
            'rw' => ['nullable', 'string', 'max:5'],
            'no_hp' => ['nullable', 'string', 'max:25'],
        ];
    }
}
