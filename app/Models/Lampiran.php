<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lampiran extends Model
{
    protected $table = 'lampiran';

    protected $fillable = [
        'attachable_type', 'attachable_id',
        'nama_file', 'path', 'mime_type', 'ukuran',
    ];

    public function attachable()
    {
        return $this->morphTo();
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function ukuranFormat(): string
    {
        $bytes = $this->ukuran;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}