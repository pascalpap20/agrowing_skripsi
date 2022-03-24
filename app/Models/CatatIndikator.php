<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatIndikator extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'catat_indikator';
    protected $fillable = [
        'catat_item_id', 'indikator_id','nama_indikator', 'catat_jawaban'
    ];

    public function catatItem(){
        return $this-> belongsTo(CatatItem::class, 'catat_item_id');
    }

    public function indikator(){
        return $this-> belongsTo(IndikatorKegiatan::class, 'indikator_id');
    }

}
