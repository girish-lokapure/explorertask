<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExplorerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_all()
    {
        $response = $this->get('/api/explorer');

        $response->assertStatus(200);
        $response->assertEquals("application/json", $response->getHeaders()["Content-Type"][0]);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'name',
                    'amount',
                    'children'
                ]
            ]
        ]);
//        print_r($response->assertStatus(200));
    }

    public function test_client()
    {
        $response = $this->get('/api/explorer', ['client_id' => [1]]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'name',
                    'amount',
                    'children'
                ]
            ]
        ]);


        $response->assertJson([
            'data' => [
                [
                    'type' => 'client',
                    'id' => '1'
                ]
            ]
        ]);

    }
}
