<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ItemPekerjaan extends Model
{
    use HasFactory;
    protected $table = 'item_pekerjaan'; 
    public $timestamps = false;
    protected $fillable = [
        'tahapan_sop_id','nama_kegiatan', 'durasi_waktu',
    ];


    public function sop(){
        return $this-> belongsTo(Sop::class, 'sop_id' );
    }

    public function tahapan_sop(){
        return $this-> belongsTo(Tahapan::class, 'tahapan_sop_id');
    }

    public function indikatorKegiatan(){
        return $this-> hasMany(IndikatorKegiatan::class);
    }
}
