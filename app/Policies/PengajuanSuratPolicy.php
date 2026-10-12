<?php

namespace App\Policies;

use App\Models\PengajuanSurat;
use App\Models\User;

class PengajuanSuratPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PengajuanSurat $pengajuan): bool
    {
        if ($user->isGuru()) {
            return $pengajuan->user_id === $user->id;
        }
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('pengajuan.create');
    }

    public function update(User $user, PengajuanSurat $pengajuan): bool
    {
        return $user->id === $pengajuan->user_id && $pengajuan->status->bisaDiedit();
    }

    public function delete(User $user, PengajuanSurat $pengajuan): bool
    {
        return $user->id === $pengajuan->user_id && $pengajuan->status->bisaDihapus();
    }

    public function submit(User $user, PengajuanSurat $pengajuan): bool
    {
        return $user->id === $pengajuan->user_id && $pengajuan->status->bisaDiajukan();
    }

    public function verify(User $user, PengajuanSurat $pengajuan): bool
    {
        return $user->isAdminTu() && $pengajuan->current_step === 1;
    }

    public function approve(User $user, PengajuanSurat $pengajuan): bool
    {
        return $user->isPimpinan() && $pengajuan->current_step === 2;
    }
}