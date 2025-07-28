<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianGaji extends Model
{
    use HasFactory;

    protected $table = 'rincian_gaji';
    protected $primaryKey = 'id_rincian';
    protected $guarded = [];

    public function gaji() {
        return $this->belongsTo(Gaji::class, 'id_gaji');
    }

    public function jabatan() {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

}
