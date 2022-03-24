<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Alamat;
use App\Models\ManagerKebun;
use App\Models\User as ModelsUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class User extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert user
        $admin = ModelsUser::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('12345678'),
            'role_id' => 2,
        ]);

        $mkebun = ModelsUser::create([
            'name' => 'manajer kebun',
            'email' => 'mkebun@mail.com',
            'password' => Hash::make('12345678'),
            'role_id' => 1,
        ]);

        $alamat = Alamat::create([
            'alamat' => 'Rumah Manajer Kebun',
            'regencies_id' => 3271
        ]);

        // insert data admin
        Admin::create([
            'nama' => $admin->name,
            'user_id' => $admin->id
        ]);

        ManagerKebun::create([
            'nama' => $mkebun->name,
            'jenis_kelamin' => 'Laki-Laki',
            'no_hp' => '0812334567890',
            'email' => $mkebun->email,
            'alamat_id' => $alamat->id,
            'user_id' => $mkebun->id
        ]);
    }
}
