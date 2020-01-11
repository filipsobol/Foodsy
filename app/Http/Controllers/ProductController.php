<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            "location"      => "required|string|exists:locations,slug",
            "name"          => "sometimes|required|min:2|max:20",
            "sortBy"        => "sometimes|required|in:" . implode(",", Product::ALLOWED_SORTING_PARAMETERS),
            "sortOrder"     => "sometimes|required_with:sortBy|in:asc,desc",
            // "rating"        => "sometimes|required|integer|min:50|max:90",

            "diets"         => "sometimes|required|array",
            "diets.*"       => "required|string|exists:diets,slug",
            
            "categories"    => "sometimes|required|array",
            "categories.*"  => "required|string|exists:categories,slug",
        ]);

        $products = Product::where("active", true)
            ->whereHas("locations", fn (Builder $query) => $query->where("locations.slug", $data["location"]));

        if (array_key_exists("name", $data)) {
            $products
                ->where("name", "like", "%{$data['name']}%")
                ->orWhere("description", "like", "%{$data['name']}%");
        }


        if (array_key_exists("diets", $data)) {
            $products->whereHas("diets", fn (Builder $query) => $query->whereIn("diets.slug", $data["diets"]));
        }

        if (array_key_exists("categories", $data)) {
            $products->whereHas("category", fn (Builder $query) => $query->whereIn("categories.slug", $data["categories"]));
        }

        if (array_key_exists("sortBy", $data)) {
            $products->orderBy($data["sortBy"], $data["sortOrder"]);
        }

        $result = $products
            ->with(["supplier", "category", "diets"])
            ->paginate(20)
            ->toArray();

        return [
            "data"          => $result["data"],
            "current_page"  => $result["current_page"],
            "page_count"    => $result["last_page"],
            "total"         => $result["total"],
        ];
    }

    /**
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if (!$product->active) {
            return response("Product doesn't exists", 404);
        }

        return $product->load(["supplier", "category", "diets"]);
    }
}
