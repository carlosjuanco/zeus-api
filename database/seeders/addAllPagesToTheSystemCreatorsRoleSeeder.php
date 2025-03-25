<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class addAllPagesToTheSystemCreatorsRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = Page::whereIn('id', [3, 4, 5, 6, 7, 8, 9])->get();

        $role = Role::where('name', 'Creadores del sistema')->first();

        $role->pages()->attach($pages->pluck('id'));
    }
}
