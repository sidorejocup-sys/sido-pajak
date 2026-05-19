<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MutasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_mutasi' => ['required', 'string', 'max:36'],
            'nop_asal' => ['required', 'string', 'size:18', 'exists:objek_pajak,nop'],
            'nik_lama' => ['required', 'string', 'max:20', 'exists:subjek_pajak,nik'],
            'nik_baru' => ['required', 'string', 'max:20', 'exists:subjek_pajak,nik'],
            'jenis_mutasi' => ['required', 'in:jual,hibah,waris,lainnya'],
            'tgl_mutasi' => ['required', 'date'],
            'no_arsip' => ['nullable', 'string', 'max:255'],
        ];
    }
}
