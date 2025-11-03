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
</div>
@endsection
