<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";
    
    public $timestamps = false;

    protected $fillable = [
        "name",
        "description",
        "ingredients",
        "mass",
        "price",
        "active",
        "image_path",
        "category_id",
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, "product_location");
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, "cart_product")->withPivot("quantity");
    }
}
