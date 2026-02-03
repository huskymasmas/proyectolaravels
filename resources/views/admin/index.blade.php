@extends('layouts.app')

@section('content')
<div class="container">
    <h1>admin</h1>
    <button class="btn btn-secondary" onclick="window.location='{{ route('proyectos.index') }}'">
        crear proyecto
        </button>
    <button class="btn btn-secondary" onclick="window.location='{{ route('Configuracion.index') }}'">
        configuracion
        </button>
    <button class="btn btn-secondary" onclick="window.location='{{ route('dosificacion.index') }}'">
        dosificaciones
        </button>
    <button class="btn btn-secondary" onclick="window.location='{{ route('detalles.index') }}'">
        detalles
        </button>
    <button class="btn btn-secondary" onclick="window.location='{{ route('requerimientos.index') }}'">
        requerimientos
    </button>
     <button class="btn btn-secondary" onclick="window.location='{{ route('trabajo.index') }}'">
        trabajo
    </button>
     <button class="btn btn-secondary" onclick="window.location='{{ route('vale_egreso.index') }}'">
        vale egreso
    </button>

     <button class="btn btn-secondary" onclick="window.location='{{ route('vale_ingreso.index') }}'">
        vale ingreso
        </button>
    <button class="btn btn-secondary" onclick="window.location='{{ route('formato_despacho.index') }}'">
        formato despacho
        </button>
    <button class="btn btn-secondary" onclick="window.location='{{ route('formato_despacho.index') }}'">
        formato despacho
        </button>
    <button class="btn btn-secondary" onclick="window.location='{{ route('tramo_aplicacion.index') }}'">
        tramo aplicacion
        </button>
    <button class="btn btn-secondary" onclick="window.location='{{ route('tramos.index') }}'">
        tramo 
        </button>
    <button class="btn btn-secondary" onclick="window.location='{{ route('control_concreto_campo.index') }}'">
        control_concreto_campo
        </button>
    <button class="btn btn-secondary" onclick="window.location='{{ route('bodega.index') }}'">
        bodega
        </button>
        

</div>
@endsection
