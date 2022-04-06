<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKomoditas extends Model
{
    use HasFactory;

    protected $table = 'jenis_komoditas';
    protected $fillable = [
        'nama_komoditas','foto'
    ];

    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function sop() {
        return $this->hasMany(Sop::class);
    }
}
