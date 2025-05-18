<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Churche;

class AddRecordsForChurchesInTheChurchesTableSeeder extends Seeder
{
    public function run()
    {
        // Arreglo con 12 registros de iglesias
        $churchesData = [
            ['name' => 'Centenario', 'human_id' => 1],
            ['name' => 'Familias en Crecimiento', 'human_id' => 1],
            ['name' => 'Las Flores', 'human_id' => 1],
            ['name' => 'Volcanes', 'human_id' => 1],
            ['name' => 'Donaji', 'human_id' => 1],
            ['name' => 'Jardín', 'human_id' => 1],
            ['name' => '7 Regiones', 'human_id' => 1],
            ['name' => 'San Luis Beltran', 'human_id' => 1],
            ['name' => 'Zogocho', 'human_id' => 1],
            ['name' => 'Huayapam', 'human_id' => 1],
            ['name' => 'Yatareni', 'human_id' => 1],
            ['name' => 'Tres Cruces', 'human_id' => 1],
        ];

        // Insertar los registros
        foreach ($churchesData as $data) {
            Churche::create($data);
        }
    }
}

