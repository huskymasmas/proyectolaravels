<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduccionController;


Route::get('/', function () {
    return view('inicio');
});


Route::get('/produccion/crear', [ProduccionController::class, 'create'])->name('produccion.create');
Route::post('/produccion/store', [ProduccionController::class, 'store'])->name('produccion.store');
Route::get('/produccion/reporte', [ProduccionController::class,'reporte'])->name('produccion.reporte');
Route::get('/produccion', [ProduccionController::class, 'index'])->name('produccion.index');
Route::get('/produccion/{id}/editar', [ProduccionController::class, 'edit'])->name('produccion.edit');
Route::put('/produccion/{id}', [ProduccionController::class, 'update'])->name('produccion.update');
