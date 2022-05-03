<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Admin;
use App\Models\TipeJawaban;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SopTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected $user, $admin, $komoditas;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::factory()->create([
            'role_id' => 2
        ]);
        
        $this->admin = Admin::factory()->create([
            'nama' => $this->user->name,
            'user_id' => $this->user->id
        ]);

        $this->actingAs($this->user, 'api');
        $this->komoditas = $this->admin->komoditas()->firstOrCreate([
            'nama_komoditas' => 'Kelengkeng'
        ]);
        TipeJawaban::insert([
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

    public function test_create_sop()
    {
        $this->withoutExceptionHandling();

        $path = storage_path('app/sop.json');
        $formData = json_decode(file_get_contents($path), true);
        
        $response = $this->post('api/v1/sop', $formData)
                        ->assertStatus(201);
    }

    public function test_create_sop_error_formData()
    {
        // jenis_komoditas_id not found
        $json = '{
            "estimasi_panen": "18 bulan",
            "jenis_komoditas_id": 12,
            "deskripsi": "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen": "1.7",
            "kalkulasi_waktu_panen": "112",
            "detail_sop": [
                {
                    "nama_tahapan": "Persiapan Lahan",
                    "kegiatan": [
                        {
                            "nama_kegiatan": "Pembersihan lahan dari rumput/semak serta bahan pengotor lainnya",
                            "durasi_waktu": "3 hok",
                            "indikator": [
                                {
                                    "nama_indikator": "Luas pengerjaan",
                                    "tipe_jawaban_id": 1
                                },
                                {
                                    "nama_indikator": "Jumlah orang yang bekerja",
                                    "tipe_jawaban_id": 2
                                }
                            ]
                        },
                        {
                            "nama_kegiatan": "Penggemburan lahan sedalam 30 cm dengan cangkul atau bajak traktor",
                            "durasi_waktu": "3 hok",
                            "indikator": [
                                {
                                    "nama_indikator": "Luas pengerjaan",
                                    "tipe_jawaban_id": 1
                                },
                                {
                                    "nama_indikator": "Jumlah orang yang bekerja",
                                    "tipe_jawaban_id": 2
                                }
                            ]
                        }
                    ]
                },
                {
                    "nama_tahapan": "Penyiapan benih",
                    "kegiatan": [
                        {
                            "nama_kegiatan": "Penyiapan benih 1",
                            "durasi_waktu": "1 hok",
                            "indikator": [
                                {
                                    "nama_indikator": "Luas pengerjaan",
                                    "tipe_jawaban_id": 1
                                },
                                {
                                    "nama_indikator": "Jumlah pembungkus",
                                    "tipe_jawaban_id": 3
                                }
                            ]
                        }
                    ]
                }
            ]
        }';
        $formData = json_decode($json, true);
        
        $response = $this->post('api/v1/sop', $formData)
                        ->assertStatus(400);
    }

    public function test_update_sop()
    {
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $response = $this->put('api/v1/sop/' . $sop->id, [
            "estimasi_panen" => "12 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.71",
            "kalkulasi_waktu_panen" => "132",
        ])->assertStatus(200);
    }

    public function test_update_sop_error_formData()
    {
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $response = $this->put('api/v1/sop/' . $sop->id, [
            "estimasi_panen" => "12 bulan",
            "jenis_komoditas_id" => 2,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.71",
            "kalkulasi_waktu_panen" => "132",
        ])->assertStatus(400);
    }

    public function test_update_sop_error_not_found()
    {
        $response = $this->put('api/v1/sop/' . "1000000", [
            "estimasi_panen" => "12 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.71",
            "kalkulasi_waktu_panen" => "132",
        ])->assertStatus(400);
    }

    public function test_delete_sop()
    {
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $response = $this->delete('api/v1/sop/' . $sop->id)
                        ->assertStatus(200);
    }

    public function test_delete_sop_error_not_found()
    {
        $response = $this->delete('api/v1/sop/' . "10000")
                        ->assertStatus(400);
    }

    public function test_get_sop()
    {
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $response = $this->get('api/v1/sop/' . $sop->id)
                        ->assertStatus(200);
    }

    public function test_get_sop_with_query_params()
    {
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah Kelengkeng",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $response = $this->json('GET', 'api/v1/sop', ['komoditas' => 'kelengkeng'])
                        ->assertStatus(200);
    }

    
}
