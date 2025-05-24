<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Role;
use App\Models\User;
use App\Models\Human;

class CreateChurchSecretariesToTestWithSeveralChurchesSeeder extends Seeder
{
    /**
     * Crear secretarias de iglesias para hacer pruebas con varias iglesias
     * 
     * Nota: Este archivo no debe de mandarse a llamar desde "DatabaseSeeder.php"
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'Secretaria de iglesia')->get()->first();

        $user = new User();
        $user->name = 'Secretaria de iglesia 2';
        $user->email = 'secretariadeiglesia2@gmail.com';
        $user->password = Hash::make('secretariaiglesia2');
        $user->role_id = $role->id;
        $user->save();

        // si = Secretaria de iglesia
        $humanSi2 = new Human();
        $humanSi2->paternal_surname = "si 2";
        $humanSi2->maternal_surname = "si 2";
        $humanSi2->user_id = $user->id;
        $humanSi2->churche_id = 4;
        $humanSi2->save(); 
    }
}
