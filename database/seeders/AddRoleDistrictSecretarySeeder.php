<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Human;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddRoleDistrictSecretarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Este usuario no se eliminará, ya que quiero ocuparlo de lo nuevo que he aprendido
        // "Cosas nuevas que he aprendido, rol creadores del sistema"
        
        $humanDistrictSecretary = new Human();
        $humanDistrictSecretary->paternal_surname = "district";
        $humanDistrictSecretary->maternal_surname = "district";
        $humanDistrictSecretary->save();

        $human_juan_carlos = Human::where('paternal_surname', 'rojas')->first();

        $role = new Role();
        $role->name = 'Secretaria de distrito';
        $role->description = 'Es la secretaría de distrito';
        $role->human_id = $human_juan_carlos->id;
        $role->save();
    }
}
