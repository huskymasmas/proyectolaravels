@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Egreso de Materiales</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vale_egreso_material.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="id_Proyecto">Proyecto</label>
            <select name="id_Proyecto" id="id_Proyecto" class="form-control" required>
                <option value="">-- Seleccione Proyecto --</option>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id_Proyecto }}">{{ $proyecto->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="Nombre">Material</label>
            <select name="Nombre" class="form-control" required>
                <option value="">-- Seleccione Material --</option>
                @foreach($valesIngreso as $vale)
                    <option value="{{ $vale->Material }}">
                        {{ $vale->Material }} ({{ $vale->unidades->Nombre ?? 'Sin Unidad' }})
                    </option>
                @endforeach
            </select>
        </div>

         {{-- Resto de campos del fillable --}}
        <div class="mb-3">
            <label for="Nombre_encargado">Nombre Encargado</label>
            <input type="text" name="Nombre_encargado" class="form-control">
        </div>

        <div class="mb-3">
            <label for="Nombre_Bodeguero">Nombre Bodeguero</label>
            <input type="text" name="Nombre_Bodeguero" class="form-control">
        </div>

        <div class="mb-3">
            <label for="Nombre_Residente_obra">Nombre Residente de Obra</label>
            <input type="text" name="Nombre_Residente_obra" class="form-control">
        </div>

        <div class="mb-3">
            <label for="Nombre_conductor">Conductor</label>
            <input type="text" name="Nombre_conductor" class="form-control">
        </div>

        <div class="mb-3">
            <label for="marca">Marca</label>
            <input type="text" name="marca" class="form-control">
        </div>

        <div class="mb-3">
            <label for="serie">Serie</label>
            <input type="text" name="serie" class="form-control">
        </div>

        <div class="mb-3">
            <label for="placa">Placa</label>
            <input type="text" name="placa" class="form-control">
        </div>

        <div class="mb-3">
            <label for="Fecha">Fecha</label>
            <input type="date" name="Fecha" class="form-control">
        </div>

        <div class="mb-3">
            <label for="Hora_llegada">Hora de Llegada</label>
            <input type="time" name="Hora_llegada" class="form-control">
        </div>

        <div class="mb-3">
            <label for="Inicio_carga">Inicio de Carga</label>
            <input type="time" name="Inicio_carga" class="form-control">
        </div>

        <div class="mb-3">
            <label for="Finalizacion_carga">Finalización de Carga</label>
            <input type="time" name="Finalizacion_carga" class="form-control">
        </div>

        <div class="mb-3">
            <label for="Hora_salida_planta">Hora de Salida Planta</label>
            <input type="time" name="Hora_salida_planta" class="form-control">
        </div>

        <div class="mb-3">
            <label for="cantidad">Cantidad</label>
            <input type="number" step="0.01" name="cantidad" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="observaciones">Observaciones</label>
            <textarea name="observaciones" class="form-control"></textarea>
        </div>


        <button type="submit" class="btn btn-danger">Registrar Egreso</button>
    </form>
</div>
@endsection
