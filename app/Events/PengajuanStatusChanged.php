<?php

namespace App\Events;

use App\Models\PengajuanSurat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PengajuanStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public PengajuanSurat $pengajuan,
        public string $targetRole,
        public string $aksi,
    ) {}

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
            'pengajuan_id' => $this->pengajuan->id,
            'nomor_pengajuan' => $this->pengajuan->nomor_pengajuan,
            'nomor_surat' => $this->pengajuan->nomor_surat,
            'nama_kegiatan' => $this->pengajuan->nama_kegiatan,
            'pengaju' => $this->pengajuan->user->name,
            'status' => $this->pengajuan->status->value,
            'status_label' => $this->pengajuan->status->label(),
            'aksi' => $this->aksi,
            'url' => route('pengajuan.show', $this->pengajuan->id),
            'waktu' => now()->translatedFormat('d F Y H:i'),
        ];
    }
}
