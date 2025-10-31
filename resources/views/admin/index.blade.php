@extends('layouts.app')

@section('content')
<div class="container">
    <h1>admin</h1>
    <button class="btn btn-secondary" onclick="window.location='{{ route('proyectos.index') }}'">
        crear proyecto
        </button>
    <button class="btn btn-secondary" onclick="window.location='{{ route('Configuracion.index') }}'">
        crear configuracion
        </button>
</div>
@endsection
