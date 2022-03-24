<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TipeJawaban extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipe_jawaban')->insert([
            [
        	    'tipe' => 'luas lahan',
                'satuan'=> 'm2'
            ],
            [
        	    'tipe' => 'jumlah orang',
                'satuan'=> 'orang'
            ],
            [
        	    'tipe' => 'jumlah pembungkusan',
                'satuan'=> 'bungkus'
            ],
            [
        	    'tipe' => 'hari hujan',
                'satuan'=> 'lainnya'
            ],
            [
        	    'tipe' => 'hari pembungaan',
                'satuan'=> 'lainnya'
            ],
            [
        	    'tipe' => 'jumlah',
                'satuan'=> 'buah'
            ],
    ]);
    }
}
