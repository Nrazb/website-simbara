<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function (){
    return view('auth.login');
});

// Route Dashboard
Route::get('/dashboard', function () {
    return view('dashboard'); // ganti dengan view dashboard kamu
})->name('dashboard');

