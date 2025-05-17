<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Human;

class LinkThedistricUserWithTheVolcanesChurchSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
