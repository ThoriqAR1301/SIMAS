<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class FileUploadService
{
    private string $defaultDisk;

    public function __construct()
    {
        $this->defaultDisk = config('filesystems.default', 'public');
    }

    /**
     * Menyimpan satu file ke storage.
     *
     * @return array{nama_file: string, path: string, mime_type: string|null, ukuran: int}
     *
     * @throws RuntimeException
     */
    public function simpan(UploadedFile $file, string $folder, ?string $disk = null): array
    {
        $disk = $disk ?? $this->defaultDisk;

        $namaAsli = strip_tags($file->getClientOriginalName());

        $ext = strtolower($file->getClientOriginalExtension() ?: 'bin');

        $namaSimpan = Str::uuid()->toString().'.'.$ext;

        $path = $file->storeAs($folder, $namaSimpan, $disk);

        if ($path === false) {
            throw new RuntimeException('Gagal menyimpan file ke storage. Pastikan folder writable.');
        }

        return [
            'nama_file' => $namaAsli,
            'path' => $path,
            'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
            'ukuran' => $file->getSize(),
        ];
    }

    public function hapus(?string $path, ?string $disk = null): void
    {
        if (empty($path)) {
            return;
        }

        $disk = $disk ?? $this->defaultDisk;

        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }

    /**
     * Menyimpan banyak file sekaligus.
     *
     * @param  array<int, mixed>  $files
     * @return array<int, array{nama_file: string, path: string, mime_type: string|null, ukuran: int}>
     */
    public function simpanBanyak(array $files, string $folder, ?string $disk = null): array
    {
        $result = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $result[] = $this->simpan($file, $folder, $disk);
            }
        }

        return $result;
    }
}
