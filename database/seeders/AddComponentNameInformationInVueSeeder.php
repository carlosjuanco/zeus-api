<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Page;
class AddComponentNameInformationInVueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = Page::all();
        $pages->each(function ($page) {
            if($page->name == 'home') {
                $page->name_component = 'AppHome';
                $page->save();
            }
        });
    }
}
