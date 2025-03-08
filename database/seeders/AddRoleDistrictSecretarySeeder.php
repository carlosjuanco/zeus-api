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
        $human_juan_carlos = Human::find(1);

        $role = new Role();
        $role->name = 'Secretaria de distrito';
        $role->description = 'Es la secretaría de distrito';
        $role->human_id = $human_juan_carlos->id;
        $role->save();
    }
}
