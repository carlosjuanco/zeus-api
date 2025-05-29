<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetPermissionsOnPagesForChurchSecretarySeeder extends Seeder
{
    /**|
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [ "Visualizar_la_pagina" => true, "Ver_la_pagina_en_el_menu" => true ];

        $role_church_secretary = Role::where('name', 'Secretaria de iglesia')->get()->first();

        $role_church_secretary->pages->each(function ($page) use ($permissions) {
            if($page->name == "Bienvenido usuario" || $page->name == "Informe mes x") {
                $permissions["Ver_la_pagina_en_el_menu"] = false;
            } else {
                $permissions["Ver_la_pagina_en_el_menu"] = true;
            }
            $page->pivot->permissions = json_encode($permissions);
            $page->pivot->save();
        });
    }
}
