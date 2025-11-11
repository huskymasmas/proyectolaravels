@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-body">
            <h3 class="text-center text-primary fw-bold mb-4">
                {{ isset($formato) ? 'Editar Registro' : 'Nuevo Registro' }}
            </h3>

            <form method="POST" action="{{ isset($formato) ? route('formato_despacho.update', $formato->id_Formato_control_despacho_planta) : route('formato_despacho.store') }}">
                @csrf
                @if(isset($formato)) @method('PUT') @endif

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>No. Envío</label>
                        <input type="number" name="No_envio" class="form-control" value="{{ $formato->No_envio ?? '' }}" required>
                    </div>
                    <div class="col-md-3">
                        <label>Tipo Concreto (PS)</label>
                        <input type="number" step="0.01" name="Tipo_de_Concreto_ps" class="form-control" value="{{ $formato->Tipo_de_Concreto_ps ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label>Cantidad (m³)</label>
                        <input type="number" step="0.001" name="Cantidad_Concreto_mT3" class="form-control" value="{{ $formato->Cantidad_Concreto_mT3 ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label>Empleado</label>
                        <select name="id_Empleados" class="form-select" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($empleados as $e)
                                <option value="{{ $e->id_Empleados }}" {{ isset($formato) && $formato->id_Empleados == $e->id_Empleados ? 'selected' : '' }}>
                                    {{ $e->Nombres }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Observaciones</label>
                    <textarea name="Observaciones" class="form-control">{{ $formato->Observaciones ?? '' }}</textarea>
                </div>

                <div class="text-end">
                    <button class="btn btn-primary">Guardar</button>
                    <a href="{{ route('formato_despacho.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Editar Formato de Despacho</h3>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('formato_despacho.update', $formato->id_Formato_control_despacho_planta) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">No Envío:</label>
                    <input type="text" class="form-control" value="{{ $formato->No_envio }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Proyecto:</label>
                    <input type="text" class="form-control" 
                        value="{{ $formato->proyecto->Nombre ?? 'No asignado' }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="id_Empleados" class="form-label">Supervisor:</label>
                    <select name="id_Empleados" id="id_Empleados" class="form-select" required>
                        <option value="">Seleccione...</option>
                        @foreach ($empleados as $empleado)
                            <option value="{{ $empleado->id_Empleados }}" 
                                {{ $formato->id_Empleados == $empleado->id_Empleados ? 'selected' : '' }}>
                                {{ $empleado->Nombres }} {{ $empleado->Apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="Observaciones" class="form-label">Observaciones:</label>
                    <textarea name="Observaciones" id="Observaciones" class="form-control" rows="3">{{ $formato->Observaciones }}</textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <a href="{{ route('formato_despacho.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
