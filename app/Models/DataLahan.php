<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataLahan extends Model
{
    use HasFactory;

    protected $table = 'data_lahan';
    protected $fillable = [
        'alamat_id','koordinat_map_lahan', 'jumlah_tanaman', 'umur_tanaman', 'luas_lahan'
    ];


    public function projectTanam(){
        return $this-> belongsToMany(ProjectTanam::class, 'projectTanam_id');
    }
    
    public function alamat(){
        return $this-> belongsTo(Alamat::class, 'alamat_id');
    }

    public function blokLahan(){
        return $this-> hasMany(BlokLahan::class);
    }

}
