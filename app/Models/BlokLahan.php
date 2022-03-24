<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlokLahan extends Model
{
    use HasFactory;

    protected $table = 'blok_lahan';
    public $timestamps = false;

    protected $fillable = [
        'luas_blok', 'periode', 'jumlah_tanaman', 'umur_tanaman', 'tahapan_id'
    ];


    public function projectTanam()
    {
        return $this->belongsTo(ProjectTanam::class, 'project_tanam_id');
    }

    public function catatHarian()
    {
        return $this->hasMany(CatatHarian::class);
    }
}
