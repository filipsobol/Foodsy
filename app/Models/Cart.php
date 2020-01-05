<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = "carts";

    protected $keyType = "string";

    public $incrementing = false;

    protected $fillable = [
        "id",
        "user_id",
        "location_id",
    ];

    protected $casts = [
        "id" => "uuid",
        "email_verified_at" => "datetime",
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, "cart_product")->withPivot("quantity");
    }
}
