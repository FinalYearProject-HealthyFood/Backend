<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/data.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Ingredient::create([
                    "name" => $data['0'],
                    "serving_size" => 100,
                    "price" => $data['1'],
                    "protein" => $data['2'],
                    "calories" => $data['3'],
                    "fat" => $data['4'],
                    "sat_fat" => $data['5'],
                    "trans_fat" => $data['6'],
                    "carb" => $data['7'],
                    "fiber" => $data['8'],
                    "sugar" => $data['9'],
                    "cholesterol" => $data['10'],
                    "sodium" => $data['11'],
                    "calcium" => $data['12'],
                    "iron" => $data['13'],
                    "zinc" => $data['14'],
                    "image" => "images/dataseeds/".$data['19'],
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
