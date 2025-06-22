<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class akun_User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Nama tabel disesuaikan
    protected $table = 'akun_user';

    // Primary key disesuaikan
    protected $primaryKey = 'id_user';

    // Tipe primary key bukan string
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Kolom yang boleh diisi secara massal
     */
    protected $fillable = [
        'username',
        'password',
        'nama_pengguna',
        'role',
        'last_login',
        'email',
        'email_verified_at',
        'status_akun',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe data otomatis konversi
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    /**
     * Timestamps otomatis (created_at & updated_at)
     */
    public $timestamps = true;

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'id_user', 'id_user');
    }

}
