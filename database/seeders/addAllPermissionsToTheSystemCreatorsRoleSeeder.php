<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class addAllPermissionsToTheSystemCreatorsRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [ "Visualizar_la_pagina" => true, "Ver_la_pagina_en_el_menu" => true ];

        $role_creators_of_the_system = Role::where('name', 'Creadores del sistema')->first();

        $role_creators_of_the_system->pages->each(function ($page) use ($permissions) {
            if($page->name == "Bienvenido usuario" || $page->name == "Informe mes seleccionado") {
                $permissions["Ver_la_pagina_en_el_menu"] = false;
            } else {
                $permissions["Ver_la_pagina_en_el_menu"] = true;
            }
            $page->pivot->permissions = json_encode($permissions);
            $page->pivot->save();
        });
    }
}
