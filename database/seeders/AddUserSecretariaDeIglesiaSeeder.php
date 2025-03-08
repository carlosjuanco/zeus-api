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

        $user = new User();
        $user->name = 'Secretaria de iglesia';
        $user->email = 'secretariadeiglesia@gmail.com';
        $user->password = Hash::make('secretariaiglesia');
        $user->role_id = $role->id;
        $user->save();

        $human = Human::find(1);
        $human->user_id = $user->id;
        $human->save();
    }
}
