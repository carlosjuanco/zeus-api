<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Human;
class AddRoleSecretariaDeIglesiaSeeder extends Seeder
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
        $humanChurchSecretary = new Human();
        $humanChurchSecretary->paternal_surname = "church";
        $humanChurchSecretary->maternal_surname = "church";
        $humanChurchSecretary->save();

        $human_juan_carlos = Human::where('paternal_surname', 'rojas')->first();

        $role = new Role();
        $role->name = 'Secretaria de iglesia';
        $role->description = 'Es la secretaría de iglesia';
        $role->human_id = $human_juan_carlos->id;
        $role->save();
    }
}
