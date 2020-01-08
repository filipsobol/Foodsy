<?php

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Location::create([
            "name"      => "Łódź",
            "slug"      => "lodz",
        ]);

        Location::create([
            "name"      => "Warszawa",
            "slug"      => "warszawa",
        ]);

        Location::create([
            "name"      => "Wrocław",
            "slug"      => "wroclaw",
        ]);

        Location::create([
            "name"      => "Kraków",
            "slug"      => "krakow",
        ]);

        Location::create([
            "name"      => "Gdańsk",
            "slug"      => "gdansk",
        ]);
    }
}
