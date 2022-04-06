<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sop extends Model
{
    use HasFactory;
    protected $table = 'sop'; 
    protected $fillable = [
        'sop_nama', 
        'estimasi_panen', 
        'deskripsi', 
        'foto', 
        'kalkulasi_waktu_panen', 
        'kalkulasi_bobot_panen',
        'jenis_komoditas_id'
    ];

    
    public function admin(){
        return $this-> belongsTo(Admin::class, 'admin_id');
    }

    public function itemPekerjaan(){
        return $this-> hasMany(ItemPekerjaan::class);
    }

    public function projectTanam(){
        return $this-> hasMany(ProjectTanam::class);
    }

    public function komoditas(){
        return $this->belongsTo(JenisKomoditas::class, 'jenis_komoditas_id');
    }

    public function tahapan(){
        return $this->hasMany(Tahapan::class);
    }
}
