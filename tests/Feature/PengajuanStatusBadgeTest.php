<?php

declare(strict_types=1);

use App\Enums\StatusPengajuan;

describe('UI Component: Status Badge', function () {

    describe('Label Rendering', function () {
        
        test('renders the correct label for every status', function (StatusPengajuan $status, string $expectedLabel) {
            $this->blade('<x-pengajuan.status-badge :status="$status" />', [
                'status' => $status,
            ])->assertSee($expectedLabel);
        })->with([
            'draft' => [StatusPengajuan::DRAFT, 'Draft'],
            'diajukan' => [StatusPengajuan::DIAJUKAN, 'Menunggu Verifikasi'],
            'diproses' => [StatusPengajuan::DIPROSES, 'Menunggu Persetujuan'],
            'disetujui' => [StatusPengajuan::DISETUJUI, 'Disetujui'],
            'ditolak' => [StatusPengajuan::DITOLAK, 'Ditolak'],
            'selesai' => [StatusPengajuan::SELESAI, 'Selesai'],
        ]);
    });

    describe('Color & Styling', function () {
        
        test('applies the correct color palette for each status', function (StatusPengajuan $status, string $expectedColorClass) {
            $this->blade('<x-pengajuan.status-badge :status="$status" />', [
                'status' => $status,
            ])->assertSee($expectedColorClass, false);
        })->with([
            'draft is slate' => [StatusPengajuan::DRAFT, 'bg-slate-500/10'],
            'diajukan is blue' => [StatusPengajuan::DIAJUKAN, 'bg-blue-500/10'],
            'diproses is amber' => [StatusPengajuan::DIPROSES, 'bg-amber-500/10'],
            'disetujui is emerald' => [StatusPengajuan::DISETUJUI, 'bg-emerald-500/10'],
            'ditolak is red' => [StatusPengajuan::DITOLAK, 'bg-red-500/10'],
            'selesai is indigo' => [StatusPengajuan::SELESAI, 'bg-indigo-500/10'],
        ]);
    });

    describe('Animation State', function () {
        
        test('active statuses have pulse animation', function () {
            $this->blade('<x-pengajuan.status-badge :status="$status" />', [
                'status' => StatusPengajuan::DIAJUKAN,
            ])->assertSee('animate-pulse', false);
        });

        test('inactive statuses do not have pulse animation', function () {
            $this->blade('<x-pengajuan.status-badge :status="$status" />', [
                'status' => StatusPengajuan::DITOLAK,
            ])->assertDontSee('animate-pulse', false);
        });
    });

    describe('Icons and Sizes', function () {
        
        test('renders an SVG icon inside the badge', function () {
            $this->blade('<x-pengajuan.status-badge :status="$status" />', [
                'status' => StatusPengajuan::DISETUJUI,
            ])->assertSee('<svg', false);
        });

        test('supports size lg', function () {
            $this->blade('<x-pengajuan.status-badge :status="$status" size="lg" />', [
                'status' => StatusPengajuan::DISETUJUI,
            ])->assertSee('px-3.5 py-1.5', false);
        });

        test('defaults to size sm', function () {
            $this->blade('<x-pengajuan.status-badge :status="$status" />', [
                'status' => StatusPengajuan::DISETUJUI,
            ])->assertSee('px-2.5 py-1', false);
        });
    });

});