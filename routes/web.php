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
use App\Http\Controllers\BodegaGeneralController;
use App\Http\Controllers\EstadoTrabajoController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\ValeIngresoController;
use App\Http\Controllers\BodegaProyectoController;
use App\Http\Controllers\ValeEgresoController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FormatoControlDespachoPlantaController;
use App\Http\Controllers\TramoController;
use App\Http\Controllers\TramoAplicacionController;
use App\Http\Controllers\ControlConcretoCampoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\RegistroDiarioController;
use App\Http\Controllers\DetalleNominaController;
use App\Http\Controllers\ReporteNominaDetalleController;
use App\Http\Controllers\ReporteNominaController;
use App\Http\Controllers\AldeaController;
use App\Http\Controllers\ControlConcretoDetalleController;

Route::get('/', function () {

    return view('index');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//rutas para usuarios
//admin







Route::resource('formato_control_despacho_planta', FormatoControlDespachoPlantaController::class)->middleware(['auth' , 'role:admin']);

Route::resource('aldea', AldeaController::class)->middleware(['auth' , 'role:admin']);


Route::get('/reportes/nomina_detalle_empleados', [ReporteNominaDetalleController::class, 'index'])
    ->name('reportes.nomina_detalle_empleados.index');


Route::get('/reportes/nomina', [ReporteNominaController::class, 'index'])->name('reportes.nomina.index');

Route::resource('nomina', NominaController::class)->middleware(['auth' , 'role:admin']);
Route::resource('detalle_nomina', DetalleNominaController::class)->middleware(['auth' , 'role:admin']);
Route::resource('registro_diario', RegistroDiarioController::class)->middleware(['auth' , 'role:admin']);
Route::get('/facturas', [FacturaController::class, 'index'])->name('facturas.index');
Route::post('/facturas/buscar', [FacturaController::class, 'buscar'])->name('facturas.buscar');
Route::get('/facturas/exportar/{numFactura}', [FacturaController::class, 'exportarExcel'])->name('facturas.exportar');
Route::resource('control_concreto_campo', ControlConcretoCampoController::class)->middleware(['auth' , 'role:admin']);
Route::resource('tramo_aplicacion', TramoAplicacionController::class)->middleware(['auth' , 'role:admin']);
Route::resource('tramos', TramoController::class)->middleware(['auth' , 'role:admin']);
Route::resource('formato_despacho', FormatoDespachoController::class);
Route::get('/vale_egreso', [ValeEgresoController::class, 'index'])->name('vale_egreso.index')->middleware(['auth' , 'role:admin']);
Route::get('/vale_egreso/create', [ValeEgresoController::class, 'create'])->name('vale_egreso.create')->middleware(['auth' , 'role:admin']);
Route::post('/vale_egreso', [ValeEgresoController::class, 'store'])->name('vale_egreso.store')->middleware(['auth' , 'role:admin']);
Route::get('/bodega_proyecto', [BodegaProyectoController::class, 'index'])->name('bodega_proyecto.index')->middleware(['auth' , 'role:admin']);
Route::get('/bodega_proyecto/{id}', [BodegaProyectoController::class, 'show'])->name('bodega_proyecto.show')->middleware(['auth' , 'role:admin']);
Route::resource('vale_ingreso', ValeIngresoController::class)->middleware(['auth' , 'role:admin']);;
Route::resource('estado_trabajo', EstadoTrabajoController::class)->middleware(['auth' , 'role:admin']);
Route::resource('trabajo', TrabajoController::class)->middleware(['auth' , 'role:admin']);
Route::resource('bodega', BodegaGeneralController::class)->middleware(['auth' , 'role:admin']);
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