<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['tanggal_mulai', 'tanggal_selesai', 'alasan'])]
class SuratPerizinanSakit extends Model
{
    public const STATUS_MENUNGGU = 'menunggu';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DITOLAK = 'ditolak';

    /**
     * @return list<string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_MENUNGGU,
            self::STATUS_DISETUJUI,
            self::STATUS_DITOLAK,
        ];
    }

    /**
     * Get the user that owns the surat perizinan sakit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }
}
