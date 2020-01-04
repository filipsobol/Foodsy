<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";
    
    public $timestamps = false;

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
