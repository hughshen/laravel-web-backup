<?php

use App\Models\Term;
use Faker\Generator as Faker;

$factory->define(Term::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'slug' => $faker->slug,
        'taxonomy' => $faker->randomElement([Term::TAX_CAT, Term::TAX_TAG]),
        'created_at' => time(),
        'updated_at' => time(),
    ];
});
