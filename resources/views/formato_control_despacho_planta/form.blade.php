@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>{{ isset($registro) ? 'Editar Registro' : 'Nuevo Registro' }}</h2>

    <form action="{{ isset($registro) ? route('formato_control_despacho_planta.update', $registro->id_Formato_control_despacho_planta) : route('formato_control_despacho_planta.store') }}" method="POST">
        @csrf
        @if(isset($registro))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-3">
                <label>Fecha</label>
                <input type="date" name="Fecha" class="form-control" value="{{ old('Fecha', $registro->Fecha ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label>No. Envío</label>
                <input type="number" name="No_envio" class="form-control" value="{{ old('No_envio', $registro->No_envio ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label>Conductor</label>
                <input type="text" name="Conductor" class="form-control" value="{{ old('Conductor', $registro->Conductor ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label>Aldea</label>
                <select name="id_Aldea" class="form-control" required>
                    <option value="">Seleccione...</option>
                    @foreach($aldeas as $a)
                        <option value="{{ $a->id_aldea }}" {{ old('id_Aldea', $registro->id_Aldea ?? '') == $a->id_aldea ? 'selected' : '' }}>
                            {{ $a->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label>Tipo de Concreto (PSI)</label>
                <input type="number" step="0.01" name="Tipo_de_Concreto_ps" class="form-control" value="{{ old('Tipo_de_Concreto_ps', $registro->Tipo_de_Concreto_ps ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label>Cant. Concreto (m³)</label>
                <input type="number" step="0.001" name="Cantidad_Concreto_mT3" class="form-control" value="{{ old('Cantidad_Concreto_mT3', $registro->Cantidad_Concreto_mT3 ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label>Granel (kg)</label>
                <input type="number" step="0.001" name="Concreto_granel_kg" class="form-control" value="{{ old('Concreto_granel_kg', $registro->Concreto_granel_kg ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label>Sacos (kg)</label>
                <input type="number" step="0.001" name="Concreto_sacos_kg" class="form-control" value="{{ old('Concreto_sacos_kg', $registro->Concreto_sacos_kg ?? '') }}" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label>Total (kg)</label>
                <input type="number" step="0.001" name="total" class="form-control" value="{{ old('total', $registro->total ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label>Piedrín (kg)</label>
                <input type="number" step="0.001" name="kg_Piedrin" class="form-control" value="{{ old('kg_Piedrin', $registro->kg_Piedrin ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label>Arena (kg)</label>
                <input type="number" step="0.001" name="kg_Arena" class="form-control" value="{{ old('kg_Arena', $registro->kg_Arena ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label>Agua (lts)</label>
                <input type="number" step="0.001" name="Lts_Agua" class="form-control" value="{{ old('Lts_Agua', $registro->Lts_Agua ?? '') }}" required>
            </div>
        </div>

        <h5 class="mt-4">Aditivos</h5>
        <div class="row mt-2">
            @for($i=1; $i<=4; $i++)
                <div class="col-md-3">
                    <label>Aditivo {{ $i }} (nombre)</label>
                    <input type="text" name="Aditivo{{ $i }}" class="form-control" value="{{ old('Aditivo'.$i, $registro->{'Aditivo'.$i} ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label>Cantidad (lts)</label>
                    <input type="number" step="0.001" name="cantidad{{ $i }}" class="form-control" value="{{ old('cantidad'.$i, $registro->{'cantidad'.$i} ?? '') }}">
                </div>
            @endfor
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <label>Supervisor</label>
                <input type="text" name="Supervisor" class="form-control" value="{{ old('Supervisor', $registro->Supervisor ?? '') }}">
            </div>
            <div class="col-md-4">
                <label>Empleado</label>
                <select name="id_Empleados" class="form-control" required>
                    <option value="">Seleccione...</option>
                    @foreach($empleados as $e)
                        <option value="{{ $e->id_Empleados }}" {{ old('id_Empleados', $registro->id_Empleados ?? '') == $e->id_Empleados ? 'selected' : '' }}>
                            {{ $e->Nombres }} {{ $e->Apellido }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Observaciones</label>
                <input type="text" name="Observaciones" class="form-control" value="{{ old('Observaciones', $registro->Observaciones ?? '') }}">
            </div>
        </div>

        <button class="btn btn-primary mt-4">{{ isset($registro) ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('formato_control_despacho_planta.index') }}" class="btn btn-secondary mt-4">Cancelar</a>
    </form>
</div>
@endsection
