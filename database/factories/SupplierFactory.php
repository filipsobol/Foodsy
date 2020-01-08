<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\App\Models\Supplier::class, function (Faker $faker) {
    $name = $faker->name;

    return [
        "name"              => $name,
        "slug"              => Str::slug($name, '-'),
        "description"       => $faker->text,
        "image_path"        => null,
        "accepts_payments"  => $faker->boolean(50),
    ];
});
