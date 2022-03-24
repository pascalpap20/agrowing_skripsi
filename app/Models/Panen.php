<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panen extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'panen';
    protected $fillable = [
        'catat_harian_id', 'panen_aktual','panen_gradeA', 'panen_gradeB'
    ];

    public function catatHarian(){
        return $this-> belongsTo(CatatHarian::class, 'catat_harian_id');
    }
}
