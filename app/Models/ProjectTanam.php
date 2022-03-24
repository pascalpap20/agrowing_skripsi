<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTanam extends Model
{
    use HasFactory;

    protected $table = 'project_tanam';
    protected $fillable = [
        'sop_id', 'tahapan_sop_id', 'alamat_id', 'status'
    ];


    public function managerKebun()
    {
        return $this->belongsTo(ManagerKebun::class, 'manager_kebun_id');
    }

    public function sop()
    {
        return $this->belongsTo(Sop::class, 'sop_id');
    }

    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'alamat_id');
    }

    public function blokLahan()
    {
        return $this->hasMany(BlokLahan::class);
    }

    public function tahapanSop()
    {
        return $this->hasOne(Tahapan::class, 'id', 'tahapan_sop_id');
    }
}
