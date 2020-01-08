<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\App\Models\Cart::class, function (Faker $faker) {
    return [
        "id"                => Str::orderedUuid()->toString(),
        "location_id"       => factory(\App\Models\Location::class),
        "user_id"           => null,
    ];
});
