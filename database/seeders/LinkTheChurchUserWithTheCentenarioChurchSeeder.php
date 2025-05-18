<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Human;

class LinkTheChurchUserWithTheCentenarioChurchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $churche = Human::where('user_id', 2)->first();
        $churche->churche_id = 3;
        $churche->save();
    }
}
