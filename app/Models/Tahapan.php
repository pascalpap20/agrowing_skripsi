<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tahapan extends Model
{
    use HasFactory;
    protected $table = 'tahapan';
    protected $fillable = [
        'nama_tahapan'
    ];

    public function itemPekerjaan(){
        return $this-> hasMany(ItemPekerjaan::class);
    }

    public function catatHarian(){
        return $this-> hasMany(CatatHarian::class);
    }
}
