<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Page;
use App\Models\Role;

class AddPagesForSecretariaDeIglesiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page = Page::where('name', 'Inicio')->get()->first();

        $page2 = new Page();
        $page2->name = 'Capturar informe actual';
        $page2->name_component = 'CapturarInformeActual';
        $page2->save();

        $page3 = new Page();
        $page3->name = 'Informe por mes';
        $page3->name_component = 'InformePorMes';
        $page3->save();

        $role = Role::where('name', 'Secretaria de iglesia')->get()->first();

        $role->pages()->attach([$page->id, $page2->id, $page3->id]);
    }
}
