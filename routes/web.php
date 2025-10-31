<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;


Route::get('/', function () {
    return view('index');
});



Route::resource('proyectos', ProyectoController::class);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
