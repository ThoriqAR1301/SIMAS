<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\PengajuanSurat;
use App\Models\User;
use App\Policies\PengajuanSuratPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Pemetaan Model ke Policy-nya masing-masing.
     * Jika Anda memiliki Policy lain di masa depan, daftarkan di sini.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        PengajuanSurat::class => PengajuanSuratPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function (User $user, string $ability) {
            if ($user->isPimpinan()) {
                return true;
            }

            return null;
        });

        Gate::define('manage-pengaturan', function (User $user) {
            return $user->isAdminTu();
        });

        Gate::define('view-laporan', function (User $user) {
            return $user->isAdminTu() || $user->isPimpinan();
        });

        Gate::define('view-audit-log', function (User $user) {
            return $user->isPimpinan();
        });
    }
}
