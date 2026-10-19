<?php

namespace App\Http\Requests\PengajuanSurat;

use Illuminate\Foundation\Http\FormRequest;

class StorePengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('pengajuan.create');
    }

    public function rules(): array
    {
        return [
            'blanko_id' => ['required', 'exists:blanko_surat,id'],
            'tanggal_pelaksanaan' => ['required', 'date', 'after_or_equal:today'],
            'waktu_mulai' => ['nullable', 'date_format:H:i'],
            'waktu_selesai' => ['nullable', 'date_format:H:i', 'after:waktu_mulai'],
            'tempat' => ['nullable', 'string', 'max:200'],
            'nama_kegiatan' => ['required', 'string', 'max:200'],
            'jenis_surat' => ['required', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:2000'],

            'lampiran' => ['nullable', 'array', 'max:5'],
            'lampiran.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],

            'aksi' => ['nullable', 'in:draft,submit'],
        ];
    }

    public function messages(): array
    {
        return [
            'blanko_id.required' => 'Jenis Blanko Surat Wajib Dipilih',
            'blanko_id.exists' => 'Blanko Surat Tidak Valid',
            'tanggal_pelaksanaan.required' => 'Tanggal Pelaksanaan Wajib Diisi',
            'tanggal_pelaksanaan.after_or_equal' => 'Tanggal Pelaksanaan Tidak Boleh Di Masa Lalu',
            'waktu_selesai.after' => 'Waktu Selesai Harus Setelah Waktu Mulai',
            'nama_kegiatan.required' => 'Nama Kegiatan Wajib Diisi',
            'jenis_surat.required' => 'Jenis Surat Wajib Diisi',
            'lampiran.max' => 'Maksimal 5 Lampiran',
            'lampiran.*.mimes' => 'Lampiran Harus PDF, JPG, Atau PNG',
            'lampiran.*.max' => 'Ukuran Lampiran Maksimal 5 MB',
        ];
    }

    public function attributes(): array
    {
        return [
            'blanko_id' => 'blanko surat',
            'tanggal_pelaksanaan' => 'tanggal pelaksanaan',
            'nama_kegiatan' => 'nama kegiatan',
        ];
    }
}
