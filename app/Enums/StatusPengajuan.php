<?php

namespace App\Enums;

enum StatusPengajuan: string
{
    case DRAFT = 'draft';
    case DIAJUKAN = 'diajukan';
    case DIPROSES = 'diproses';
    case DISETUJUI = 'disetujui';
    case DITOLAK = 'ditolak';
    case SELESAI = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::DIAJUKAN => 'Menunggu Verifikasi Admin',
            self::DIPROSES => 'Menunggu Persetujuan Pimpinan',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak',
            self::SELESAI => 'Selesai',
        };
    }

    public function warna(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::DIAJUKAN => 'blue',
            self::DIPROSES => 'yellow',
            self::DISETUJUI => 'green',
            self::DITOLAK => 'red',
            self::SELESAI => 'emerald',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'pencil',
            self::DIAJUKAN => 'paper-airplane',
            self::DIPROSES => 'clock',
            self::DISETUJUI => 'check-circle',
            self::DITOLAK => 'x-circle',
            self::SELESAI => 'check-badge',
        };
    }

    public function bisaDiedit(): bool
    {
        return $this === self::DRAFT;
    }

    public function bisaDiajukan(): bool
    {
        return in_array($this, [self::DRAFT, self::DITOLAK]);
    }

    public function bisaDihapus(): bool
    {
        return $this === self::DRAFT;
    }
}