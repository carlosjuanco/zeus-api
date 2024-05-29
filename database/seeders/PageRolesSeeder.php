<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Page;

class PageRolesSeeder extends Seeder
{
    /**
     * Inserta en la tabla intermedia role_user
     *
     * @return void
     */
    public function run()
    {
        $role = Role::find(1);
        $page = Page::find(1);
        
        $role->pages()->attach($page->id);
    }
}
