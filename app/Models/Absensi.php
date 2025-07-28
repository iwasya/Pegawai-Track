<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi_detail';  // sesuaikan dengan nama tabel kamu
    protected $primaryKey = 'id_absen';  // sesuaikan primary key

    protected $fillable = [
        'id_pegawai',
        'id_jadwal',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'tuker_sift',
        'status',
        'keterangan',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }
}
