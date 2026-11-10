<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\PengajuanSurat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PengajuanActivityChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $pengajuanData;

    public function __construct(
        public PengajuanSurat $pengajuan,
        public string $aksi,
        public string $url,
        public string $targetRole = 'admin_tu'
    ) {
        $this->pengajuan->loadMissing('user', 'blanko');

        $this->pengajuanData = [
            'id' => $this->pengajuan->id,
            'nomor' => $this->pengajuan->nomor_pengajuan,
            'kegiatan' => $this->pengajuan->nama_kegiatan,
            'pengaju' => $this->pengajuan->user->name,
            'status_value' => $this->pengajuan->status->value,
            'status_label' => $this->pengajuan->status->label(),
            'jenis' => $this->pengajuan->jenis_surat,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('role.'.$this->targetRole),
        ];
    }

    public function broadcastAs(): string
    {
        return 'pengajuan.status.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'data' => $this->pengajuanData,
            'aksi' => $this->aksi,
            'url' => $this->url,
            'waktu' => now()->translatedFormat('d F Y H:i'),
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
