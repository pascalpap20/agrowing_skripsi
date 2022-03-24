<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class Tahapan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tahapan')->insert([
            [
        	    'nama_tahapan' => 'Tahap Persiapan Lahan dan Tanaman',
            ],
            [
                'nama_tahapan' => 'Tahap Perawatan Tanaman Belum Menghasilkan',
            ],
            [
                'nama_tahapan' => 'Tahap Perawatan Tanaman Menghasilkan',
            ],
            [
                'nama_tahapan' => 'Tahap Pemanenan',
            ],
    ]);
    }
}
