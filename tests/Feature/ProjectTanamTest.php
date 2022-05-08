<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\ManagerKebun;
use App\Models\TipeJawaban;
use App\Models\Alamat;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;

class ProjectTanamTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    protected $userAdmin, $userManager, $admin, $manager, $sop, $tahapan, $alamat, $province, $regency;
    
    public function setUp() : void {
        parent::setUp();

        $this->province = Province::create([
            'name' => 'Jawa Timur'
        ]);

        $this->regency = $this->province->regencies()->create([
            'name' => 'Kabupaten Banyuwangi',
        ]);

        $this->userAdmin = User::factory()->create([
            'role_id' => 2
        ]);

        $this->userManager = User::factory()->create([
            'role_id' => 1
        ]);
        
        $this->admin = Admin::factory()->create([
            'nama' => $this->userAdmin->name,
            'user_id' => $this->userAdmin->id
        ]);
     
        $this->alamat = Alamat::factory()->create([
            'alamat' => "demak",
            'regencies_id' => 1,
        ]);

        $this->manager = ManagerKebun::factory()->create([
            'nama' => $this->userManager->name, 
            'jenis_kelamin' => 'Laki-laki', 
            'no_hp' => '081319173324', 
            'email' => $this->userManager->email, 
            'alamat_id' => $this->alamat->id, 
            'user_id' => $this->userManager->id
        ]);

        $this->actingAs($this->userManager, 'api');

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

    public function test_create_project_tanam()
    {
        $this->withoutExceptionHandling(); 

        $json = '{
            "alamat": "demak",
            "regencies_id": ' . $this->regency->id . ',
            "sop_id": ' . $this->sop->id .',
            "blok": [
                {
                    "luas_blok": 10000,
                    "jumlah_tanaman": 100,
                    "umur_tanaman": 1,
                    "periode": 1
                }
            ]
        }';

        $formData = json_decode($json, true);
        $response = $this->post('api/v1/project/create', $formData)
                    ->assertStatus(201);
    }

    public function test_create_project_tanam_error_formData(){
        $json = '{
            "alamat": "demak",
            "regencies_id": 200,
            "sop_id": 200,
            "blok": [
                {
                    "luas_blok": 10000,
                    "jumlah_tanaman": 100,
                    "umur_tanaman": 1,
                    "periode": 1
                }
            ]
        }';

        $formData = json_decode($json, true);
        $response = $this->post('api/v1/project/create', $formData)
                    ->assertStatus(400);
    }

    public function test_delete_project_tanam(){
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $this->sop->id 
        ]);

        $response = $this->delete('api/v1/project/' . $project_tanam->id)
                ->assertStatus(200);
    }

    public function test_delete_project_tanam_error_not_found(){
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $this->sop->id 
        ]);

        $response = $this->delete('api/v1/project/' . "10000")
                ->assertStatus(400);
    }

    public function test_get_project_tanam(){
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $this->sop->id 
        ]);

        $response = $this->get('api/v1/project/')
                ->assertStatus(200);
    }

    public function test_create_bloklahan_for_project_tanam(){
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $this->sop->id 
        ]);

        $formData = [
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1
        ];

        $response = $this->post('api/v1/project/' . $project_tanam->id . '/blok/create', $formData)
            ->assertStatus(201);
    }

    public function test_create_bloklahan_for_project_tanam_error_formData(){
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $this->sop->id 
        ]);

        $formData = [
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1
        ];

        $response = $this->post('api/v1/project/' . $project_tanam->id . '/blok/create', $formData)
            ->assertStatus(400);
    }

    public function test_create_bloklahan_for_project_tanam_error_not_found(){
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $this->sop->id 
        ]);

        $formData = [
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1
        ];

        $response = $this->post('api/v1/project/' . "100000" . '/blok/create', $formData)
            ->assertStatus(400);
    }

    public function test_update_bloklahan_for_project_tanam(){
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $this->sop->id 
        ]);
        
        $blok_lahan = $project_tanam->blokLahan()->create([
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1 
        ]);

        $response = $this->put('api/v1/project/' . $project_tanam->id . '/blok/' . $blok_lahan->id, [
            "luas_blok" => 10000,
            "jumlah_tanaman" => 120,
            "umur_tanaman" => 2,
            "periode" => 2,
            "status" => "selesai"
        ])->assertStatus(200);
    }

    public function test_update_bloklahan_for_project_tanam_error_formData(){
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $this->sop->id 
        ]);
        
        $blok_lahan = $project_tanam->blokLahan()->create([
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1 
        ]);

        $response = $this->put('api/v1/project/' . $project_tanam->id . '/blok/' . $blok_lahan->id, [
            "luas_blok" => 10000,
            "jumlah_tanaman" => 120,
            "umur_tanaman" => 2,
        ])->assertStatus(400);
    }

    public function test_update_bloklahan_for_project_tanam_error_not_found(){
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $this->sop->id 
        ]);
        
        $blok_lahan = $project_tanam->blokLahan()->create([
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1 
        ]);

        $response = $this->put('api/v1/project/' . $project_tanam->id . '/blok/' . "100000", [
            "luas_blok" => 10000,
            "jumlah_tanaman" => 120,
            "umur_tanaman" => 2,
            "periode" => 2,
            "status" => "selesai"
        ])->assertStatus(400);
    }

    public function test_delete_bloklahan_for_project_tanam(){
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $this->sop->id 
        ]);
        
        $blok_lahan = $project_tanam->blokLahan()->create([
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1 
        ]);

        $response = $this->delete('api/v1/project/' . $project_tanam->id . '/blok/' . $blok_lahan->id)
                    ->assertStatus(200);
    }

    public function test_delete_bloklahan_for_project_tanam_error_not_found(){
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $this->sop->id 
        ]);
        
        $blok_lahan = $project_tanam->blokLahan()->create([
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1 
        ]);

        $response = $this->delete('api/v1/project/' . $project_tanam->id . '/blok/' . "10000")
                    ->assertStatus(400);
    }

    public function test_get_bloklahan_for_project_tanam(){
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $this->sop->id 
        ]);

        $response = $this->get('api/v1/project/' . $project_tanam->id . '/blok/')
                    ->assertStatus(200);
    }

    public function test_create_catatan_harian()
    {
        $this->withoutExceptionHandling(); 

        $this->actingAs($this->userAdmin, 'api');
        // dependency tahapan (di sop), blok id

        // create sop
        $path = storage_path('app/sop.json');
        $formData = json_decode(file_get_contents($path), true);
        $sop = $this->post('api/v1/sop', $formData)->decodeResponseJson();
        $sop_data = json_decode($sop->json)->data;
        // dd($sop_data);
        
        // find sop tahapan id
        $sop_tahapan = $this->get('api/v1/sop/' . $sop_data->id .'/tahapan')->decodeResponseJson();
        // dd($sop_tahapan);

        $this->actingAs($this->userManager, 'api');
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $sop_data->id 
        ]);

        $blok_lahan = $project_tanam->blokLahan()->create([
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1 
        ]);
        // dd($blok_lahan->id);
        $formData = '{
            "tahapan_id": 2,
            "catatan": "Another two",
            "panen": false,
            "kegiatan": [
                {
                    "item_pekerjaan_id": 1,
                    "filled": 1,
                    "indikator": [
                        {
                            "indikator_id": 1,
                            "catat_jawaban": "100" 
                        },
                        {
                            "indikator_id": 2,
                            "catat_jawaban": "202" 
                        }
                    ]
                },
                {
                    "item_pekerjaan_id": 2,
                    "filled": 0,
                    "indikator": [
                        {
                            "indikator_id": 3,
                            "catat_jawaban": "100" 
                        },
                        {
                            "indikator_id": 4,
                            "catat_jawaban": "302" 
                        }
                    ]
                }
            ]
        }';
        
        $json = json_decode($formData, true);
        $response = $this->post('/api/v1/blok/' . $blok_lahan->id . '/catat/create', $json)
                    ->assertStatus(201);
    }   

    public function test_create_catatan_harian_error_formData()
    {
        $this->withoutExceptionHandling(); 

        $this->actingAs($this->userAdmin, 'api');
        // dependency tahapan (di sop), blok id

        // create sop
        $path = storage_path('app/sop.json');
        $formData = json_decode(file_get_contents($path), true);
        $sop = $this->post('api/v1/sop', $formData)->decodeResponseJson();
        $sop_data = json_decode($sop->json)->data;
        // dd($sop_data);
        
        // find sop tahapan id
        $sop_tahapan = $this->get('api/v1/sop/' . $sop_data->id .'/tahapan')->decodeResponseJson();
        // dd($sop_tahapan);

        $this->actingAs($this->userManager, 'api');
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $sop_data->id 
        ]);

        $blok_lahan = $project_tanam->blokLahan()->create([
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1 
        ]);

        // error tahapan id not found in db
        $formData = '{
            "tahapan_id": 15,
            "catatan": "Another two",
            "panen": false,
            "kegiatan": [
                {
                    "item_pekerjaan_id": 1,
                    "filled": 1,
                    "indikator": [
                        {
                            "indikator_id": 1,
                            "catat_jawaban": "100" 
                        },
                        {
                            "indikator_id": 2,
                            "catat_jawaban": "202" 
                        }
                    ]
                },
                {
                    "item_pekerjaan_id": 2,
                    "filled": 0,
                    "indikator": [
                        {
                            "indikator_id": 3,
                            "catat_jawaban": "100" 
                        },
                        {
                            "indikator_id": 4,
                            "catat_jawaban": "302" 
                        }
                    ]
                }
            ]
        }';
        
        $json = json_decode($formData, true);
        $response = $this->post('/api/v1/blok/' . $blok_lahan->id . '/catat/create', $json)
                    ->assertStatus(400);
    }   

    public function test_update_catat_harian()
    {
        $this->withoutExceptionHandling(); 

        $this->actingAs($this->userAdmin, 'api');
        // dependency tahapan (di sop), blok id

        // create sop
        $path = storage_path('app/sop.json');
        $formData = json_decode(file_get_contents($path), true);
        $sop = $this->post('api/v1/sop', $formData)->decodeResponseJson();
        $sop_data = json_decode($sop->json)->data;
        // dd($sop_data);
        
        // find sop tahapan id
        $sop_tahapan = $this->get('api/v1/sop/' . $sop_data->id .'/tahapan')->decodeResponseJson();
        // dd($sop_tahapan);

        $this->actingAs($this->userManager, 'api');
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $sop_data->id 
        ]);

        $blok_lahan = $project_tanam->blokLahan()->create([
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1 
        ]);
        // dd($blok_lahan->id);

        $catatan = $blok_lahan->catatHarian()->create([
            "tahapan_id" => 2,
            "catatan" => "Another two",
            "panen" => false,
        ]);

        $response = $this->put('/api/v1/blok/' . $blok_lahan->id . '/catat/' . $catatan->id, [
                        "catatan" => "update nih"
                    ])->assertStatus(200);
    }

    public function test_update_catat_harian_error_not_found()
    {
        $this->withoutExceptionHandling(); 

        $this->actingAs($this->userAdmin, 'api');
        // dependency tahapan (di sop), blok id

        // create sop
        $path = storage_path('app/sop.json');
        $formData = json_decode(file_get_contents($path), true);
        $sop = $this->post('api/v1/sop', $formData)->decodeResponseJson();
        $sop_data = json_decode($sop->json)->data;
        // dd($sop_data);
        
        // find sop tahapan id
        $sop_tahapan = $this->get('api/v1/sop/' . $sop_data->id .'/tahapan')->decodeResponseJson();
        // dd($sop_tahapan);

        $this->actingAs($this->userManager, 'api');
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $sop_data->id 
        ]);

        $blok_lahan = $project_tanam->blokLahan()->create([
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1 
        ]);
        // dd($blok_lahan->id);

        $catatan = $blok_lahan->catatHarian()->create([
            "tahapan_id" => 2,
            "catatan" => "Another two",
            "panen" => false,
        ]);

        $response = $this->put('/api/v1/blok/' . $blok_lahan->id . '/catat/' . "100000", [
                    ])->assertStatus(400);
    }

    public function test_delete_catat_harian()
    {
        $this->withoutExceptionHandling(); 

        $this->actingAs($this->userAdmin, 'api');
        // dependency tahapan (di sop), blok id

        // create sop
        $path = storage_path('app/sop.json');
        $formData = json_decode(file_get_contents($path), true);
        $sop = $this->post('api/v1/sop', $formData)->decodeResponseJson();
        $sop_data = json_decode($sop->json)->data;
        // dd($sop_data);
        
        // find sop tahapan id
        $sop_tahapan = $this->get('api/v1/sop/' . $sop_data->id .'/tahapan')->decodeResponseJson();
        // dd($sop_tahapan);

        $this->actingAs($this->userManager, 'api');
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $sop_data->id 
        ]);

        $blok_lahan = $project_tanam->blokLahan()->create([
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1 
        ]);
        // dd($blok_lahan->id);

        $catatan = $blok_lahan->catatHarian()->create([
            "tahapan_id" => 2,
            "catatan" => "Another two",
            "panen" => false,
        ]);

        $response = $this->delete('/api/v1/blok/' . $blok_lahan->id . '/catat/' . $catatan->id)
                    ->assertStatus(200);
    }

    public function test_delete_catat_harian_error_not_found()
    {
        $this->withoutExceptionHandling(); 

        $this->actingAs($this->userAdmin, 'api');
        // dependency tahapan (di sop), blok id

        // create sop
        $path = storage_path('app/sop.json');
        $formData = json_decode(file_get_contents($path), true);
        $sop = $this->post('api/v1/sop', $formData)->decodeResponseJson();
        $sop_data = json_decode($sop->json)->data;
        // dd($sop_data);
        
        // find sop tahapan id
        $sop_tahapan = $this->get('api/v1/sop/' . $sop_data->id .'/tahapan')->decodeResponseJson();
        // dd($sop_tahapan);

        $this->actingAs($this->userManager, 'api');
        $project_tanam = $this->manager->projectTanam()->create([
            "alamat_id" => $this->alamat->id,
            "manager_kebun_id" => $this->userManager->id ,
            "sop_id" => $sop_data->id 
        ]);

        $blok_lahan = $project_tanam->blokLahan()->create([
            "luas_blok" => 10000,
            "jumlah_tanaman" => 100,
            "umur_tanaman" => 1,
            "periode" => 1 
        ]);
        // dd($blok_lahan->id);

        $catatan = $blok_lahan->catatHarian()->create([
            "tahapan_id" => 2,
            "catatan" => "Another two",
            "panen" => false,
        ]);

        $response = $this->delete('/api/v1/blok/' . $blok_lahan->id . '/catat/' . "100000")
                    ->assertStatus(400);
    }
}
