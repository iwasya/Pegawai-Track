<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Pegawai extends Model
{
    use SoftDeletes;
    protected $table = 'pegawai';  // nama tabel sesuai database
    protected $primaryKey = 'id_pegawai';

    // Field yang boleh diisi massal
    protected $fillable = [
        'nip',
        'nama_lengkap',
        'jabatan_id',
        'jenis_kelamin',
        'tanggal_lahir',
        'tanggal_masuk',
        'no_telepon',
        'alamat',
        'id_jabatan',
        'email',
        'status_kerja',
        'foto',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    public function AkunUser()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

}
