<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/meal.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Meal::create([
                    "name" => $data['1'],
                    "serving_size" => 350,
                    "price" => $data['7'],
                    "protein" => $data['4'],
                    "calories" => $data['3'],
                    "fat" => $data['5'],
                    "carb" => $data['6'],
                    "image" => "images/dataseeds2/".$data['2'],
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
