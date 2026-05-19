<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjekPajakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'rt' => ['nullable', 'string', 'max:5'],
            'rw' => ['nullable', 'string', 'max:5'],
            'no_hp' => ['nullable', 'string', 'max:25'],
        ];
    }
}
