<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tahapan extends Model
{
    use HasFactory;
    protected $table = 'tahapan';
    public $timestamps = false;
    protected $fillable = [
        'nama_tahapan',
        'sop_id',
        'admin_id'
    ];
    public function itemPekerjaan(){
        return $this-> hasMany(ItemPekerjaan::class);
    }

    public function catatHarian(){
        return $this-> hasMany(CatatHarian::class);
    }

    public function sop(){
        return $this->belongsTo(Sop::class, 'sop_id');
    }
}
