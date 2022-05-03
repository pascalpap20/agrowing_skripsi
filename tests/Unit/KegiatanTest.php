<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Admin;
use App\Models\TipeJawaban;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KegiatanTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected $user, $admin, $sop, $tahapan;

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

        $this->sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $this->tahapan = $this->sop->tahapan()->firstOrCreate([
            "nama_tahapan" => "Pengolahan",
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

    public function test_create_item_pekerjaan_for_sop()
    {
        // dd($this->tahapan->id);
        $this->withoutExceptionHandling(); 
        $json = '{
            "tahapan_sop_id": '. $this->tahapan->id .',
            "nama_kegiatan": "Penyiapan bibit 2",
            "durasi_waktu": "2 hok",
            "indikator": [
                {
                    "nama_indikator": "Tambah indikator 1",
                    "tipe_jawaban_id": 3
                },
                {
                    "nama_indikator": "Tambah indikator 2",
                    "tipe_jawaban_id": 4
                }
            ]
        }';

        $formData = json_decode($json, true);

        $response = $this->post('api/v1/sop/'. $this->sop->id . '/kegiatan' , $formData)
                        ->assertStatus(201);
    }

    public function test_create_item_pekerjaan_for_sop_error_formData()
    {
        // tahapan sop no found
        $json = '{
            "tahapan_sop_id": 2,
            "nama_kegiatan": "Penyiapan bibit 2",
            "durasi_waktu": "2 hok",
            "indikator": [
                {
                    "nama_indikator": "Tambah indikator 1",
                    "tipe_jawaban_id": 3
                },
                {
                    "nama_indikator": "Tambah indikator 2",
                    "tipe_jawaban_id": 4
                }
            ]
        }';

        $formData = json_decode($json, true);

        $response = $this->post('api/v1/sop/'. $this->sop->id . '/kegiatan' , $formData)
                        ->assertStatus(400);
    }

    public function test_create_item_pekerjaan_for_sop_error_not_found()
    {
        // tahapan sop no found
        $json = '{
            "tahapan_sop_id": '. $this->tahapan->id .',
            "nama_kegiatan": "Penyiapan bibit 2",
            "durasi_waktu": "2 hok",
            "indikator": [
                {
                    "nama_indikator": "Tambah indikator 1",
                    "tipe_jawaban_id": 3
                },
                {
                    "nama_indikator": "Tambah indikator 2",
                    "tipe_jawaban_id": 4
                }
            ]
        }';

        $formData = json_decode($json, true);

        $response = $this->post('api/v1/sop/'. "100000" . '/kegiatan' , $formData)
                        ->assertStatus(400);
    }

    public function test_update_item_pekerjaan_for_sop()
    {
        $kegiatan = $this->sop->itemPekerjaan()->create([
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $response = $this->put('api/v1/sop/'. $this->sop->id . '/kegiatan/'. $kegiatan->id, [
            'nama_kegiatan' => 'Penyiapan bibit 2',
            'durasi_waktu' => '2 hok'
        ])->assertStatus(200);
    }

    public function test_update_item_pekerjaan_for_sop_error_formData()
    {
        $kegiatan = $this->sop->itemPekerjaan()->create([   
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $response = $this->put('api/v1/sop/'. $this->sop->id . '/kegiatan/'. $kegiatan->id, [
            'durasi_waktu' => '2 hok'
        ])->assertStatus(400);
    }

    public function test_update_item_pekerjaan_for_sop_error_not_found()
    {
        $kegiatan = $this->sop->itemPekerjaan()->create([   
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $response = $this->put('api/v1/sop/'. $this->sop->id . '/kegiatan/'. '100000', [
            'nama_kegiatan' => 'Penyiapan bibit 2',
            'durasi_waktu' => '2 hok'
        ])->assertStatus(400);
    }

    public function test_delete_item_pekerjaan_for_sop()
    {
        $kegiatan = $this->sop->itemPekerjaan()->create([   
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $response = $this->delete('api/v1/sop/'. $this->sop->id . '/kegiatan/'. $kegiatan->id)
                        ->assertStatus(200);
    }

    public function test_delete_item_pekerjaan_for_sop_error_not_found()
    {
        $kegiatan = $this->sop->itemPekerjaan()->create([   
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $response = $this->delete('api/v1/sop/'. $this->sop->id . '/kegiatan/'. '100000')
                        ->assertStatus(400);
    }

    public function test_create_indikator_for_item_pekerjaan()
    {
        $this->withoutExceptionHandling(); 
        $kegiatan = $this->sop->itemPekerjaan()->create([   
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $response = $this->post('api/v1/kegiatan/' . $kegiatan->id . '/indikator', [
            'nama_indikator' => 'Tambah indikator 1',
            'tipe_jawaban_id' => 3
        ])->assertStatus(201);
    }
    
    public function test_create_indikator_for_item_pekerjaan_error_formData()
    {
        $this->withoutExceptionHandling(); 
        $kegiatan = $this->sop->itemPekerjaan()->create([   
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $response = $this->post('api/v1/kegiatan/' . $kegiatan->id . '/indikator', [
            'nama_indikator' => 'Tambah indikator 1'
        ])->assertStatus(400);
    }

    public function test_create_indikator_for_item_pekerjaan_error_not_found()
    {
        $this->withoutExceptionHandling(); 
        $kegiatan = $this->sop->itemPekerjaan()->create([   
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $response = $this->post('api/v1/kegiatan/' . '100000' . '/indikator', [
            'nama_indikator' => 'Tambah indikator 1',
            'tipe_jawaban_id' => 3
        ])->assertStatus(400);
    }

    public function test_update_indikator_for_item_pekerjaan()
    {
        $kegiatan = $this->sop->itemPekerjaan()->create([   
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $indikator = $kegiatan->indikatorKegiatan()->create([
            'nama_indikator' => 'Tambah indikator 1',
            'tipe_jawaban_id' => 1
        ]);

        $response = $this->put('api/v1/kegiatan/' . $kegiatan->id . '/indikator/' . $indikator->id, [
            'nama_indikator' => 'Tambah indikator 1',
            'tipe_jawaban_id' => 3
        ])->assertStatus(200);
    }

    public function test_update_indikator_for_item_pekerjaan_error_formData()
    {
        $kegiatan = $this->sop->itemPekerjaan()->create([   
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $indikator = $kegiatan->indikatorKegiatan()->create([
            'nama_indikator' => 'Tambah indikator 1',
            'tipe_jawaban_id' => 1
        ]);

        $response = $this->put('api/v1/kegiatan/' . $kegiatan->id . '/indikator/' . $indikator->id, [
            'nama_indikator' => 'Tambah indikator 1'
        ])->assertStatus(400);
    }

    public function test_update_indikator_for_item_pekerjaan_error_notFound()
    {
        $kegiatan = $this->sop->itemPekerjaan()->create([   
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $indikator = $kegiatan->indikatorKegiatan()->create([
            'nama_indikator' => 'Tambah indikator 1',
            'tipe_jawaban_id' => 1
        ]);

        $response = $this->put('api/v1/kegiatan/' . $kegiatan->id . '/indikator/' . '1000000', [
            'nama_indikator' => 'Tambah indikator 1',
            'tipe_jawaban_id' => 3
        ])->assertStatus(400);
    }

    public function test_delete_indikator_for_item_pekerjaan()
    {
        $kegiatan = $this->sop->itemPekerjaan()->create([   
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $indikator = $kegiatan->indikatorKegiatan()->create([
            'nama_indikator' => 'Tambah indikator 1',
            'tipe_jawaban_id' => 1
        ]);

        $response = $this->delete('api/v1/kegiatan/' . $kegiatan->id . '/indikator/' . $indikator->id)
                    ->assertStatus(200);
    }

    public function test_delete_indikator_for_item_pekerjaan_error_not_found()
    {
        $kegiatan = $this->sop->itemPekerjaan()->create([   
            'nama_kegiatan' => 'Penyiapan bibit 1',
            'durasi_waktu' => '1 hok',
            'tahapan_sop_id' => $this->tahapan->id
        ]);

        $indikator = $kegiatan->indikatorKegiatan()->create([
            'nama_indikator' => 'Tambah indikator 1',
            'tipe_jawaban_id' => 1
        ]);

        $response = $this->delete('api/v1/kegiatan/' . $kegiatan->id . '/indikator/' . '1000000')
                    ->assertStatus(400);
    }
}
