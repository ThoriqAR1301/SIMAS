<?php

namespace App\Services;

use App\Models\Pengaturan;
use Carbon\Carbon;

class NomorSuratService
{
    public function generateNomorPengajuan(): string
    {
        $now = Carbon::now();
        $prefix = sprintf('PGJ/%s/%s/', $now->year, $now->format('m'));

        $last = \DB::table('pengajuan_surat')->where('nomor_pengajuan', 'like', $prefix . '%')->orderByDesc('nomor_pengajuan')->lockForUpdate()->value('nomor_pengajuan');

        $seq = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function generateNomorSurat(string $kodeBlanko): string
    {
        $format = Pengaturan::get('surat.format_nomor', '{seq}/{kode}/{unit}/{romawi}/{tahun}');
        $unit = Pengaturan::get('surat.kode_unit', 'TU');
        $reset = Pengaturan::get('surat.reset_seq', 'tahunan');
        $now = Carbon::now();

        $romawi = $this->bulanRomawi($now->month);

        $query = \DB::table('pengajuan_surat')->where('nomor_surat', 'not like', 'PGJ/%')->whereNotNull('nomor_surat')->where('nomor_surat', 'like', "%/{$kodeBlanko}/%")->whereYear('created_at', $now->year)->lockForUpdate();

        if ($reset === 'bulanan') {
            $query->whereMonth('created_at', $now->month);
        }

        $last = $query->orderByDesc('id')->value('nomor_surat');

        $seq = 1;
        if ($last) {
            $seq = ((int) substr($last, 0, 3)) + 1;
        }

        return str_replace(
            ['{seq}', '{kode}', '{unit}', '{romawi}', '{tahun}'],
            [str_pad($seq, 3, '0', STR_PAD_LEFT), $kodeBlanko, $unit, $romawi, $now->year],
            $format
        );
    }

    private function bulanRomawi(int $bulan): string
    {
        return [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ][$bulan];
    }
}