<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Page;
use App\Models\Role;

class AddNewPageWelcomeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page = new Page();
        $page->name = 'Bienvenido usuario';
        $page->name_component = 'WelcomeUser';
        $page->save();

        $role_creators_of_the_system = Role::where('name', 'Creadores del sistema')->get()->first();
        $role_creators_of_the_system->pages()->attach([$page->id]);

        $role_church_secretary = Role::where('name', 'Secretaria de iglesia')->get()->first();
        $role_church_secretary->pages()->attach([$page->id]);
    }
}
