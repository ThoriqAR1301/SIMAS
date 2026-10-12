<?php

namespace App\Providers;

use App\Models\PengajuanSurat;
use App\Policies\PengajuanSuratPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        PengajuanSurat::class => PengajuanSuratPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}