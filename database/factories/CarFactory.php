<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Car;
use Faker\Generator as Faker;
use App\Data\Cars\AllowedSymbols;

$factory->define(Car::class, function (Faker $faker) {
    
    $allowedSymbols = AllowedSymbols::getLettersArray();

    return [
        'marque' => $this->faker->word,
        'model' => $this->faker->word,
        'color_id' => random_int(1,5),
        'number' => $this->faker->randomElement($allowedSymbols)
            . ' '
            . $this->faker->randomDigit()
            . $this->faker->randomDigit()
            . $this->faker->randomDigit()
            . ' '
            . implode('', $this->faker->randomElements($allowedSymbols, 2))
            . ' '
            . $this->faker->numberBetween(10, 999), 
        'parking_paid' => random_int(0,1) ? false : true,
        'comment' => random_int(0,1) ? '' : $this->faker->sentence()
    ];
});
