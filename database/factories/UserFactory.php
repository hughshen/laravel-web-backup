<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'username' => 'admin',
        'password' => bcrypt('admin'),
        'secret_2fa' => app('gauth')->createSecret(),
        'login_ip' => ip2long($faker->ipv4),
        'login_time' => time(),
        'created_at' => time(),
        'updated_at' => time(),
    ];
});
