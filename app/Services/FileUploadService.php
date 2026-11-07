<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function simpan(UploadedFile $file, string $folder): array
    {
        $namaAsli = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        $namaSimpan = Str::uuid().'.'.$ext;

        $path = $file->storeAs($folder, $namaSimpan, 'public');

        return [
            'nama_file' => $namaAsli,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'ukuran' => $file->getSize(),
        ];
    }

    public function hapus(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function simpanBanyak(array $files, string $folder): array
    {
        $result = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $result[] = $this->simpan($file, $folder);
            }
        }

        return $result;
    }
}
