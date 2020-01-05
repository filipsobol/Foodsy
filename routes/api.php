<?php

// Locations
Route::get("/locations", "LocationController@index");

// Suppliers
Route::get("/suppliers", "SupplierController@index");
Route::get("/suppliers/{supplier}", "SupplierController@show");

// Cart
Route::post("/cart", "CartController@createCart");
Route::get("/cart/{cart}", "CartController@showCart");
Route::post("/cart/{cart}/product", "CartController@addProduct");
Route::put("/cart/{cart}/product", "CartController@updateProduct");


// ---------------- FRONT FOR CUSTOMERS ----------------
// Checkout
// Order
// Customer
//   - profile
//     - first name
//     - last name
//     - email address
//     - phone no
//     - password
//     - addresses (many)
//   - orders
//   - signin / signout


// ---------------- BACK FOR SUPPLIERS ----------------
// Dashboard - number of new orders, cash earned etc.
// Orders with map