<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\PengajuanSurat;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PengajuanApproved
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly PengajuanSurat $pengajuan,
        public readonly int $step,
    ) {}

    public function getStepLabel(): string
    {
        return match ($this->step) {
            1 => 'Admin Tata Usaha',
            2 => 'Pimpinan',
            default => 'Sistem',
        };
    }

    public function isFinalApproval(): bool
    {
        return $this->step === 2;
    }
}
