<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Page;
use App\Models\Role;
use App\Models\Human;

class CreateAdministrativeRolePagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $human_juan_carlos = Human::find(1);

        $page = Page::where('name', 'Inicio')->first();

        $page2 = Page::where('name', 'Bienvenido usuario')->first();

        $page3 = new Page();
        $page3->name = 'Comunidades';
        $page3->name_component = 'AppComunidad';
        $page3->human_id = $human_juan_carlos->id;
        $page3->save();

        $page4 = new Page();
        $page4->name = 'Escuelas';
        $page4->name_component = 'AppEscuela';
        $page4->human_id = $human_juan_carlos->id;
        $page4->save();

        $page5 = new Page();
        $page5->name = 'Profesores';
        $page5->name_component = 'Profesor';
        $page5->human_id = $human_juan_carlos->id;
        $page5->save();

        $page6 = new Page();
        $page6->name = 'Reporte por comunidad';
        $page6->name_component = 'ReportePorComunidad';
        $page6->human_id = $human_juan_carlos->id;
        $page6->save();

        $role = Role::where('name', 'Administrativo')->first();

        $role->pages()->attach([$page->id, $page2->id, $page3->id, $page4->id, $page5->id, $page6->id]);
    }
}
