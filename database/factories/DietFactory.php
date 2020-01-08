<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\App\Models\Diet::class, function (Faker $faker) {
    $name = $faker->name;

    return [
        "name"      => $name,
        "slug"      => Str::slug($name, '-'),
    ];
});
