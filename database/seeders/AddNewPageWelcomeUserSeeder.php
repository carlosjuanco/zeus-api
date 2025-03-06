<?php

namespace Database\Seeders;

use App\Models\Human;
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
        $human_juan_carlos = Human::find(1);

        $page = new Page();
        $page->name = 'Bienvenido usuario';
        $page->name_component = 'WelcomeUser';
        $page->human_id = $human_juan_carlos->id;
        $page->save();

        $role_creators_of_the_system = Role::where('name', 'Creadores del sistema')->get()->first();
        $role_creators_of_the_system->pages()->attach([$page->id]);
    }
}
