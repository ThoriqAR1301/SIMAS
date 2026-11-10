<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'pengajuan.create', 'display_name' => 'Buat Pengajuan', 'grup' => 'pengajuan'],
            ['name' => 'pengajuan.read', 'display_name' => 'Lihat Pengajuan', 'grup' => 'pengajuan'],
            ['name' => 'pengajuan.update', 'display_name' => 'Ubah Pengajuan', 'grup' => 'pengajuan'],
            ['name' => 'pengajuan.delete', 'display_name' => 'Hapus Pengajuan', 'grup' => 'pengajuan'],
            ['name' => 'pengajuan.verify', 'display_name' => 'Verifikasi Pengajuan', 'grup' => 'pengajuan'],
            ['name' => 'pengajuan.approve', 'display_name' => 'Approve Pengajuan', 'grup' => 'pengajuan'],

            ['name' => 'surat_izin.create', 'display_name' => 'Buat Surat Izin', 'grup' => 'surat_izin'],
            ['name' => 'surat_izin.read', 'display_name' => 'Lihat Surat Izin', 'grup' => 'surat_izin'],
            ['name' => 'surat_izin.approve', 'display_name' => 'Approve Surat Izin', 'grup' => 'surat_izin'],
            ['name' => 'surat_izin.manage', 'display_name' => 'Kelola Surat Izin', 'grup' => 'surat_izin'],

            ['name' => 'surat_masuk.create', 'display_name' => 'Input Surat Masuk', 'grup' => 'surat_masuk'],
            ['name' => 'surat_masuk.read', 'display_name' => 'Lihat Surat Masuk', 'grup' => 'surat_masuk'],
            ['name' => 'surat_masuk.update', 'display_name' => 'Ubah Surat Masuk', 'grup' => 'surat_masuk'],
            ['name' => 'surat_masuk.delete', 'display_name' => 'Hapus Surat Masuk', 'grup' => 'surat_masuk'],

            ['name' => 'disposisi.create', 'display_name' => 'Buat Disposisi', 'grup' => 'disposisi'],
            ['name' => 'disposisi.read', 'display_name' => 'Lihat Disposisi', 'grup' => 'disposisi'],
            ['name' => 'disposisi.tindak', 'display_name' => 'Tindak Lanjut Disposisi', 'grup' => 'disposisi'],

            ['name' => 'folder.manage', 'display_name' => 'Kelola Folder Surat', 'grup' => 'folder'],

            ['name' => 'blanko.manage', 'display_name' => 'Kelola Blanko Surat', 'grup' => 'master'],
            ['name' => 'user.manage', 'display_name' => 'Kelola Pengguna', 'grup' => 'master'],
            ['name' => 'pengaturan.manage', 'display_name' => 'Kelola Pengaturan', 'grup' => 'master'],

            ['name' => 'laporan.view', 'display_name' => 'Lihat Laporan', 'grup' => 'laporan'],
            ['name' => 'laporan.export', 'display_name' => 'Export Laporan', 'grup' => 'laporan'],
        ];

        DB::transaction(function () use ($permissions) {
            foreach ($permissions as $permission) {
                Permission::updateOrCreate(
                    ['name' => $permission['name']],
                    $permission
                );
            }
        });

        // PREMIUM FEATURE: Console Output
        $this->command->info('✅ Berhasil mengisi '.count($permissions).' data master Permission');
    }
}
