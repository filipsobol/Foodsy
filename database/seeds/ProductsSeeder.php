<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 50; $i++) {
            $product = factory(\App\Models\Product::class)->create([
                "name"          => "Test Product #{$i}",
                "supplier_id"   => rand(1, 5),
                "category_id"   => rand(1, 6),
            ]);

            $product
                ->locations()
                ->attach($product->supplier->locations()->get()->pluck('id'));

            $product
                ->diets()
                ->attach([
                    rand(1, 6),
                    rand(1, 6),
                ]);
        }
    }
}
