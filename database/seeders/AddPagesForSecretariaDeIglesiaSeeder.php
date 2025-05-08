<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Page;
use App\Models\Role;
use App\Models\Human;

class AddPagesForSecretariaDeIglesiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $human_juan_carlos = Human::where('paternal_surname', 'rojas')->first();

        $page = Page::where('name', 'Inicio')->get()->first();

        $page2 = Page::where('name', 'Bienvenido usuario')->get()->first();

        $page3 = new Page();
        $page3->name = 'Capturar informe actual';
        $page3->name_component = 'CapturarInformeActual';
        $page3->human_id = $human_juan_carlos->id;
        $page3->save();

        $page4 = new Page();
        $page4->name = 'Informe por mes';
        $page4->name_component = 'InformePorMes';
        $page4->human_id = $human_juan_carlos->id;
        $page4->save();

        $page5 = new Page();
        $page5->name = 'Informe mes x';
        $page5->name_component = 'InformeMesX';
        $page5->human_id = $human_juan_carlos->id;
        $page5->save();

        $role = Role::where('name', 'Secretaria de iglesia')->get()->first();

        $role->pages()->attach([$page->id, $page2->id, $page3->id, $page4->id, $page5->id]);
    }
}
