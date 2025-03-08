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
        $human_juan_carlos = Human::find(1);

        $role = new Role();
        $role->name = 'Secretaria de iglesia';
        $role->description = 'Es la secretaría de iglesia';
        $role->human_id = $human_juan_carlos->id;
        $role->save();
    }
}
