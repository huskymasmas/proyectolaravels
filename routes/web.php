<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DosificacionController;
use App\Http\Controllers\UtilidadController;
Route::get('/', function () {
    return view('index');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//rutas para usuarios

//admin


Route::resource('utilidades', UtilidadController::class)->middleware(['auth' , 'role:admin']);


Route::resource('dosificacion', DosificacionController::class)->middleware(['auth' , 'role:admin']);

Route::resource('admin/inicio', AdminController::class)->middleware(['auth' , 'role:admin']);

Route::resource('proyectos', ProyectoController::class)->middleware(['auth' , 'role:admin']);

Route::resource('Configuracion', ConfiguracionController::class)->middleware(['auth' , 'role:admin']);


//editor 


Route::resource('editor/inicio', EditorController::class)->middleware(['auth' , 'role:editor']);


