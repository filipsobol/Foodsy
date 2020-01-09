<?php

namespace Tests\Feature;

use App\Models\{
    Cart,
    Location,
    Product
};
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    protected $cart;

    protected $cartLocation;
    protected $invalidLocation;
    
    protected $cartProduct;
    protected $invalidCartProduct;

    public function setUp(): void
    {
        parent::setUp();

        $this->cartLocation = factory(Location::class)->create();
        $this->invalidLocation = factory(Location::class)->create();

        $this->cartProduct = factory(Product::class)->create();
        $this->invalidCartProduct = factory(Product::class)->create();

        $this->cartLocation->products()->attach($this->cartProduct->id);
        $this->invalidLocation->products()->attach($this->invalidCartProduct->id);

        $this->cart = factory(Cart::class)->create(["location_id" => $this->cartLocation->id]);
    }

    /*******************************************************  HAPPY FLOW *******************************************************/
    public function test_guest_can_get_a_cart()
    {
        $this
            ->getJson("/cart/{$this->cart->id}")
            ->assertStatus(200)
            ->assertJson([
                "id"                => $this->cart->id,
                "location_id"       => $this->cartLocation->id,
                "user_id"           => null,
            ]);
    }

    public function test_guest_can_create_a_cart()
    {
        $this
            ->postJson("/cart", ["location_id" => $this->cartLocation->id])
            ->assertStatus(201)
            ->assertJson([
                "location_id"       => $this->cartLocation->id,
                "user_id"           => null,
            ]);
    }

    public function test_guest_can_add_product_from_the_same_location_as_cart()
    {
        $this
            ->postJson("/cart/{$this->cart->id}/product", [
                "product_id"    => $this->cartProduct->id,
                "quantity"      => 10
            ])
            ->assertStatus(200)
            ->assertJson([
                "id"                => $this->cart->id,
                "location_id"       => $this->cartLocation->id,
                "user_id"           => null,
                "products"          => [
                    [
                        "id" => $this->cartProduct->id,
                        "pivot" => [
                            "quantity" => 10,
                        ]
                    ]
                ],
            ]);
    }

    public function test_guest_can_update_product_in_the_cart()
    {
        $this->postJson("/cart/{$this->cart->id}/product", [
            "product_id"    => $this->cartProduct->id,
            "quantity"      => 1
        ]);

        $this
            ->putJson("/cart/{$this->cart->id}/product", [
                "product_id"    => $this->cartProduct->id,
                "quantity"      => 5
            ])
            ->assertStatus(200)
            ->assertJson([
                "id"                => $this->cart->id,
                "location_id"       => $this->cartLocation->id,
                "user_id"           => null,
                "products"          => [
                    [
                        "id" => $this->cartProduct->id,
                        "pivot" => [
                            "quantity" => 5,
                        ]
                    ]
                ],
            ]);
    }

    /***************************************************  NOT SO HAPPY FLOWS ***************************************************/
    public function test_fetching_cart_that_doesnt_exists_fails()
    {
        $nonExistentCartId = Str::orderedUuid()->toString();

        $this
            ->getJson("/cart/{$nonExistentCartId}")
            ->assertStatus(404);
    }

    public function test_adding_product_that_doesnt_exists_fails()
    {
        $this
            ->postJson("/cart/{$this->cart->id}/product", [
                "product_id"    => Str::orderedUuid()->toString(),
                "quantity"      => 1
            ])
            ->assertStatus(422);
    }

    public function test_updating_product_that_is_not_in_cart_fails()
    {
        $this
            ->putJson("/cart/{$this->cart->id}/product", [
                "product_id"    => $this->cartProduct->id,
                "quantity"      => 1
            ])
            ->assertStatus(400);
    }

    public function test_cannot_add_product_from_different_location_than_cart()
    {
        $this
            ->postJson("/cart/{$this->cart->id}/product", [
                "product_id"    => $this->invalidCartProduct->id,
                "quantity"      => 1
            ])
            ->assertStatus(400);
    }

    public function test_adding_product_that_is_already_in_cart_will_increase_quantity()
    {
        $this->postJson("/cart/{$this->cart->id}/product", [
            "product_id"    => $this->cartProduct->id,
            "quantity"      => 1
        ]);

        $this
            ->postJson("/cart/{$this->cart->id}/product", [
                "product_id"    => $this->cartProduct->id,
                "quantity"      => 2
            ])
            ->assertStatus(200)
            ->assertJson([
                "id"                => $this->cart->id,
                "location_id"       => $this->cartLocation->id,
                "user_id"           => null,
                "products"          => [
                    [
                        "id" => $this->cartProduct->id,
                        "pivot" => [
                            "quantity" => 3,
                        ]
                    ]
                ],
            ]);
    }

    public function test_adding_higher_quantity_than_allowed_fails()
    {
        $this
        ->postJson("/cart/{$this->cart->id}/product", [
            "product_id"    => $this->cartProduct->id,
            "quantity"      => Cart::MAXIMUM_PRODUCT_QUANTITY + 1
        ])
        ->assertStatus(422);
    }

    public function test_increasing_quantity_higher_than_allowed_will_set_max_quantity()
    {
        $this->postJson("/cart/{$this->cart->id}/product", [
            "product_id"    => $this->cartProduct->id,
            "quantity"      => Cart::MAXIMUM_PRODUCT_QUANTITY
        ]);

        $this
            ->postJson("/cart/{$this->cart->id}/product", [
                "product_id"    => $this->cartProduct->id,
                "quantity"      => 1
            ])
            ->assertStatus(200)
            ->assertJson([
                "id"                => $this->cart->id,
                "location_id"       => $this->cartLocation->id,
                "user_id"           => null,
                "products"          => [
                    [
                        "id" => $this->cartProduct->id,
                        "pivot" => [
                            "quantity" => Cart::MAXIMUM_PRODUCT_QUANTITY,
                        ]
                    ]
                ],
            ]);
    }
}
