<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{
    use HasFactory;
    protected $table = 'admin';
    protected $fillable = [
        'nama', 'user_id'
    ];


    public function sop()
    {
        return $this->hasMany(Sop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function komoditas(){
        return $this->hasMany(JenisKomoditas::class);
    }
}
