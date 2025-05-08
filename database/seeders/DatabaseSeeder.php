<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            HumansSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PageSeeder::class,
            PageRolesSeeder::class,
            AddComponentNameInformationInVueSeeder::class,
            AddNewPageWelcomeUserSeeder::class,
            FillInTheValuesForThePermissionsFieldSeeder::class,
            AddRoleSecretariaDeIglesiaSeeder::class,
            AddUserSecretariaDeIglesiaSeeder::class,
            AddPagesForSecretariaDeIglesiaSeeder::class,
            SetPermissionsOnPagesForChurchSecretarySeeder::class,
            AddRoleDistrictSecretarySeeder::class,
            AddUserDistrictSecretarySeeder::class,
            AddPagesForRoleDistrictSecretarySeeder::class,
            SetPermissionsOnPagesForRoleDistrictSecretarySeeder::class,
            addAllPagesToTheSystemCreatorsRoleSeeder::class,
            addAllPermissionsToTheSystemCreatorsRoleSeeder::class,
        ]);
    }
}
