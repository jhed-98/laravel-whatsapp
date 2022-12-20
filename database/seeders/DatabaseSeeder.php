<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
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

        User::factory()->create([
            'name' => 'JhEd Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789')
        ]);

        User::factory(10)->create();
    }
}
