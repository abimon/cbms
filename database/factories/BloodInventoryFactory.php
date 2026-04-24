<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BloodInventoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'din'=>strtoupper(uniqid()),
            'type'=>$this->faker->randomElement(['Whole Blood', 'Platelet', 'Plasma']),
            'volume'=>$this->faker->randomFloat(2,1,3),
            'blood_type'=>$this->faker->randomElement(['A', 'B', 'AB', 'O','NT']),
            'rhesus'=>$this->faker->randomElement(['Positive', 'Negative','NT']),
            'date_collected'=>$this->faker->dateTimeBetween('-5 month','now'),
            'location'=>$this->faker->city(),
            'collection_agency'=>$this->faker->company(),
            'HIV'=>$this->faker->randomElement(['Positive','Negative','NT']),
            'HBV'=>$this->faker->randomElement(['Positive','Negative','NT']),
            'HCV'=>$this->faker->randomElement(['Positive','Negative','NT']),
            'Syphilis'=>$this->faker->randomElement(['Positive','Negative','NT']),
            'Malaria'=>$this->faker->randomElement(['Positive','Negative','NT']),
            'expiry_date'=>$this->faker->dateTimeBetween('now','+5 months'),
            'release_date'=>$this->faker->dateTimeBetween('-5 months','now'),
            'status'=>$this->faker->randomElement(['tested', 'not_tested', 'available', 'used', 'expired']),
        ];
    }
}
