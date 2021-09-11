<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use ColorSeeder;

class ColorTableTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_color_seeder()
    {
        $this->seed(ColorSeeder::class);
        $this->assertDatabaseCount('colors', 5);
    }
}
