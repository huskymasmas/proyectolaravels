@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Asistencia</h2>

    <form action="{{ route('asistencia.store') }}" method="POST">
        @csrf
  
        <div class="mb-3">
            <label for="id_Empleados" class="form-label">Empleado</label>
            <select name="id_Empleados" id="id_Empleados" class="form-select" required>
                <option value="">Seleccione un empleado</option>
                @foreach ($empleados as $empleado)
                    <option value="{{ $empleado->id_Empleados }}">{{ $empleado->Nombres }} {{ $empleado->Apellido }}</option>
                @endforeach
            </select>
        </div>
       
     

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de registro</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="entrada">Entrada</option>
                <option value="salida">Salida</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>

        @role('admin')
        <a href="{{ route('asistencia.index') }}" class="btn btn-secondary">Volver</a>
        @endrole

    </form>
</div>
@endsection
