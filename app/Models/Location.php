<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = "locations";
    
    public $timestamps = false;

    protected $fillable = [
        "name",
        "slug",
        "image_path",
    ];

    public function getRouteKeyName(): string
    {
        return "slug";
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, "supplier_location");
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, "product_location");
    }
}
