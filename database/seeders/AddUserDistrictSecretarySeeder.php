<?php

namespace Database\Seeders;

use App\Models\Human;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AddUserDistrictSecretarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'Secretaria de distrito')->get()->first();

        // Este usuario no se eliminará, ya que quiero ocuparlo de lo nuevo que he aprendido
        // "Cosas nuevas que he aprendido, rol creadores del sistema"
        $user = new User();
        $user->name = 'Secretaria de distrito';
        $user->email = 'secretariadedistrito@gmail.com';
        $user->password = Hash::make('secretariadistrito');
        $user->role_id = $role->id;
        $user->save();

        $human = Human::where('paternal_surname', 'district')->first();
        $human->user_id = $user->id;
        $human->save();
    }
}
