<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Post::class, function (Faker $faker) {
    return [
        'author' => 1,
        'title' => $faker->word,
        'content' => $faker->sentence,
        'excerpt' => $faker->sentence,
        'slug' => $faker->slug,
        'created_at' => time(),
        'updated_at' => time(),
    ];
});
