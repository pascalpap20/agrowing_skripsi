<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Admin;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TahapanTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected $user, $admin;

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
    }   

    public function test_create_tahapan_for_sop() 
    {
        $this->withoutExceptionHandling();  
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $formData = [
            "nama_tahapan" => "Pengolahan",
        ];

        $response = $this->post('api/v1/sop/'.$sop->id . '/tahapan', $formData)
                        ->assertStatus(201);
    }

    public function test_create_tahapan_for_sop_error_formData() 
    {
        $this->withoutExceptionHandling();  
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $formData = [
        ];

        $response = $this->post('api/v1/sop/'.$sop->id . '/tahapan', $formData)
                        ->assertStatus(400);
    }

    public function test_create_tahapan_for_sop_error_not_found() 
    {
        $formData = [
            "nama_tahapan" => "Pengolahan",
        ];

        $response = $this->post('api/v1/sop/'. "10000000" . '/tahapan', $formData)
                        ->assertStatus(400);
    }

    public function test_update_tahapan_for_sop()
    {
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $tahapan = $sop->tahapan()->firstOrCreate([
            "nama_tahapan" => "Pengolahan",
        ]);

        $formData = [
            "nama_tahapan" => "Penanaman",
        ];

        $response = $this->put('api/v1/sop/'.$sop->id . '/tahapan/' .$tahapan->id , $formData)
                        ->assertStatus(200);
    }

    public function test_update_tahapan_for_sop_error_formData()
    {
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $tahapan = $sop->tahapan()->firstOrCreate([
            "nama_tahapan" => "Pengolahan",
        ]);

        $formData = [
        ];

        $response = $this->put('api/v1/sop/'.$sop->id . '/tahapan/' .$tahapan->id , $formData)
                        ->assertStatus(400);
    }

    public function test_update_tahapan_for_sop_error_not_found()
    {
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $formData = [
        ];

        $response = $this->put('api/v1/sop/'.$sop->id . '/tahapan/' . '1000000' , $formData)
                        ->assertStatus(400);
    }

    public function test_delete_tahapan_for_sop()
    {
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $tahapan = $sop->tahapan()->firstOrCreate([
            "nama_tahapan" => "Pengolahan",
        ]);

        $response = $this->delete('api/v1/sop/'.$sop->id . '/tahapan/' .$tahapan->id)
                        ->assertStatus(200);
    }

    public function test_delete_tahapan_for_sop_error_not_found()
    {
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $response = $this->delete('api/v1/sop/'.$sop->id . '/tahapan/' . '1000000')
                        ->assertStatus(400);
    }
    
    public function test_get_tahapan_for_sop()
    {
        $sop = $this->admin->sop()->firstOrCreate([
            "estimasi_panen" => "18 bulan",
            "jenis_komoditas_id" => 1,
            "deskripsi" => "SOP untuk budidaya tanaman buah salak",
            "kalkulasi_bobot_panen" => "1.721",
            "kalkulasi_waktu_panen" => "111",
        ]);

        $response = $this->get('api/v1/sop/'.$sop->id . '/tahapan/' )
                        ->assertStatus(200);
    }
}
