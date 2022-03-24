<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'catat_item';
    protected $fillable = [
        'catat_harian_id', 'item_pekerjaan', 'filled'
    ];

    public function catatHarian(){
        return $this-> belongsTo(CatatHarian::class, 'catat_harian_id');
    }

    public function catatIndikator(){
        return $this-> hasMany(CatatIndikator::class);
    }
}
