<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SuratIzin extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_izin'  => 'date',
        'diproses_at'   => 'datetime',
        'diproses_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Admin/pimpinan yang memproses (menyetujui/menolak) surat */
    public function pemroses()
    {
        foreach (['diproses_oleh', 'pemroses_id', 'processed_by', 'disetujui_oleh', 'admin_id'] as $kolom) {
            if (Schema::hasColumn($this->getTable(), $kolom)) {
                return $this->belongsTo(User::class, $kolom);
            }
        }

        // kolom belum ada: relasi kosong agar halaman tidak error
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    protected static function booted(): void
    {
        // Surat baru -> beri tahu Admin TU & Pimpinan
        static::created(function (SuratIzin $s) {
            try {
                $adminIds = User::whereHas('role', fn ($q) => $q->whereIn('name', ['admin_tu', 'pimpinan']))
                    ->where('id', '!=', $s->user_id)
                    ->pluck('id');

                foreach ($adminIds as $id) {
                    IzinNotifikasi::kirim(
                        $id, $s->id,
                        'Surat izin baru',
                        $s->nama . ' mengajukan surat izin untuk tanggal ' . $s->tanggal_izin->format('d/m/Y') . '.'
                    );
                }
            } catch (\Throwable $e) {
                report($e);
            }
        });

        // Status berubah -> beri tahu pemilik surat
        static::updated(function (SuratIzin $s) {
            if (!$s->wasChanged('status')) return;
            if (!in_array($s->status, ['disetujui', 'ditolak'])) return;

            try {
                $setuju = $s->status === 'disetujui';
                IzinNotifikasi::kirim(
                    $s->user_id, $s->id,
                    $setuju ? 'Surat izin disetujui' : 'Surat izin ditolak',
                    'Surat izin untuk tanggal ' . $s->tanggal_izin->format('d/m/Y') . ' telah ' . ($setuju ? 'disetujui.' : 'ditolak.')
                );
            } catch (\Throwable $e) {
                report($e);
            }
        });
    }
}