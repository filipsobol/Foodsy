<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            "name"      => "Kanapki",
            "slug"      => "kanapki",
        ]);

        Category::create([
            "name"      => "Obiady",
            "slug"      => "obiady",
        ]);

        Category::create([
            "name"      => "Sałatki",
            "slug"      => "salatki",
        ]);

        Category::create([
            "name"      => "Jogurty",
            "slug"      => "jogurty",
        ]);

        Category::create([
            "name"      => "Desery",
            "slug"      => "desery",
        ]);

        Category::create([
            "name"      => "Napoje",
            "slug"      => "napoje",
        ]);
    }
}
