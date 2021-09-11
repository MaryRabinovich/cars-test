<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use DatabaseSeeder;
use App\Color;
use App\Car;

class ApiCarsTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;
    
    public function test_api_cars_exists()
    {
        $response = $this->get('/api/cars');
        $response->assertStatus(200);
    }

    public function test_api_cars_returns_a_json_with_cars_data()
    {
        $this->seed(DatabaseSeeder::class);
        $response = $this->get('/api/cars');
        $response->assertJsonCount(5);
    }

    public function test_api_cars_allows_post_requests()
    {
        $this->seed(DatabaseSeeder::class);
        $response = $this->post('/api/cars', [
            'marque' => 'BMW',
            'model' => '7X',
            'color_id' => 3,
            'number' => 'А 123 ВС 45',
            'parking_paid' => false,
            'comment' => ''
        ]);
        $response->assertStatus(200);
    }

    public function test_api_cars_refuses_cyrillic_latin_exchange_in_car_numbers()
    {
        $this->seed(DatabaseSeeder::class);
        $response = $this->post('/api/cars', [
            'marque' => 'BMW',
            'model' => '7X',
            'color_id' => 3,
            'number' => 'A 123 BC 45', // latin
            'parking_paid' => false,
            'comment' => '',
        ]);
        $this->assertDatabaseCount('cars', 6);
        $response = $this->post('/api/cars', [
            'marque' => 'BMW',
            'model' => '7X',
            'color_id' => 3,
            'number' => 'А 123 ВС 45', // cyrillic
            'parking_paid' => false,
            'comment' => '',
        ]);
        $this->assertDatabaseCount('cars', 6);
        $response->assertStatus(200);
    }

    public function test_api_cars_refuses_latinCapital_latinSmall_exchange_in_car_numbers()
    {
        $this->seed(DatabaseSeeder::class);
        $response = $this->post('/api/cars', [
            'marque' => 'BMW',
            'model' => '7X',
            'color_id' => 3,
            'number' => 'A 123 BC 45', // latin capital
            'parking_paid' => false,
            'comment' => '',
        ]);
        $this->assertDatabaseCount('cars', 6);
        $response = $this->post('/api/cars', [
            'marque' => 'BMW',
            'model' => '7X',
            'color_id' => 3,
            'number' => 'a 123 bc 45', // latin small
            'parking_paid' => false,
            'comment' => '',
        ]);
        $this->assertDatabaseCount('cars', 6);
        $response->assertStatus(200);
    }

    /**
     * @dataProvider someCars
     * 
     * @param array $userInput
     * @param array $serverResponse
     * @param number $additionalCarsCount
     * 
     * @throws Throwable
     */
    public function test_cars_api_validation_and_storage($userInput, $serverResponse, $additionalCarsNumber)
    {
        $this->seed(DatabaseSeeder::class);
        $carsCount = Car::count();
        $response = $this->postJson('/api/cars', $userInput);
        $response->assertJson($serverResponse);
        $this->assertTrue(
            Car::count() == $carsCount + $additionalCarsNumber
        );
    }

    /**
     * @return array[] 
     */
    public function someCars()
    {
        return [

            [
                [
                    'marque' => 'BMW',
                    'model' => '7X',
                    'color_id' => 1,
                    'number' => 'A 123 bc 45', 
                    'parking_paid' => true,
                    'comment' => 'Номер латиницей'
                ],
                [
                    'stored' => 'ok',
                ],
                1
            ],

            [
                [
                    'marque' => 'BMW',
                    'model' => '7X',
                    'color_id' => 1,
                    'number' => 'а 123 Вс 45', 
                    'parking_paid' => true,
                    'comment' => 'Номер кириллицей'
                ],
                [
                    'stored' => 'ok',
                ],
                1
            ],

            [
                [
                    'marque' => '',
                    'model' => '7X',
                    'color_id' => 1,
                    'number' => 'A 142 KM 76',
                    'parking_paid' => true,
                    'comment' => 'Миленькая машинка'
                ],
                [
                    'stored' => 'no',
                    'error' => 'Проверьте поля "марка", "модель" и "цвет"'
                ],
                0
            ],

            [
                [
                    'marque' => 'BMW',
                    'model' => '',
                    'color_id' => 1,
                    'number' => 'A 142 KM 76',
                    'parking_paid' => true,
                    'comment' => 'Миленькая машинка'
                ],
                [
                    'stored' => 'no',
                    'error' => 'Проверьте поля "марка", "модель" и "цвет"'
                ],
                0
            ],

            [
                [
                    'marque' => 'BMW',
                    'model' => '7X',
                    'color_id' => 1000,
                    'number' => 'A 142 KM 76',
                    'parking_paid' => true,
                    'comment' => 'Миленькая машинка'
                ],
                [
                    'stored' => 'no',
                    'error' => 'Проверьте поля "марка", "модель" и "цвет"'
                ],
                0
            ],

            [
                [
                    'marque' => 'BMW',
                    'model' => '7X',
                    'color_id' => 1,
                    'number' => 'A142 KM 76',
                    'parking_paid' => true,
                    'comment' => 'Миленькая машинка'
                ],
                [
                    'stored' => 'no',
                    'error' => 'Проверьте номер машины'
                ],
                0
            ],

            [
                [
                    'marque' => 'BMW',
                    'model' => '7X',
                    'color_id' => 1,
                    'number' => 'A 142 K6',
                    'parking_paid' => true,
                    'comment' => 'Миленькая машинка'
                ],
                [
                    'stored' => 'no',
                    'error' => 'Проверьте номер машины'
                ],
                0
            ],

            [
                [
                    'marque' => 'BMW',
                    'model' => '7X',
                    'color_id' => 1,
                    'number' => 'A 142 KM 76000',
                    'parking_paid' => true,
                    'comment' => 'Миленькая машинка'
                ],
                [
                    'stored' => 'no',
                    'error' => 'Проверьте номер машины'
                ],
                0
            ],
            
        ];
    }
}
