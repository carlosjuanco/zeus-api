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
        // Vincularlo con la iglesia "Centenario"
        $humanSi2->churche_id = 1;
        $humanSi2->save(); 

        $user2 = new User();
        $user2->name = 'Secretaria de iglesia 3';
        $user2->email = 'secretariadeiglesia3@gmail.com';
        $user2->password = Hash::make('secretariaiglesia3');
        $user2->role_id = $role->id;
        $user2->save();

        // si = Secretaria de iglesia
        $humanSi3 = new Human();
        $humanSi3->paternal_surname = "si 3";
        $humanSi3->maternal_surname = "si 3";
        $humanSi3->user_id = $user2->id;
        // Vincularlo con la iglesia "Familias en Crecimiento"
        $humanSi3->churche_id = 2;
        $humanSi3->save(); 

        $user3 = new User();
        $user3->name = 'Secretaria de iglesia 4';
        $user3->email = 'secretariadeiglesia4@gmail.com';
        $user3->password = Hash::make('secretariaiglesia4');
        $user3->role_id = $role->id;
        $user3->save();

        // si = Secretaria de iglesia
        $humanSi4 = new Human();
        $humanSi4->paternal_surname = "si 4";
        $humanSi4->maternal_surname = "si 4";
        $humanSi4->user_id = $user3->id;
        // Vincularlo con la iglesia "Volcanes"
        $humanSi4->churche_id = 4;
        $humanSi4->save();

        $user4 = new User();
        $user4->name = 'Secretaria de iglesia 5';
        $user4->email = 'secretariadeiglesia5@gmail.com';
        $user4->password = Hash::make('secretariaiglesia5');
        $user4->role_id = $role->id;
        $user4->save();

        // si = Secretaria de iglesia
        $humanSi5 = new Human();
        $humanSi5->paternal_surname = "si 5";
        $humanSi5->maternal_surname = "si 5";
        $humanSi5->user_id = $user4->id;
        // Vincularlo con la iglesia "Donaji"
        $humanSi5->churche_id = 5;
        $humanSi5->save();

        $user5 = new User();
        $user5->name = 'Secretaria de iglesia 6';
        $user5->email = 'secretariadeiglesia6@gmail.com';
        $user5->password = Hash::make('secretariaiglesia6');
        $user5->role_id = $role->id;
        $user5->save();

        // si = Secretaria de iglesia
        $humanSi6 = new Human();
        $humanSi6->paternal_surname = "si 6";
        $humanSi6->maternal_surname = "si 6";
        $humanSi6->user_id = $user5->id;
        // Vincularlo con la iglesia "Jardín"
        $humanSi6->churche_id = 6;
        $humanSi6->save();

        $user6 = new User();
        $user6->name = 'Secretaria de iglesia 7';
        $user6->email = 'secretariadeiglesia7@gmail.com';
        $user6->password = Hash::make('secretariaiglesia7');
        $user6->role_id = $role->id;
        $user6->save();

        // si = Secretaria de iglesia
        $humanSi7 = new Human();
        $humanSi7->paternal_surname = "si 7";
        $humanSi7->maternal_surname = "si 7";
        $humanSi7->user_id = $user6->id;
        // Vincularlo con la iglesia "7 Regiones"
        $humanSi7->churche_id = 7;
        $humanSi7->save();

        $user7 = new User();
        $user7->name = 'Secretaria de iglesia 8';
        $user7->email = 'secretariadeiglesia8@gmail.com';
        $user7->password = Hash::make('secretariaiglesia8');
        $user7->role_id = $role->id;
        $user7->save();

        // si = Secretaria de iglesia
        $humanSi8 = new Human();
        $humanSi8->paternal_surname = "si 8";
        $humanSi8->maternal_surname = "si 8";
        $humanSi8->user_id = $user7->id;
        // Vincularlo con la iglesia "San Luis Beltran"
        $humanSi8->churche_id = 8;
        $humanSi8->save();

        $user8 = new User();
        $user8->name = 'Secretaria de iglesia 9';
        $user8->email = 'secretariadeiglesia9@gmail.com';
        $user8->password = Hash::make('secretariaiglesia9');
        $user8->role_id = $role->id;
        $user8->save();

        // si = Secretaria de iglesia
        $humanSi9 = new Human();
        $humanSi9->paternal_surname = "si 9";
        $humanSi9->maternal_surname = "si 9";
        $humanSi9->user_id = $user8->id;
        // Vincularlo con la iglesia "Zogocho"
        $humanSi9->churche_id = 9;
        $humanSi9->save();

        $user9 = new User();
        $user9->name = 'Secretaria de iglesia 10';
        $user9->email = 'secretariadeiglesia10@gmail.com';
        $user9->password = Hash::make('secretariaiglesia10');
        $user9->role_id = $role->id;
        $user9->save();

        // si = Secretaria de iglesia
        $humanSi10 = new Human();
        $humanSi10->paternal_surname = "si 10";
        $humanSi10->maternal_surname = "si 10";
        $humanSi10->user_id = $user9->id;
        // Vincularlo con la iglesia "Huayapam"
        $humanSi10->churche_id = 10;
        $humanSi10->save();

        $user10 = new User();
        $user10->name = 'Secretaria de iglesia 11';
        $user10->email = 'secretariadeiglesia11@gmail.com';
        $user10->password = Hash::make('secretariaiglesia11');
        $user10->role_id = $role->id;
        $user10->save();

        // si = Secretaria de iglesia
        $humanSi11 = new Human();
        $humanSi11->paternal_surname = "si 11";
        $humanSi11->maternal_surname = "si 11";
        $humanSi11->user_id = $user10->id;
        // Vincularlo con la iglesia "Yatareni"
        $humanSi11->churche_id = 11;
        $humanSi11->save();

        $user11 = new User();
        $user11->name = 'Secretaria de iglesia 12';
        $user11->email = 'secretariadeiglesia12@gmail.com';
        $user11->password = Hash::make('secretariaiglesia12');
        $user11->role_id = $role->id;
        $user11->save();

        // si = Secretaria de iglesia
        $humanSi12 = new Human();
        $humanSi12->paternal_surname = "si 12";
        $humanSi12->maternal_surname = "si 12";
        $humanSi12->user_id = $user11->id;
        // Vincularlo con la iglesia "Tres Cruces"
        $humanSi12->churche_id = 12;
        $humanSi12->save();
    }
}
