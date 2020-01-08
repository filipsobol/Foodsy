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
        "supplier_id",
        "category_id",
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function diets()
    {
        return $this->belongsToMany(Diet::class, "product_diet");
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
