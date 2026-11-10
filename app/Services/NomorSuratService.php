<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Pengaturan;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NomorSuratService
{
    private const PREFIX_PENGAJUAN = 'PGJ';

    private const SEQ_LENGTH_PENGAJUAN = 4;

    private const SEQ_LENGTH_SURAT = 3;

    public function generateNomorPengajuan(): string
    {
        $now = CarbonImmutable::now();
        $prefix = sprintf('%s/%s/%s/', self::PREFIX_PENGAJUAN, $now->year, $now->format('m'));

        $last = DB::transaction(function () use ($prefix) {
            return DB::table('pengajuan_surat')
                ->where('nomor_pengajuan', 'like', $prefix.'%')
                ->orderByDesc('nomor_pengajuan')
                ->lockForUpdate()
                ->value('nomor_pengajuan');
        });

        $seq = 1;
        if ($last) {
            $seq = ((int) Str::afterLast($last, '/')) + 1;
        }

        return $prefix.Str::padLeft((string) $seq, self::SEQ_LENGTH_PENGAJUAN, '0');
    }

    public function generateNomorSurat(string $kodeBlanko): string
    {
        $format = (string) Pengaturan::get('surat.format_nomor', '{seq}/{kode}/{unit}/{romawi}/{tahun}');
        $unit = (string) Pengaturan::get('surat.kode_unit', 'TU');
        $reset = (string) Pengaturan::get('surat.reset_seq', 'tahunan');

        $now = CarbonImmutable::now();
        $romawi = $this->bulanRomawi($now->month);

        $last = DB::transaction(function () use ($kodeBlanko, $now, $reset) {
            $query = DB::table('pengajuan_surat')
                ->where('nomor_surat', 'not like', self::PREFIX_PENGAJUAN.'/%')
                ->whereNotNull('nomor_surat')
                ->where('nomor_surat', 'like', "%/{$kodeBlanko}/%")
                ->whereYear('created_at', $now->year)
                ->lockForUpdate();

            if ($reset === 'bulanan') {
                $query->whereMonth('created_at', $now->month);
            }

            return $query->orderByDesc('id')->value('nomor_surat');
        });

        $seq = 1;
        if ($last) {
            $seq = ((int) Str::before($last, '/')) + 1;
        }

        return str_replace(
            ['{seq}', '{kode}', '{unit}', '{romawi}', '{tahun}'],
            [
                Str::padLeft((string) $seq, self::SEQ_LENGTH_SURAT, '0'),
                $kodeBlanko,
                $unit,
                $romawi,
                (string) $now->year,
            ],
            $format
        );
    }

    private function bulanRomawi(int $bulan): string
    {
        return match ($bulan) {
            1 => 'I',   2 => 'II',   3 => 'III', 4 => 'IV',
            5 => 'V',   6 => 'VI',   7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X',    11 => 'XI',  12 => 'XII',
            default => throw new \InvalidArgumentException("Bulan tidak valid: {$bulan}"),
        };
    }
}
