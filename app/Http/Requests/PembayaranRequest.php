<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_bayar' => ['required', 'string', 'max:36'],
            'id_sppt' => ['required', 'string', 'max:36', 'exists:sppt,id_sppt'],
            'tgl_bayar' => ['required', 'date'],
            'jumlah_bayar' => ['required', 'numeric', 'min:0'],
            'id_petugas' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}
