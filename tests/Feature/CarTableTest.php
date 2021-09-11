<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use ColorSeeder;
use CarSeeder;
use App\Car;

class CarTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_car_seeder()
    {
        $this->seed(ColorSeeder::class);
        $this->seed(CarSeeder::class);
        $this->assertDatabaseCount('cars', 5);
    }
    
    public function test_cars_have_colors()
    {
        $this->seed(ColorSeeder::class);
        $this->seed(CarSeeder::class);
        $car = Car::first();
        $this->assertTrue(in_array(
            $car->color->name,
            [
                'Белый',
                'Жёлтый',
                'Красный',
                'Синий',
                'Чёрный',
            ]
        ));
    }
}
