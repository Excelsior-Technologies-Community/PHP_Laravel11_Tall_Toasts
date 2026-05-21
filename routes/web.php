<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToastController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/success', [ToastController::class, 'success']);
Route::get('/error', [ToastController::class, 'error']);
Route::get('/info', [ToastController::class, 'info']);
Route::get('/warning', [ToastController::class, 'warning']);
Route::post('/toast/custom', [ToastController::class, 'custom']);
Route::get('/toast-history', [ToastController::class, 'history']);
Route::delete('/clear-history', [ToastController::class, 'clearHistory']);