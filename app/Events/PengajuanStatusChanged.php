<?php

declare(strict_types=1);

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
        public readonly PengajuanSurat $pengajuan,
        public readonly string $targetRole,
        public readonly string $aksi,
    ) {
        $this->pengajuan->loadMissing('user');
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
            'pengajuan_id' => $this->pengajuan->id,
            'nomor_pengajuan' => $this->pengajuan->nomor_pengajuan,
            'nomor_surat' => $this->pengajuan->nomor_surat,
            'nama_kegiatan' => $this->pengajuan->nama_kegiatan,

            'pengaju' => $this->pengajuan->user->name ?? 'Sistem',
            'pengaju_initial' => strtoupper(substr($this->pengajuan->user->name ?? 'S', 0, 2)),

            'status' => $this->pengajuan->status->value,
            'status_label' => $this->pengajuan->status->label(),
            'aksi' => $this->aksi,
            'aksi_label' => $this->getAksiLabel(),
            'theme_color' => $this->getThemeColor(),

            'url' => route('pengajuan.show', $this->pengajuan->id),
            'waktu' => now()->translatedFormat('d F Y H:i'),
            'time_ago' => $this->pengajuan->updated_at->diffForHumans(),
        ];
    }

    private function getAksiLabel(): string
    {
        return match ($this->aksi) {
            'submitted' => 'Pengajuan Baru Masuk',
            'verified' => 'Pengajuan Diverifikasi',
            'approved' => 'Pengajuan Disetujui',
            'rejected' => 'Pengajuan Ditolak',
            'revisi' => 'Permintaan Revisi',
            'updated' => 'Pengajuan Diperbarui',
            'deleted' => 'Pengajuan Dihapus',
            default => 'Notifikasi Sistem',
        };
    }

    private function getThemeColor(): string
    {
        return match ($this->aksi) {
            'submitted', 'updated' => 'blue',
            'verified' => 'gold',
            'approved' => 'emerald',
            'rejected', 'deleted' => 'red',
            'revisi' => 'orange',
            default => 'gray',
        };
    }
}
