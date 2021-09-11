<?php

use Illuminate\Database\Seeder;
use App\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Color::create(['name' => 'Белый']);
        Color::create(['name' => 'Жёлтый']);
        Color::create(['name' => 'Красный']);
        Color::create(['name' => 'Синий']);
        Color::create(['name' => 'Чёрный']);
    }
}
