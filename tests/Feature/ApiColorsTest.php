<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use ColorSeeder;

class ApiColorsTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_api_colors_returns_colors_list()
    {
        $this->seed(ColorSeeder::class);
        $response = $this->get('/api/colors');
        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }
}
