<?php

namespace Database\Seeders;

use App\Models\Human;
use Illuminate\Database\Seeder;

use App\Models\Page;

class PageSeeder extends Seeder
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
        $page->name = 'Inicio';
        $page->name_component = 'AppHome';
        $page->human_id = $human_juan_carlos->id;
        $page->save();
    }
}
