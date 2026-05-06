<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToastController;

// Main welcome page
Route::get('/', function () {
    return view('welcome');
});

// Toast test routes
Route::get('/success', [ToastController::class, 'success']);
Route::get('/error', [ToastController::class, 'error']);
Route::get('/info', [ToastController::class, 'info']);

// NEW ROUTE
Route::post('/toast/custom', [ToastController::class, 'custom']);