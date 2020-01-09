<?php

namespace Tests\Feature;

use App\Models\{
    Category,
    Diet,
    Location,
    Product
};
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected $products;
    protected $locations;

    public function setUp(): void
    {
        parent::setUp();

        $this->products = factory(Product::class, 6)->create();
        $this->locations = factory(Location::class, 3)->create();

        // First location
        $this->products->firstWhere("id", 1)->locations()->attach($this->locations->firstWhere("id", 1));
        $this->products->firstWhere("id", 2)->locations()->attach($this->locations->firstWhere("id", 1));

        // Second location
        $this->products->firstWhere("id", 3)->locations()->attach($this->locations->firstWhere("id", 2));
        $this->products->firstWhere("id", 4)->locations()->attach($this->locations->firstWhere("id", 2));

        // Third location
        $this->products->firstWhere("id", 5)->locations()->attach($this->locations->firstWhere("id", 3));
        $this->products->firstWhere("id", 6)->locations()->attach($this->locations->firstWhere("id", 3));
    }


    /*******************************************************  HAPPY FLOW *******************************************************/
    public function test_guest_can_get_a_list_of_products()
    {
        $firstProduct = $this->products->firstWhere("id", 1);
        $secondProduct = $this->products->firstWhere("id", 2);

        $parameters = http_build_query([
            "location" => $this->locations->firstWhere("id", 1)->slug,
        ]);

        $this
            ->getJson("/products?{$parameters}")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id"        => $firstProduct->id,
                        "name"      => $firstProduct->name,
                        "slug"      => $firstProduct->slug,
                    ],
                    [
                        "id"        => $secondProduct->id,
                        "name"      => $secondProduct->name,
                        "slug"      => $secondProduct->slug,
                    ]
                ],
                "current_page"  => 1,
                "page_count"    => 1,
                "total"         => 2
            ]);
    }

    public function test_guest_can_get_a_product()
    {
        $product = $this->products->firstWhere("id", 6);

        $this
            ->getJson("/products/{$product->slug}")
            ->assertStatus(200)
            ->assertJson([
                "id"        => $product->id,
                "name"      => $product->name,
                "slug"      => $product->slug,
            ]);
    }

    /***************************************************  NOT SO HAPPY FLOWS ***************************************************/
    public function test_non_active_product_is_not_included_in_index()
    {
        $firstProduct = $this->products->firstWhere("id", 1);
        $secondProduct = $this->products->firstWhere("id", 2);

        $firstProduct->update(["active" => false]);

        $parameters = http_build_query([
            "location" => $firstProduct->locations()->first()->slug,
        ]);

        $this
        ->getJson("/products?{$parameters}")
        ->assertStatus(200)
        ->assertJson([
            "data" => [
                [
                    "id"        => $secondProduct->id,
                    "name"      => $secondProduct->name,
                    "slug"      => $secondProduct->slug,
                ]
            ]
        ]);
    }

    public function test_non_active_product_cannot_be_fetched()
    {
        $product = $this->products->firstWhere("id", 1);
        $product->update(["active" => false]);

        $this
            ->getJson("/products/{$product->slug}")
            ->assertStatus(404);
    }

    public function test_name_filter_works()
    {
        $product = $this->products->firstWhere("id", 6);
        $product->update(["name" => "SEARCH_TEXT"]);

        $parameters = http_build_query([
            "location"  => $product->locations()->first()->slug,
            "name"      => "SEARCH_TEXT",
        ]);

        $this
        ->getJson("/products?{$parameters}")
        ->assertStatus(200)
        ->assertJson([
            "data" => [
                [
                    "id"        => $product->id,
                    "name"      => $product->name,
                    "slug"      => $product->slug,
                ]
            ]
        ]);
    }

    public function test_descrtiption_filter_works()
    {
        $product = $this->products->firstWhere("id", 5);
        $product->update(["description" => "SEARCH_TEXT"]);

        $parameters = http_build_query([
            "location"  => $product->locations()->first()->slug,
            "name"      => "SEARCH_TEXT",
        ]);

        $this
            ->getJson("/products?{$parameters}")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id"        => $product->id,
                        "name"      => $product->name,
                        "slug"      => $product->slug,
                    ]
                ]
            ]);
    }

    public function test_diets_filter_works()
    {
        $product = $this->products->firstWhere("id", 4);

        // Attach diet
        $diet = factory(Diet::class)->create();
        $product->diets()->attach($diet->id);

        $parameters = http_build_query([
            "location"  => $product->locations()->first()->slug,
            "diets"     => [
                $diet->slug
            ],
        ]);

        $this
            ->getJson("/products?{$parameters}")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id"        => $product->id,
                        "name"      => $product->name,
                        "slug"      => $product->slug,
                    ]
                ]
            ]);
    }

    public function test_categories_filter_works()
    {
        $product = $this->products->firstWhere("id", 3);

        // Attach category
        $category = factory(Category::class)->create();
        $product->category()->associate($category);
        $product->save();

        $parameters = http_build_query([
            "location"      => $product->locations()->first()->slug,
            "categories"    => [
                $category->slug
            ],
        ]);

        $this
            ->getJson("/products?{$parameters}")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id"        => $product->id,
                        "name"      => $product->name,
                        "slug"      => $product->slug,
                    ]
                ]
            ]);
    }

    public function test_asc_ordering_works()
    {
        $products = collect([
            $this->products->firstWhere("id", 1),
            $this->products->firstWhere("id", 2)
        ])->sortBy("name");
        $firstProduct = $products->first();
        $lastProduct = $products->last();

        $parametersAsc = http_build_query([
            "location"      => $firstProduct->locations()->first()->slug,
            "sortBy"        => "name",
            "sortOrder"     => "asc"
        ]);

        $this
            ->getJson("/products?{$parametersAsc}")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id"        => $firstProduct->id,
                        "name"      => $firstProduct->name,
                        "slug"      => $firstProduct->slug,
                    ],
                    [
                        "id"        => $lastProduct->id,
                        "name"      => $lastProduct->name,
                        "slug"      => $lastProduct->slug,
                    ]
                ]
            ]);

        $parametersDesc = http_build_query([
            "location"      => $firstProduct->locations()->first()->slug,
            "sortBy"        => "name",
            "sortOrder"     => "desc"
        ]);

        $this
            ->getJson("/products?{$parametersDesc}")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id"        => $lastProduct->id,
                        "name"      => $lastProduct->name,
                        "slug"      => $lastProduct->slug,
                    ],
                    [
                        "id"        => $firstProduct->id,
                        "name"      => $firstProduct->name,
                        "slug"      => $firstProduct->slug,
                    ]
                ]
            ]);
    }
}
