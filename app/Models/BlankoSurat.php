<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlankoSurat extends Model
{
    use HasFactory;

    protected $table = 'blanko_surat';

    protected $fillable = [
        'kode', 'nama', 'deskripsi', 'jenis',
        'template_path', 'requires_lampiran',
        'requires_surat_awalan', 'is_active',
    ];

    protected $casts = [
        'requires_lampiran' => 'boolean',
        'requires_surat_awalan' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function pengajuanSurat()
    {
        return $this->hasMany(PengajuanSurat::class, 'blanko_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}
