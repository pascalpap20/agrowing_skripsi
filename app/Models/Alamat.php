<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;
    protected $table = 'alamat';
    protected $fillable = [
        'alamat', 'regencies_id'
    ];
    public function projectTanam()
    {
        return $this->hasMany(DataLahan::class);
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regencies_id', 'id');
    }

    public function managerKebun()
    {
        return $this->hasMany(ManagerKebun::class);
    }

    public function daftarMember()
    {
        return $this->hasMany(DaftarMemberBaru::class);
    }
}
