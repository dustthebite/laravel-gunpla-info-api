<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ModelKit;
use Faker\Factory as Faker;

class ModelKitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        foreach(range(1, 20) as $kit){
            ModelKit::create([
                'name' => $faker->randomElement([
                    'Gundam RX-78-2', 'Strike Freedom Gundam', 'Unicorn Gundam', 
                    'Zaku II', 'Gundam Exia', 'Gundam Barbatos', 'Nu Gundam'
                ]),
                'height_centimeters' => $faker->randomFloat(2, 14, 30),
                'grade_id' => $faker->numberBetween(1, 4),
                'scale_id' => $faker->numberBetween(1, 4),
                'timeline_id' => $faker->numberBetween(1, 4),
                'recommended_price_yen' => $faker->randomFloat(2, 2000, 50000), 
                'release_date' => $faker->date(),
                'isPBandai' => $faker->boolean(),
            ]);
        }   
    }
}
