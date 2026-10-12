<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id', 'aksi', 'model_type', 'model_id',
        'perubahan', 'ip_address', 'user_agent',
    ];

    protected $casts = ['perubahan' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}