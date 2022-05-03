<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Admin;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KomoditasTest extends TestCase
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

    public function test_create_new_komoditas()
    {
        // $this->withoutExceptionHandling();
        // $user = User::factory()->create([
        //     "role_id" => 2
        // ]);
        // // dd($user);
        // $admin = Admin::factory()->create([
        //     "nama" => $user->name,
        //     "user_id" => $user->id
        // ]);
        // $this->actingAs($user, 'api');

        $formData = [
            "nama_komoditas" => "Kelengkeng",
            "foto" => null,
        ];
        
        $response = $this->post('api/v1/komoditas', $formData)
                        ->assertStatus(201);
    }

    public function test_create_new_komoditas_error()
    {
        $formData = [
            "foto" => null,
        ];

        $response = $this->post('api/v1/komoditas', $formData)
                        ->assertStatus(400);
    }

    public function test_update_komoditas()
    {
        $komoditas = $this->admin->komoditas()->firstOrCreate([
            'nama_komoditas' => 'Kelengkeng'
        ]);

        $response = $this->put('api/v1/komoditas/' . $komoditas->id, [
            'nama_komoditas' => 'Kelengkeng',
            'foto' => null 
        ])->assertStatus(200);
    }

    public function test_update_komoditas_error_formdata()
    {
        $komoditas = $this->admin->komoditas()->firstOrCreate([
            'nama_komoditas' => 'Kelengkeng'
        ]);

        $response = $this->put('api/v1/komoditas/' . $komoditas->id, [
            'foto' => null 
        ])->assertStatus(400);
    }

    public function test_update_komoditas_error_not_found()
    {
        $komoditas = $this->admin->komoditas()->firstOrCreate([
            'nama_komoditas' => 'Kelengkeng'
        ]);

        $response = $this->put('api/v1/komoditas/' . "1000000", [
            'nama_komoditas' => 'Kelengkeng',
            'foto' => null 
        ])->assertStatus(404);
    }

    public function test_delete_komoditas()
    {
        $komoditas = $this->admin->komoditas()->firstOrCreate([
            'nama_komoditas' => 'Kelengkeng'
        ]);

        $response = $this->delete('api/v1/komoditas/' . $komoditas->id)
                        ->assertStatus(200);
    }

    public function test_delete_komoditas_error_not_found()
    {
        $response = $this->delete('api/v1/komoditas/' . "1000000")
                        ->assertStatus(404);
    }

    public function test_get_komoditas()
    {
        $komoditas = $this->admin->komoditas()->firstOrCreate([
            'nama_komoditas' => 'Kelengkeng'
        ]);

        $response = $this->get('api/v1/komoditas')
                        ->assertStatus(200);
    }

    public function test_get_komoditas_by_id()
    {
        $komoditas = $this->admin->komoditas()->firstOrCreate([
            'nama_komoditas' => 'Kelengkeng'
        ]);

        $response = $this->get('api/v1/komoditas/' . $komoditas->id)
                        ->assertStatus(200);
    }

    public function test_get_komoditas_by_id_error_not_found()
    {
        $response = $this->get('api/v1/komoditas/' . "1000000")
                        ->assertStatus(404);
    }
}
