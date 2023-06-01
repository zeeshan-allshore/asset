<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AppDatabaseSeeder::class,
            RolesDatabaseSeeder::class,
            SettingsDatabaseSeeder::class,
            UserDatabaseSeeder::class,
        ]);

        Category::factory(5)->create();
    }
}
