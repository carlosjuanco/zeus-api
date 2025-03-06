<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Human;

class RoleSeeder extends Seeder
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
        $role->name = 'Creadores del sistema';
        $role->description = 'Son los usuarios que crean todo el sistema';
        $role->human_id = $human_juan_carlos->id;
        $role->save();
    }
}
