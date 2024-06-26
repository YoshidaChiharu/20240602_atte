<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() < 100) {
            \App\Models\User::factory(100)->create();
        }
        \App\Models\Work::factory(80)->create();
        \App\Models\Rest::factory(80)->create();
    }
}
