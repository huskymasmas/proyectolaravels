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
use App\Http\Controllers\StockController;
use App\Http\Controllers\TipoDosificacionController;
use App\Http\Controllers\PlanoController;
use App\Http\Controllers\BodegaParaProyectosController;
use App\Http\Controllers\TransferenciaController;
use App\Http\Controllers\ValeIngresoMaquinariaController;
use App\Http\Controllers\EstacionBodegaController;
use App\Http\Controllers\ValeEgreso_MaquinariaController;
use App\Http\Controllers\MaquinaUsoController;
use App\Http\Controllers\NominaUnificadaController;
use App\Http\Controllers\ValeEgresoMaterialesController;
use App\Http\Controllers\ValeIngresoMaterialesController;
use App\Http\Controllers\ControlProduccionController;
use App\Http\Controllers\AvanceTrabajoController;

Route::get('/', function () {

    return view('index');
});



Route::prefix('avances')->name('avances.')->group(function () {
    Route::get('/', [AvanceTrabajoController::class, 'index'])->name('index');
    Route::get('/create', [AvanceTrabajoController::class, 'create'])->name('create');
    Route::post('/store', [AvanceTrabajoController::class, 'store'])->name('store');

    // AJAX correcto
    Route::get('/trabajos/{id_aldea}', [AvanceTrabajoController::class, 'getTrabajos']);
});


Route::resource('control_produccion', ControlProduccionController::class)->middleware(['auth' , 'role:admin']);
Route::get('/control_produccion/siguiente_vale', [ControlProduccionController::class, 'siguienteVale'])
    ->name('control_produccion.siguiente_vale');


Route::resource('vale_ingreso_material', ValeIngresoMaterialesController::class)->middleware(['auth' , 'role:admin']);

Route::resource('vale_egreso_material', ValeEgresoMaterialesController::class)->middleware(['auth' , 'role:admin']);

Route::get('/nomina_unificada', [NominaUnificadaController::class, 'index'])->name('nomina_unificada.index');

Route::get('/reportes/nomina', [ReporteNominaController::class, 'index'])->name('reportes.nomina.index');

Route::get('/reportes/nomina/create', [ReporteNominaController::class, 'create'])
    ->name('reportes.nomina.create');

Route::post('/reportes/nomina/store', [ReporteNominaController::class, 'store'])
    ->name('reportes.nomina.store');
// Grupo de rutas para reportes/nomina


Route::prefix('reportes/nomina')->name('reportes.nomina.')->group(function () {

    // Rutas para nómina
    Route::get('create', [ReporteNominaController::class, 'create'])->name('create');
    Route::post('store', [ReporteNominaController::class, 'store'])->name('store');
    Route::get('{id}/edit', [ReporteNominaController::class, 'edit'])->name('edit');
    Route::put('{id}', [ReporteNominaController::class, 'update'])->name('update');
    Route::delete('{id}', [ReporteNominaController::class, 'destroy'])->name('destroy');

    // Rutas para detalle de nómina (ahora apuntando a los métodos correctos)
    Route::get('detalle/create', [ReporteNominaController::class, 'detalleCreate'])->name('detalle.create');
    Route::post('detalle/store', [ReporteNominaController::class, 'guardarDetalle'])->name('detalle.store');
    Route::get('detalle/{id}/edit', [ReporteNominaController::class, 'detalleEdit'])->name('detalle.edit');
    Route::put('detalle/{id}', [ReporteNominaController::class, 'detalleUpdate'])->name('detalle.update');
    Route::delete('detalle/{id}', [ReporteNominaController::class, 'detalleDestroy'])->name('detalle.destroy');
});


// INDEX de máquinas en uso
Route::get('maquina_uso', [MaquinaUsoController::class, 'index'])
    ->name('maquinauso.index')
    ->middleware(['auth','role:admin']);

// FORM para devolver
Route::get('maquina_uso/{id}/devolver', [MaquinaUsoController::class, 'formDevolver'])
    ->name('maquinauso.formDevolver')
    ->middleware(['auth','role:admin']);

// PROCESAR la devolución
Route::post('maquina_uso/{id}/procesar-devolucion', [MaquinaUsoController::class, 'procesarDevolucion'])
    ->name('maquinauso.procesarDevolucion')
    ->middleware(['auth','role:admin']);


Route::resource('estacionbodega', EstacionBodegaController::class)->middleware(['auth' , 'role:admin']);
Route::resource('valeingres_maquinaria', ValeIngresoMaquinariaController::class)->middleware(['auth' , 'role:admin']);

Route::resource('valeegreso_maquinaria', ValeEgreso_MaquinariaController::class)
    ->middleware(['auth', 'role:admin']);


Route::resource('MaquinaUso', MaquinaUsoController::class)->middleware(['auth' , 'role:admin']);


Route::get('bodega-proyectos/crear', [BodegaParaProyectosController::class, 'create'])->name('bodegaparaproyectos.form');
Route::get('bodega-proyectos/{id}', [BodegaParaProyectosController::class, 'show'])->name('bodegaparaproyectos.show');




// PLANOS
// Subir plano por trabajo
Route::post('/planos/store', [PlanoController::class, 'store'])->name('planos.store');

// Subir plano por aldea
Route::post('/planos/aldea/store', [PlanoController::class, 'storePorAldea'])->name('planos.aldea.store');





Route::get('/planos/ver/{id}', [PlanoController::class, 'ver'])->name('planos.ver');






Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//rutas para usuarios
//admin



Route::get('/transferencias', [TransferenciaController::class, 'index'])->name('transferencia.index');
Route::post('/transferencias', [TransferenciaController::class, 'transferir'])->name('transferencia.store');


Route::resource('tipo_dosificacion', TipoDosificacionController::class);


Route::get('/stock_bajo', [StockController::class, 'enviarCorreo']);


Route::get('trabajo/export/excel', [TrabajoController::class, 'exportExcel'])->name('trabajo.export.excel');
Route::get('trabajo/export/pdf', [TrabajoController::class, 'exportPdf'])->name('trabajo.export.pdf');


Route::resource('formato_control_despacho_planta', FormatoControlDespachoPlantaController::class)->middleware(['auth' , 'role:admin']);

Route::resource('aldea', AldeaController::class)->middleware(['auth' , 'role:admin']);


Route::get('/reportes/nomina_detalle_empleados', [ReporteNominaDetalleController::class, 'index'])
    ->name('reportes.nomina_detalle_empleados.index');





Route::resource('bodegaparaproyectos', BodegaParaProyectosController::class);
Route::post('detalle/store', [ReporteNominaController::class, 'guardarDetalle'])
    ->name('detalle.store');

Route::resource('nomina', NominaController::class)->middleware(['auth' , 'role:admin']);
Route::resource('detalle_nomina', DetalleNominaController::class)->middleware(['auth' , 'role:admin']);
Route::resource('registro_diario', RegistroDiarioController::class)->middleware(['auth' , 'role:admin']);
Route::get('/facturas', [FacturaController::class, 'index'])->name('facturas.index');
Route::get('/facturas/buscar', [FacturaController::class, 'buscar'])->name('facturas.buscar');
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
Route::prefix('editor')->middleware(['auth','role:editor'])->group(function () {
    Route::resource('inicio', EditorController::class);
});

Route::resource('asistencia/asignar', AsistenciaController::class)->middleware(['auth' , 'role:editor']);

 // Route::get('asistencia/create', [AsistenciaController::class, 'create'])->name('asistencia.create')->middleware(['auth' , 'role:editor']);
   // Route::post('asistencia', [AsistenciaController::class, 'store'])->name('asistencia.store')->middleware(['auth' , 'role:editor']);
