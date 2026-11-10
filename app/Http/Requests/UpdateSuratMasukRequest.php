<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSuratMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nama_surat' => ['required', 'string', 'max:255'],
            'kategori_folder' => ['required', 'string', 'max:255'],
            'asal_surat' => ['required', 'string', 'max:255'],
            'tanggal_diterima' => ['required', 'date'],
            'deskripsi' => ['nullable', 'string'],
            'file_surat' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }
}
