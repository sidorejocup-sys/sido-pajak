<?php

namespace App\Exports;

use App\Models\Sppt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SpptExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Sppt::select(['id_sppt', 'nop', 'tahun', 'njop_bumi', 'njop_bangunan', 'pajak_terhutang', 'status_bayar'])->get();
    }

    public function headings(): array
    {
        return ['id_sppt', 'nop', 'tahun', 'njop_bumi', 'njop_bangunan', 'pajak_terhutang', 'status_bayar'];
    }
}
