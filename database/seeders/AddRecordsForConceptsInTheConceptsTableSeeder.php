<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Concept;

class AddRecordsForConceptsInTheConceptsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conceptsData = [
            ['concept' => 'Grupos pequeños', 'human_id' => 1],
            ['concept' => 'Contactos misioneros', 'human_id' => 1],
            ['concept' => 'Estudios acumulados', 'human_id' => 1],
            ['concept' => 'Nuevos estudios', 'human_id' => 1],
            ['concept' => 'Bautismos', 'human_id' => 1],
            ['concept' => 'Total de personas estudiando', 'human_id' => 1],
            ['concept' => 'Total de estudios mensuales', 'human_id' => 1],
            ['concept' => 'Total de bautismos alcanzados', 'human_id' => 1],
            ['concept' => 'Invitados en la campaña de GP', 'human_id' => 1],
            ['concept' => 'Invitados en la campaña de iglesia', 'human_id' => 1],
        ];

        foreach ($conceptsData as $data) {
            Concept::create($data);
        }
    }
}
