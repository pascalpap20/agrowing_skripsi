<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlamatTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use RefreshDatabase;

     public function test_show_alamat()
    {
        $response = $this->get('/api/v1/alamat')
                ->assertStatus(200);
    }
}
