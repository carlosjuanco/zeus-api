<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddPagesForRoleDistrictSecretarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page = Page::where('name', 'Inicio')->get()->first();

        $page2 = Page::where('name', 'Bienvenido usuario')->get()->first();

        $page3 = new Page();
        $page3->name = 'Informe mensual actual';
        $page3->name_component = 'InformeMensualActual';
        $page3->save();

        $page4 = new Page();
        $page4->name = 'Informe por meses';
        $page4->name_component = 'InformePorMeses';
        $page4->save();

        $page5 = new Page();
        $page5->name = 'Informe mes seleccionado';
        $page5->name_component = 'InformeMesSeleccionado';
        $page5->save();

        $page6 = new Page();
        $page6->name = 'Apertura de mes';
        $page6->name_component = 'AperturaDeMes';
        $page6->save();

        $role = Role::where('name', 'Secretaria de distrito')->get()->first();

        $role->pages()->attach([$page->id, $page2->id, $page3->id, $page4->id, $page5->id, $page6->id]);
    }
}
