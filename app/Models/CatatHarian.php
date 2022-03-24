<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatHarian extends Model
{
    use HasFactory;

    protected $table = 'catat_harian';
    protected $fillable = [
        'catatan', 'blok_lahan_id','tahapan_id'
    ];


    public function blokLahan(){
        return $this-> belongsTo(BlokLahan::class, 'blok_lahan_id');
    }

    public function tahapan(){
        return $this-> belongsTo(Tahapan::class, 'tahapan_id');
    }

    public function catatItem(){
        return $this-> hasMany(CatatItem::class);
    }

    public function catatPanen(){
        return $this-> hasMany(Panen::class);
    }
}
