<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Human;

class LinkThedistricUserWithTheVolcanesChurchSeeder extends Seeder
{
    /**
     * Del usuario "Secretaria de distrito" con rol "Secretaria de distrito", obtener que ID le corresponde de humano
     * para vincularlo a una iglesia aleatoria.
     *
     * @return void
     */
    public function run()
    {
        $churche = Human::where('user_id', 3)->first();
        $churche->churche_id = 4;
        $churche->save();
    }
}
