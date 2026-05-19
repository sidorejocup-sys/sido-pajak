<?php

namespace App\Exports;

use App\Models\ObjekPajak;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ObjekPajakExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ObjekPajak::select(['nop', 'nik_pemilik', 'letak_objek', 'luas_bumi', 'luas_bangunan', 'status_aktif'])->get();
    }

    public function headings(): array
    {
        return ['nop', 'nik_pemilik', 'letak_objek', 'luas_bumi', 'luas_bangunan', 'status_aktif'];
    }
}
