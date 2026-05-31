<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BloodInventoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'din'=>$this->faker->unique()->numerify('DIN-KE-26-####'),
            'type'=>$this->faker->randomElement(['Whole Blood', 'Platelet', 'Plasma']),
            'volume'=>$this->faker->randomFloat(1,1,3),
            'blood_type'=>$this->faker->randomElement(['A', 'B', 'AB', 'O']),
            'rhesus'=>$this->faker->randomElement(['Positive', 'Negative']),
            'date_collected'=>$this->faker->dateTimeBetween('-3 month','now'),
            'location'=>$this->faker->city(),
            'collection_agency'=>$this->faker->company(),
            'HIV'=>$this->faker->randomElement(['Positive','Negative']),
            'HBV'=>$this->faker->randomElement(['Positive','Negative']),
            'HCV'=>$this->faker->randomElement(['Positive','Negative']),
            'Syphilis'=>$this->faker->randomElement(['Positive','Negative']),
            'Malaria'=>$this->faker->randomElement(['Positive','Negative']),
            'expiry_date'=>$this->faker->dateTimeBetween('+2 months','+5 months'),
            'release_date'=>$this->faker->dateTimeBetween('-5 months','now'),
            'status'=>$this->faker->randomElement(['available', 'used', 'withdrawn']),
        ];
    }
}
