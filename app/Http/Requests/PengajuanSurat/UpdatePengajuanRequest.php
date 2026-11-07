<?php

namespace App\Http\Requests\PengajuanSurat;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        $pengajuan = $this->route('pengajuan');

        return auth()->check()
            && auth()->user()->hasPermission('pengajuan.update')
            && $pengajuan->bisaDiedit();
    }

    public function rules(): array
    {
        return [
            'blanko_id' => ['sometimes', 'exists:blanko_surat,id'],
            'tanggal_pelaksanaan' => ['sometimes', 'date', 'after_or_equal:today'],
            'waktu_mulai' => ['nullable', 'date_format:H:i'],
            'waktu_selesai' => ['nullable', 'date_format:H:i', 'after:waktu_mulai'],
            'tempat' => ['nullable', 'string', 'max:200'],
            'nama_kegiatan' => ['sometimes', 'string', 'max:200'],
            'jenis_surat' => ['sometimes', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
            'lampiran' => ['nullable', 'array', 'max:5'],
            'lampiran.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'aksi' => ['nullable', 'in:draft,submit'],
        ];
    }
}
