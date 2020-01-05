<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class CartController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createCart(Request $request)
    {
        $data = $request->validate([
            "location_id" => "required|integer|min:1|exists:locations,id"
        ]);

        return Cart::create([
            "id" => Str::orderedUuid()->toString(),
            "location_id" => $data["location_id"],
            "user_id" => auth('api')->check() ? auth('api')->user()->id : null,
        ]);
    }
    
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart          $cart
     * @return \Illuminate\Http\Response
     */
    public function showCart(Request $request, Cart $cart)
    {
        return $cart->load("products");
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart          $cart
     * @return \Illuminate\Http\Response
     */
    public function addProduct(Request $request, Cart $cart)
    {
        $data = $request->validate([
            "product_id" => "required|integer|min:1|exists:products,id",
            "quantity"   => "required|integer|min:1|max:" . Cart::MAXIMUM_PRODUCT_QUANTITY,
        ]);

        $product = $this->getProductForLocation($data["product_id"], $cart->location_id);

        if (!$product) {
            return response("Product cannot be added to the cart", 400);
        }

        $productInCart = $cart->products()
            ->where("products.id", $product->id)
            ->first();

        $productInCart
            ? $this->updateProductInTheCart($cart, $productInCart, min($productInCart->pivot->quantity + $data["quantity"], Cart::MAXIMUM_PRODUCT_QUANTITY))
            : $this->addProductToTheCart($cart, $product, $data["quantity"]);

        $cart->touch(); // Update updated_at time on Cart model

        return $this->showCart($request, $cart);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart          $cart
     * @return \Illuminate\Http\Response
     */
    public function updateProduct(Request $request, Cart $cart)
    {
        $data = $request->validate([
            "product_id" => "required|integer|min:1|exists:products,id",
            "quantity"   => "required|integer|min:1|max:" . Cart::MAXIMUM_PRODUCT_QUANTITY,
        ]);

        $product = $cart->products()
            ->where("products.id", $data["product_id"])
            ->first();

        if (!$product) {
            return response("Product cannot be updated", 400);
        }

        $this->updateProductInTheCart($cart, $product, min($data["quantity"], Cart::MAXIMUM_PRODUCT_QUANTITY));

        $cart->touch(); // Update updated_at time on Cart model

        return $this->showCart($request, $cart);
    }


    /**
     * @param  integer                   $productId
     * @param  integer                   $locationId
     * @return \App\Models\Product
     */
    protected function getProductForLocation(int $productId, int $locationId)
    {
        return Product::where("id", $productId)
            ->where("active", true)
            ->whereHas("locations", fn(Builder $query) => $query->where("locations.id", $locationId))
            ->first();
    }

    /**
     * @param  \App\Models\Cart          $cart
     * @param  \App\Models\Product       $product
     * @param  integer                   $quantity
     * @return void
     */
    protected function addProductToTheCart(Cart $cart, Product $product, int $quantity)
    {
        $cart->products()->attach($product->id, ["quantity" => $quantity]);
    }

    /**
     * @param  \App\Models\Cart          $cart
     * @param  \App\Models\Product       $product
     * @param  integer                   $quantity
     * @return void
     */
    protected function updateProductInTheCart(Cart $cart, Product $product, int $quantity)
    {
        $cart->products()->updateExistingPivot($product->id, ["quantity" => $quantity]);
    }
}
