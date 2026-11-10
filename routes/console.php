<?php

declare(strict_types=1);

use App\Models\LogAktivitas;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->components->info('Tetap semangat, Bu Aesa!');
    $this->line('  <fg=gray>—</> '.Inspiring::quote());
})->purpose('Menampilkan kutipan penyemangat');

Schedule::command('App\\Console\\Commands\\KirimRekapMingguan')
    ->weeklyOn(1, '07:00')
    ->description('Mengirim email rekap mingguan ke Admin & Pimpinan');

Schedule::command('model:prune', [
    '--model' => [
        LogAktivitas::class,
    ],
])->monthlyOn(1, '02:00')
    ->description('Membersihkan log aktivitas lama');
