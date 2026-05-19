<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ObjekPajakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nop' => ['required', 'string', 'size:18'],
            'nik_pemilik' => ['required', 'string', 'max:20', 'exists:subjek_pajak,nik'],
            'letak_objek' => ['required', 'string'],
            'luas_bumi' => ['required', 'numeric', 'min:0'],
            'luas_bangunan' => ['required', 'numeric', 'min:0'],
            'status_aktif' => ['required', 'in:aktif,nonaktif'],
        ];
    }
}
