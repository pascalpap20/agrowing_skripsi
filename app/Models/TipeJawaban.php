<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeJawaban extends Model
{
    use HasFactory;
    protected $table = 'tipe_jawaban';
    protected $fillable = [
        'tipe', 'satuan'
    ];

    public function indikatorKegiatan(){
        return $this->hasMany(IndikatorKegiatan::class);
    }
}
