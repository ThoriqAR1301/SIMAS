<?php

declare(strict_types=1);

namespace App\Http\Requests\PengajuanSurat;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        $pengajuan = $this->route('pengajuan');

        return $this->user()?->hasPermission('pengajuan.update')
            && $pengajuan?->bisaDiedit();
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('panitia') && is_array($this->input('panitia'))) {
            $panitia = array_filter($this->input('panitia'), function ($item) {
                return ! empty($item['nama']) || ! empty($item['jabatan']);
            });
            $this->merge(['panitia' => array_values($panitia)]);
        }

        if (! $this->has('aksi')) {
            $this->merge(['aksi' => 'draft']);
        }
    }

    /**
     * Aturan validasi untuk request ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'blanko_id' => [
                'sometimes',
                Rule::exists('blanko_surat', 'id')->where('is_active', true),
            ],
            'jenis_surat' => ['sometimes', 'string', 'max:50'],
            'nama_kegiatan' => ['sometimes', 'string', 'max:200'],

            'tanggal_pelaksanaan' => ['sometimes', 'date', 'after_or_equal:today'],
            'waktu_mulai' => ['nullable', 'date_format:H:i'],
            'waktu_selesai' => ['nullable', 'date_format:H:i', 'after:waktu_mulai'],
            'tempat' => ['nullable', 'string', 'max:200'],

            'keterangan' => ['nullable', 'string', 'max:2000'],

            'panitia' => ['sometimes', 'nullable', 'array'],
            'panitia.*' => ['array'],
            'panitia.*.nama' => ['required', 'string', 'max:255'],
            'panitia.*.jabatan' => ['required', 'string', 'max:255'],
            'panitia.*.keterangan' => ['nullable', 'string', 'max:255'],

            'lampiran' => ['nullable', 'array', 'max:5'],
            'lampiran.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],

            'aksi' => ['nullable', Rule::in(['draft', 'submit'])],
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
            'blanko_id.exists' => 'Blanko Surat tidak valid atau sudah tidak aktif',
            'tanggal_pelaksanaan.after_or_equal' => 'Tanggal Pelaksanaan tidak boleh di masa lalu',
            'waktu_selesai.after' => 'Waktu Selesai harus setelah Waktu Mulai',
            'panitia.*.nama.required' => 'Nama guru pada susunan panitia wajib diisi',
            'panitia.*.jabatan.required' => 'Jabatan panitia wajib diisi',
            'lampiran.max' => 'Maksimal 5 lampiran yang diperbolehkan',
            'lampiran.*.mimes' => 'Lampiran harus berformat PDF, JPG, atau PNG',
            'lampiran.*.max' => 'Ukuran lampiran maksimal 5 MB',
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
            'blanko_id' => 'blanko surat',
            'tanggal_pelaksanaan' => 'tanggal pelaksanaan',
            'nama_kegiatan' => 'nama kegiatan',
            'waktu_mulai' => 'waktu mulai',
            'waktu_selesai' => 'waktu selesai',
            'panitia.*.nama' => 'nama panitia',
            'panitia.*.jabatan' => 'jabatan panitia',
            'lampiran.*' => 'lampiran',
        ];
    }
}
