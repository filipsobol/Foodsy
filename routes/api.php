<?php

// Filters
Route::get("/filters", "FilterController@index");

// Products
Route::get("/products", "ProductController@index");
Route::get("/products/{product}", "ProductController@show");

// Cart
Route::post("/cart", "CartController@createCart");
Route::get("/cart/{cart}", "CartController@showCart");
Route::post("/cart/{cart}/product", "CartController@addProduct");
Route::put("/cart/{cart}/product", "CartController@updateProduct");
