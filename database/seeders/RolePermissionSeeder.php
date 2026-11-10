<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $rolePermissions = [
            'guru' => [
                'pengajuan.create', 'pengajuan.read', 'pengajuan.update', 'pengajuan.delete',
                'surat_izin.create', 'surat_izin.read',
                'surat_masuk.create', 'surat_masuk.read',
                'disposisi.read', 'disposisi.tindak',
            ],

            'admin_tu' => [
                'pengajuan.read', 'pengajuan.verify',
                'surat_izin.read', 'surat_izin.approve', 'surat_izin.manage',
                'surat_masuk.create', 'surat_masuk.read', 'surat_masuk.update', 'surat_masuk.delete',
                'disposisi.create', 'disposisi.read',
                'folder.manage', 'blanko.manage', 'user.manage', 'pengaturan.manage',
                'laporan.view', 'laporan.export',
            ],

            'pimpinan' => [
                'pengajuan.read', 'pengajuan.approve',
                'surat_izin.read',
                'surat_masuk.read',
                'disposisi.create', 'disposisi.read', 'disposisi.tindak',
                'laporan.view', 'laporan.export',
            ],
        ];

        $allPermissions = Permission::pluck('id', 'name');

        DB::transaction(function () use ($rolePermissions, $allPermissions) {
            foreach ($rolePermissions as $roleName => $permissions) {
                $role = Role::where('name', $roleName)->first();

                if (! $role) {
                    $this->command->error("❌ Role '{$roleName}' tidak ditemukan. Pastikan RoleSeeder sudah berjalan");

                    continue;
                }

                $permissionIds = collect($permissions)
                    ->map(fn ($name) => $allPermissions[$name] ?? null)
                    ->filter()
                    ->values()
                    ->toArray();

                $role->permissions()->sync($permissionIds);

                $this->command->line("-> Role {$roleName} diberikan ".count($permissionIds).' permission');
            }
        });

        $this->command->info('✅ Berhasil memetakan hak akses (Role-Permission) untuk semua role');
    }
}
