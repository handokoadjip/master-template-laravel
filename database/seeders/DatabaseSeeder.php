<?php

namespace Database\Seeders;

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
        $this->call(GroupSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(MenuItemSeeder::class);
        $this->call(WorkUnitSeeder::class);
        $this->call(ActionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(GroupActionSeeder::class);
        $this->call(GroupMenuItemSeeder::class);
        $this->call(GroupWorkUnitSeeder::class);
        $this->call(UserGroupSeeder::class);
        $this->call(UserWorkUnitSeeder::class);
    }
}
