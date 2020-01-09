<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\App\Models\Supplier::class, function (Faker $faker) {
    $name = $faker->company;

    return [
        "name"              => $name,
        "slug"              => Str::slug($name, '-'),
        "description"       => $faker->catchPhrase,
        "image_path"        => null,
        "accepts_payments"  => $faker->boolean(50),
    ];
});
