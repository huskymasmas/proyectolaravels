@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container mt-4">
    <h2>{{ $empleado->exists ? 'Editar Empleado' : 'Nuevo Empleado' }}</h2>

    <form action="{{ $empleado->exists ? route('empleados.update', $empleado->id_Empleados) : route('empleados.store') }}" method="POST">
        @csrf
        @if($empleado->exists) @method('PUT') @endif

        <div class="row">

            <!-- Departamento -->
            <div class="col-md-4 mb-3">
                <label>Departamento</label>
                <select name="id_Departamento" class="form-select select2" required>
                    <option value="">Seleccione...</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep->id_Departamento }}" {{ old('id_Departamento', $empleado->id_Departamento) == $dep->id_Departamento ? 'selected' : '' }}>
                            {{ $dep->Nombres }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Rol -->
            <div class="col-md-4 mb-3">
                <label>Rol</label>
                <select name="id_Rol" class="form-select select2" required>
                    <option value="">Seleccione...</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id_Rol }}" {{ old('id_Rol', $empleado->id_Rol) == $rol->id_Rol ? 'selected' : '' }}>
                            {{ $rol->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Estado de trabajo -->
            <div class="col-md-4 mb-3">
                <label>Estado de Trabajo</label>
                <select name="Estado_trabajo" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="Activo" {{ old('Estado_trabajo', $empleado->Estado_trabajo) == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo" {{ old('Estado_trabajo', $empleado->Estado_trabajo) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <!-- Tipo de contrato -->
            <div class="col-md-4 mb-3">
                <label>Tipo de Contrato</label>
                <select name="Tipo_contrato" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="Planilla" {{ old('Tipo_contrato', $empleado->Tipo_contrato) == 'Planilla' ? 'selected' : '' }}>Planilla</option>
                    <option value="No Planilla" {{ old('Tipo_contrato', $empleado->Tipo_contrato) == 'No Planilla' ? 'selected' : '' }}>No Planilla</option>
                </select>
            </div>

            <!-- Otros campos -->
            <div class="col-md-4 mb-3">
                <label>Nombres</label>
                <input type="text" name="Nombres" value="{{ old('Nombres', $empleado->Nombres) }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Apellido</label>
                <input type="text" name="Apellido" value="{{ old('Apellido', $empleado->Apellido) }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Segundo Apellido</label>
                <input type="text" name="Apellido2" value="{{ old('Apellido2', $empleado->Apellido2) }}" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>Sexo</label>
                <select name="Sexo" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="M" {{ old('Sexo', $empleado->Sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ old('Sexo', $empleado->Sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Fecha de Nacimiento</label>
                <input type="date" name="Fecha_nacimiento" value="{{ old('Fecha_nacimiento', $empleado->Fecha_nacimiento) }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Fecha de Inicio</label>
                <input type="date" name="Fecha_inicio" value="{{ old('Fecha_inicio', $empleado->Fecha_inicio) }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>DPI</label>
                <input type="text" name="DPI" value="{{ old('DPI', $empleado->DPI) }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Teléfono</label>
                <input type="text" name="Numero" value="{{ old('Numero', $empleado->Numero) }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Código Empleado</label>
                <input type="text" name="Codigo_empleado" value="{{ old('Codigo_empleado', $empleado->Codigo_empleado) }}" class="form-control" required>
            </div>
           <div class="col-md-4 mb-3">
    <label>Nómina</label>
    <select name="id_Nomina" class="form-select select2">
        <option value="">Seleccione...</option>
        @foreach($nominas as $nomina)
            <option value="{{ $nomina->id_Nomina }}" {{ old('id_Nomina', $empleado->id_Nomina) == $nomina->id_Nomina ? 'selected' : '' }}>
                {{ $nomina->sueldo_Base . "" . $nomina->Descripcion }}
            </option>
        @endforeach
    </select>
</div>

        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('empleados.index') }}" class="btn btn-secondary">⬅️ Volver</a>
            <button type="submit" class="btn btn-success">{{ $empleado->exists ? 'Actualizar' : 'Guardar' }}</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Seleccione...",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection
