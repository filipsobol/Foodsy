<?php

// Locations
Route::get("/locations", "LocationController@index");

// Suppliers
Route::get("/suppliers", "SupplierController@index");
Route::get("/suppliers/{supplier}", "SupplierController@show");

// Cart
Route::post("/cart", "CartController@store");


// ---------------- FRONT FOR CUSTOMERS ----------------
// Cart
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