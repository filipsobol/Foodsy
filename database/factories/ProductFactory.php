<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;

$factory->define(\App\Models\Product::class, function (Faker $faker) {
    return [
        "name"              => $faker->uuid,
        "description"       => $faker->text,
        "ingredients"       => $faker->text,
        "mass"              => $faker->numberBetween(100, 300),
        "price"             => $faker->numberBetween(500, 900),
        "active"            => true,
        "image_path"        => null,
        "supplier_id"       => factory(\App\Models\Supplier::class),
        "category_id"       => factory(\App\Models\Category::class),
    ];
});
