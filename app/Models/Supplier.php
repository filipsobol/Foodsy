<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = "suppliers";
    
    public $timestamps = false;

    protected $fillable = [
        "name",
        "slug",
        "description",
        "image_path",
        "accepts_payments"
    ];

    public function getRouteKeyName(): string
    {
        return "slug";
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, "supplier_location");
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
