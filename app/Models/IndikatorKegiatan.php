<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorKegiatan extends Model
{
    use HasFactory;
    protected $table = 'indikator_kegiatan'; 
    public $timestamps = false;

    protected $fillable = [
        'nama_indikator','tipe_jawaban_id'
    ];


    public function itemPekerjaan(){
        return $this-> belongsTo(ItemPekerjaan::class, 'item_pekerjaan_id');
    }

    public function tipeJawaban(){
        return $this-> belongsTo(TipeJawaban::class, 'tipe_jawaban_id');
    }

    public function catatIndikator(){
        return $this-> hasMany(catatIndikator::class);
    }
}
