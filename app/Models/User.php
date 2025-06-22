<?php

namespace App\Models;
use App\Models\Pegawai;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'akun_user';
    protected $primaryKey = 'id_user';
    public $timestamps = true;

    protected $fillable = [
        'username', 'password', 'nama_pengguna', 'role', 'email',
        'status_akun', 'last_login', 'last_seen', 'email_verified_at'
    ];

    protected $hidden = ['password'];

    // PENTING: Cast ke datetime supaya bisa pakai diffInMinutes()
    protected $casts = [
        'last_login' => 'datetime',
        'last_seen' => 'datetime',
        'email_verified_at' => 'datetime',
    ];
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'id_user', 'id_user');
    }

}
