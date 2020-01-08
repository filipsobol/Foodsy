<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\App\Models\Location::class, function (Faker $faker) {
    $name = $faker->city;

    return [
        "name"          => $name,
        "slug"          => Str::slug($name, '-'),
        "image_path"    => null,
    ];
});