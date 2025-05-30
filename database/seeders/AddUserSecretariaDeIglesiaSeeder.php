<?php

namespace Database\Seeders;

use App\Models\Human;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Role;
use App\Models\User;

class AddUserSecretariaDeIglesiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'Secretaria de iglesia')->get()->first();

        // Este usuario no se eliminará, ya que quiero ocuparlo de lo nuevo que he aprendido
        // "Cosas nuevas que he aprendido, rol creadores del sistema"
        $user = new User();
        $user->name = 'Secretaria de iglesia';
        $user->email = 'secretariadeiglesia@gmail.com';
        $user->password = Hash::make('secretariaiglesia');
        $user->role_id = $role->id;
        $user->save();

        $human = Human::where('paternal_surname', 'church')->first();
        $human->user_id = $user->id;
        $human->save();
    }
}
