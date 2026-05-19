<?php

namespace App\Exports;

use App\Models\SubjekPajak;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubjekPajakExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return SubjekPajak::select(['nik', 'nama', 'alamat', 'rt', 'rw', 'no_hp'])->get();
    }

    public function headings(): array
    {
        return ['nik', 'nama', 'alamat', 'rt', 'rw', 'no_hp'];
    }
}
