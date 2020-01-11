<?php

namespace Tests\Feature;

use App\Models\{
    Category,
    Diet,
    Location
};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterApiTest extends TestCase
{
    use RefreshDatabase;

    protected $categories;
    protected $diets;
    protected $locations;

    public function setUp(): void
    {
        parent::setUp();

        $this->categories = factory(Category::class, 5)->create();
        $this->diets = factory(Diet::class, 10)->create();
        $this->locations = factory(Location::class, 15)->create();
    }
    
    /*******************************************************  HAPPY FLOW *******************************************************/
    public function test_guest_can_get_a_list_of_filters()
    {
        $this
            ->getJson("/filters")
            ->assertStatus(200)
            ->assertJson([
                "categories"    => $this->categories->toArray(),
                "diets"         => $this->diets->toArray(),
                "locations"     => $this->locations->toArray(),
            ]);
    }

    /***************************************************  NOT SO HAPPY FLOWS ***************************************************/
}
