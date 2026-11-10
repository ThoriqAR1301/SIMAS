<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\TelescopeServiceProvider;

$providers = [
    AppServiceProvider::class,
    AuthServiceProvider::class,

];

if (app()->environment('local')) {
    $providers[] = TelescopeServiceProvider::class;
}

return $providers;
