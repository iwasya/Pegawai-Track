<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas_pengguna';

    protected $primaryKey = 'id_logaktiv';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'aktivitas',
        'tanggal_waktu',
        'ip_address',
    ];
}
