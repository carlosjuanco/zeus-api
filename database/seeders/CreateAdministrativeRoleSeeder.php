<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Human;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateAdministrativeRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrative = new Human();
        $administrative->paternal_surname = "administrative";
        $administrative->maternal_surname = "administrative";
        $administrative->save();

        $role = new Role();
        $role->name = 'Administrativo';
        $role->description = 'Es la secretaria';
        $role->human_id = $administrative->id;
        $role->save();
    }
}
