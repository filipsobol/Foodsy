<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diet extends Model
{
    protected $table = "diets";
    
    public $timestamps = false;

    protected $fillable = [
        "name",
        "slug",
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, "product_diet");
    }

    public function suppliers()
    {
        return $this->hasManyThrough(Supplier::class, Product::class);
    }
}
