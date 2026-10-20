<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuratPerizinanSakitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan' => ['required', 'string', 'max:2000'],
            'surat_dokter' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tanggal_mulai.required' => 'Tanggal mulai izin wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai izin harus berupa tanggal yang valid.',
            'tanggal_selesai.required' => 'Tanggal selesai izin wajib diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai izin harus berupa tanggal yang valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai izin.',
            'alasan.required' => 'Alasan sakit wajib diisi.',
            'alasan.string' => 'Alasan sakit harus berupa teks.',
            'alasan.max' => 'Alasan sakit maksimal :max karakter.',
            'surat_dokter.required' => 'Surat dokter wajib diunggah.',
            'surat_dokter.file' => 'Surat dokter harus berupa berkas.',
            'surat_dokter.mimes' => 'Surat dokter harus berformat PDF, JPG, JPEG, atau PNG.',
            'surat_dokter.max' => 'Ukuran surat dokter maksimal :max KB (5 MB).',
        ];
    }

    /**
     * Get the custom attributes for validator errors.
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
