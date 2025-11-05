<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DosificacionController;
use App\Http\Controllers\DetalleController;
use App\Http\Controllers\RequerimientoMaterialController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\NominaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ExportarController;
use App\Http\Controllers\DepartamentoController;
Route::get('/', function () {
    return view('index');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//rutas para usuarios

//admin


Route::resource('departamentos', DepartamentoController::class)->middleware(['auth' , 'role:admin']);

Route::get('/exportar', [ExportarController::class, 'exportar'])->name('exportar');

Route::resource('asistencia', AsistenciaController::class)->middleware(['auth' , 'role:admin']);

Route::resource('requerimientos', RequerimientoMaterialController::class)->middleware(['auth' , 'role:admin']);

Route::resource('detalles', DetalleController::class)->middleware(['auth' , 'role:admin']);

Route::resource('dosificacion', DosificacionController::class)->middleware(['auth' , 'role:admin']);

Route::resource('admin/inicio', AdminController::class)->middleware(['auth' , 'role:admin']);

Route::resource('proyectos', ProyectoController::class)->middleware(['auth' , 'role:admin']);

Route::resource('Configuracion', ConfiguracionController::class)->middleware(['auth' , 'role:admin']);

Route::resource('roles', RolController::class)->middleware(['auth', 'role:admin']);

Route::resource('nomina', NominaController::class)->middleware(['auth', 'role:admin']);

Route::resource('empleados', EmpleadoController::class)->middleware(['auth', 'role:admin']);


//editor 


Route::resource('editor/inicio', EditorController::class)->middleware(['auth' , 'role:editor']);

Route::resource('asistencia/asignar', AsistenciaController::class)->middleware(['auth' , 'role:editor']);

 // Route::get('asistencia/create', [AsistenciaController::class, 'create'])->name('asistencia.create')->middleware(['auth' , 'role:editor']);
   // Route::post('asistencia', [AsistenciaController::class, 'store'])->name('asistencia.store')->middleware(['auth' , 'role:editor']);