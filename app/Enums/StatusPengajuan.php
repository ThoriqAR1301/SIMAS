<?php

declare(strict_types=1);

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
            self::DRAFT => 'slate',
            self::DIAJUKAN => 'blue',
            self::DIPROSES => 'amber',
            self::DISETUJUI => 'emerald',
            self::DITOLAK => 'red',
            self::SELESAI => 'indigo',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'pencil-square',
            self::DIAJUKAN => 'paper-airplane',
            self::DIPROSES => 'clock',
            self::DISETUJUI => 'check-circle',
            self::DITOLAK => 'x-circle',
            self::SELESAI => 'check-badge',
        };
    }

    public function getBadgeClass(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-slate-500/10 text-slate-600 border-slate-500/20',
            self::DIAJUKAN => 'bg-blue-500/10 text-blue-600 border-blue-500/20',
            self::DIPROSES => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
            self::DISETUJUI => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
            self::DITOLAK => 'bg-red-500/10 text-red-600 border-red-500/20',
            self::SELESAI => 'bg-indigo-500/10 text-indigo-600 border-indigo-500/20',
        };
    }

    public function getDotClass(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-slate-400',
            self::DIAJUKAN => 'bg-blue-500',
            self::DIPROSES => 'bg-amber-500 animate-pulse',
            self::DISETUJUI => 'bg-emerald-500',
            self::DITOLAK => 'bg-red-500',
            self::SELESAI => 'bg-indigo-500',
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

    public function isPending(): bool
    {
        return in_array($this, [self::DIAJUKAN, self::DIPROSES]);
    }

    public function isApproved(): bool
    {
        return in_array($this, [self::DISETUJUI, self::SELESAI]);
    }

    public function isRejected(): bool
    {
        return $this === self::DITOLAK;
    }
}
