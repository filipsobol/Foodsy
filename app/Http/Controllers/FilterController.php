<?php

namespace App\Http\Controllers;

use App\Models\{
    Category,
    Diet,
    Location
};

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            "categories"        => Category::all(),
            "diets"             => Diet::all(),
            "locations"         => Location::all(),
        ];
    }
}
