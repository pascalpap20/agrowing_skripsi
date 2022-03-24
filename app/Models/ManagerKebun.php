<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerKebun extends Model
{
    use HasFactory;
    protected $table = 'manager_kebun';
    protected $fillable = [
        'nama', 'jenis_kelamin', 'no_hp', 'email', 'alamat_id', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projectTanam()
    {
        return $this->hasMany(ProjectTanam::class);
    }

    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'alamat_id');
    }
}
