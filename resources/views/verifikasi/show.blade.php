<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Surat</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Surat Terverifikasi</h1>
            <p class="text-sm text-gray-500 mt-1">Dokumen Ini Sah Dikeluarkan Oleh Sekolah</p>
        </div>

        <dl class="space-y-3 text-sm border-t pt-4">
            <div class="flex justify-between">
                <dt class="text-gray-500">Nomor Surat</dt>
                <dd class="font-mono font-medium">{{ $pengajuan->nomor_surat }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Tanggal</dt>
                <dd>{{ $pengajuan->tanggal_pelaksanaan->translatedFormat('d F Y') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Pengaju</dt>
                <dd>{{ $pengajuan->user->name }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Kegiatan</dt>
                <dd class="text-right max-w-[60%]">{{ $pengajuan->nama_kegiatan }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Status</dt>
                <dd class="text-green-600 font-medium">Disetujui</dd>
            </div>
        </dl>

        <p class="text-xs text-gray-400 text-center mt-6">
            Diverifikasi Pada {{ now()->translatedFormat('d F Y H:i') }}
        </p>
    </div>
</body>
</html>