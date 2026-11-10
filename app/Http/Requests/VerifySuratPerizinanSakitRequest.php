<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifySuratPerizinanSakitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminTu() ?? false;
    }

    public function rules(): array
    {
        return [
            'keputusan' => [
                'required',
                Rule::in(['disetujui', 'ditolak', 'perlu_revisi']),
            ],
            'catatan_tu' => [
                'nullable',
                'required_if:keputusan,perlu_revisi,ditolak',
                'string',
                'max:500',
            ],
        ];
    }
}
