<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

describe('System Health Check', function () {

    test('framework is running perfectly', function () {
        expect(true)->toBeTrue();
    });

    test('database connection is healthy', function () {
        try {
            DB::connection()->getPdo();
            $isConnected = true;
        } catch (\Exception $e) {
            $isConnected = false;
        }

        expect($isConnected)->toBeTrue('Gagal terhubung ke database MySQL/SQLite');
    });

    test('cache system is working', function () {
        $key = 'health_check_key';
        $value = 'simas_premium';

        Cache::put($key, $value, 60);
        $retrieved = Cache::get($key);

        expect($retrieved)->toBe($value, 'Sistem cache (Redis/Database) tidak berfungsi');
        
        Cache::forget($key); 
    });

    test('storage disk is writable', function () {
        $disk = Storage::disk('public');
        $testFile = 'health-check/test.txt';
        $content = 'SIMAS Storage Check';

        $written = $disk->put($testFile, $content);
        expect($written)->toBeTrue('Storage tidak dapat ditulis (Permission Denied)');

        $exists = $disk->exists($testFile);
        expect($exists)->toBeTrue('File tidak ditemukan setelah ditulis');

        $disk->delete($testFile);
    });

    test('php version meets minimum requirements', function () {
        expect(version_compare(PHP_VERSION, '8.2.0', '>='))
            ->toBeTrue("Versi PHP saat ini (" . PHP_VERSION . ") terlalu rendah. Minimal 8.2");
    });

    test('required php extensions are installed', function () {
        expect(extension_loaded('gd'))->toBeTrue('Ekstensi GD belum terinstall (dibutuhkan untuk QR Code)');
        expect(extension_loaded('zip'))->toBeTrue('Ekstensi ZIP belum terinstall (dibutuhkan untuk Export Excel)');
    });
});