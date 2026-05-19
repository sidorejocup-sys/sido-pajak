<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_sppt' => ['required', 'string', 'max:36'],
            'nop' => ['required', 'string', 'size:18', 'exists:objek_pajak,nop'],
            'tahun' => ['required', 'digits:4', 'integer', 'min:1900', 'max:' . (date('Y') + 5)],
            'njop_bumi' => ['required', 'numeric', 'min:0'],
            'njop_bangunan' => ['required', 'numeric', 'min:0'],
            'pajak_terhutang' => ['required', 'numeric', 'min:0'],
            'status_bayar' => ['required', 'in:piutang,lunas'],
        ];
    }
}
