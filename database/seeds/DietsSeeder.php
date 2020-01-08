<?php

use App\Models\Diet;
use Illuminate\Database\Seeder;

class DietsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Diet::create([
            "name"      => "Wegańska",
            "slug"      => "weganska",
        ]);

        Diet::create([
            "name"      => "Wegetariańska",
            "slug"      => "wegetarianska",
        ]);

        Diet::create([
            "name"      => "Bez glutnu",
            "slug"      => "bez-glutenu",
        ]);

        Diet::create([
            "name"      => "Bez laktozy",
            "slug"      => "bez-laktozy",
        ]);

        Diet::create([
            "name"      => "Bez ryb",
            "slug"      => "bez-ryb",
        ]);

        Diet::create([
            "name"      => "Odchudzająca",
            "slug"      => "odchudzajaca",
        ]);
    }
}
