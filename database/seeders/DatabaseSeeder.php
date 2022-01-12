<?php

namespace Database\Seeders;

use App\Models\Status;
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
        // \App\Models\User::factory(10)->create();
        Status::truncate();
        $this->call(UsersTableSeeder::class);

        Status::factory()->count(10)->create();
    }
}
