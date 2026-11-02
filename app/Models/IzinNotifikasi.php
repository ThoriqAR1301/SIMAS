<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IzinNotifikasi extends Model
{
    protected $table = 'izin_notifikasis';

    protected $fillable = ['user_id', 'surat_izin_id', 'judul', 'pesan', 'dibaca_at'];

    protected $casts = ['dibaca_at' => 'datetime'];

    public static function kirim($userId, $suratId, string $judul, string $pesan): void
    {
        static::create([
            'user_id'       => $userId,
            'surat_izin_id' => $suratId,
            'judul'         => $judul,
            'pesan'         => $pesan,
        ]);
    }
}