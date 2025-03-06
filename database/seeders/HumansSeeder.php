<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Human;

class HumansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $human_juan = new Human();
        $human_juan->paternal_surname = "Rojas";
        $human_juan->maternal_surname = "Garcia";
        $human_juan->save();
    }
}
