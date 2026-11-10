<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSuratPerizinanSakitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isGuru() ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('alasan')) {
            $this->merge([
                'alasan' => trim((string) $this->input('alasan')),
            ]);
        }
    }

    /**
     * Aturan validasi yang berlaku untuk request ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal_mulai' => [
                'required',
                'date',
            ],
            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'alasan' => [
                'required',
                'string',
                'max:2000',
            ],

            'surat_dokter' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ];
    }

    /**
     * Pesan error kustom dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tanggal_mulai.required' => 'Tanggal mulai izin wajib diisi',
            'tanggal_mulai.date' => 'Tanggal mulai izin harus berupa tanggal yang valid',
            'tanggal_selesai.required' => 'Tanggal selesai izin wajib diisi',
            'tanggal_selesai.date' => 'Tanggal selesai izin harus berupa tanggal yang valid',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai izin',
            'alasan.required' => 'Alasan sakit wajib diisi',
            'alasan.string' => 'Alasan sakit harus berupa teks',
            'alasan.max' => 'Alasan sakit maksimal :max karakter',
            'surat_dokter.required' => 'Surat keterangan dokter wajib diunggah',
            'surat_dokter.file' => 'Surat dokter harus berupa berkas yang valid',
            'surat_dokter.mimes' => 'Surat dokter harus berformat PDF, JPG, JPEG, atau PNG',
            'surat_dokter.max' => 'Ukuran surat dokter maksimal :max KB (5 MB)',
        ];
    }

    /**
     * Nama atribut yang lebih ramah pengguna untuk pesan error.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'tanggal_mulai' => 'tanggal mulai izin',
            'tanggal_selesai' => 'tanggal selesai izin',
            'alasan' => 'alasan sakit',
            'surat_dokter' => 'surat dokter',
        ];
    }
}
