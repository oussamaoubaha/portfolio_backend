<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug-db', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        return "Database connection established successfully to: " . \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "Could not connect to the database. Error: " . $e->getMessage();
    }
});

Route::get('/init-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed --force');
        return "Database initialized successfully (migrate:fresh --seed --force). Output: " . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        return "Error initializing database: " . $e->getMessage();
    }
});
