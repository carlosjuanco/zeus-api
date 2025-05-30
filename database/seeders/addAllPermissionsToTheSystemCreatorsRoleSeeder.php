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

        $roleOfSystemCreators = Role::where('name', 'Creadores del sistema')->first();

        $roleOfSystemCreators->pages->each(function ($page) use ($permissions) {
            if($page->name == "Bienvenido usuario") {
                $permissions["Ver_la_pagina_en_el_menu"] = false;
            } else {
                $permissions["Ver_la_pagina_en_el_menu"] = true;
            }
            $page->pivot->permissions = json_encode($permissions);
            $page->pivot->save();
        });
    }
}
