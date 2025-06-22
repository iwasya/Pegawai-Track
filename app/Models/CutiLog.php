<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CutiLog extends Model
{
    protected $table = 'cuti_log';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_cuti',
        'id_user',
        'id_pegawai',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_cuti',
        'status',
        'keterangan',
        'aksi',
        'dilakukan_oleh',
        'approved_by',
    ];

    public $timestamps = false; // karena waktu_log sudah default
    // Di dalam Cuti.php
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

}
