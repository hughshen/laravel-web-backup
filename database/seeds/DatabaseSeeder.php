<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Config
        $this->call(ConfigSeeder::class);

        // User
        $this->call(UserSeeder::class);

        // Post
        $this->call(PostSeeder::class);

        // Term
        $this->call(TermSeeder::class);

        // Key
        Artisan::call('key:generate');

        // JWT
        Artisan::call('jwt:secret');
    }
}
