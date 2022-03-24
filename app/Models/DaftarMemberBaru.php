<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarMemberBaru extends Model
{
    use HasFactory;
    protected $table = 'daftar_member_baru';
    protected $fillable = [
        'nama', 'jenis_kelamin', 'no_hp', 'email', 'alamat_id', 'status'
    ];

    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'alamat_id');
    }
}
