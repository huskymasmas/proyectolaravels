@extends('layouts.app')

@section('title', 'Nuevo Vale de Ingreso')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📥 Registrar Vale de Ingreso</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('vale_ingreso.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Fecha y Hora --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Fecha" class="form-label">Fecha</label>
                        <input type="date" name="Fecha" id="Fecha" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="Hora_llegada" class="form-label">Hora de llegada</label>
                        <input type="time" name="Hora_llegada" id="Hora_llegada" class="form-control" required>
                    </div>
                </div>

                {{-- Material y Cantidad --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Tipo_material" class="form-label">Tipo de Material</label>
                        <input type="text" name="Tipo_material" id="Tipo_material" class="form-control" placeholder="Ej. Cemento, Arena, Piedrín" required>
                    </div>
                    <div class="col-md-6">
                        <label for="Cantidad" class="form-label">Cantidad</label>
                        <input type="number" step="0.01" name="Cantidad" id="Cantidad" class="form-control" required>
                    </div>
                </div>

                {{-- Unidad y Proyecto --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="id_Unidades" class="form-label">Unidad</label>
                        <select name="id_Unidades" id="id_Unidades" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach ($unidades as $unidad)
                                <option value="{{ $unidad->id_Unidades }}">{{ $unidad->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="id_Proyecto" class="form-label">Proyecto</label>
                        <input type="number" name="id_Proyecto" id="id_Proyecto" class="form-control" placeholder="ID del proyecto" required>
                    </div>
                </div>

                {{-- Empresa y Observaciones --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                    <label for="id_Empresa" class="form-label">Empresa</label>
                    <select name="id_Empresa" id="id_Empresa" class="form-select" required>
                    <option value="">Seleccione una empresa...</option>
                    @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->id_Empresa }}">{{ $empresa->Nombre }}</option>
                    @endforeach
                    </select>
                </div>

                    <div class="col-md-6">
                        <label for="Observaciones" class="form-label">Observaciones</label>
                        <textarea name="Observaciones" id="Observaciones" rows="2" class="form-control"></textarea>
                    </div>
                </div>

                {{-- Datos de transporte --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="Nombre_coductor" class="form-label">Nombre del Conductor</label>
                        <input type="text" name="Nombre_coductor" id="Nombre_coductor" class="form-control">
                    </div>
                 
                    <div class="col-md-4">
                        <label for="Nombre_encargado_palata" class="form-label">Nombre del Encargado de Planta</label>
                        <input type="text" name="Nombre_encargado_palata" id="Nombre_encargado_palata" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label for="Nombre_bodegero" class="form-label">Nombre del Bodegero</label>
                        <input type="text" name="Nombre_bodegero" id="Nombre_bodegero" class="form-control">
                    </div>
                     <div class="col-md-4">
                        <label for="Nombre_residente_obra" class="form-label">Nombre del Residente de Obra</label>
                        <input type="text" name="Nombre_residente_obra" id="Nombre_residente_obra" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label for="Placa_vehiculo" class="form-label">Placa del Vehículo</label>
                        <input type="text" name="Placa_vehiculo" id="Placa_vehiculo" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="Origen_material" class="form-label">Origen del Material</label>
                        <input type="text" name="Origen_material" id="Origen_material" class="form-control">
                    </div>
                </div>

                {{-- Firmas --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="Firma1" class="form-label">Firma Encargado Planta</label>
                        <input type="file" name="Firma1" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-4">
                        <label for="Firma2" class="form-label">Firma Bodeguero</label>
                        <input type="file" name="Firma2" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-4">
                        <label for="Firma3" class="form-label">Firma Residente de Obra</label>
                        <input type="file" name="Firma3" class="form-control" accept="image/*">
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('vale_ingreso.index') }}" class="btn btn-secondary">
                        ⬅️ Regresar
                    </a>
                    <button type="submit" class="btn btn-success">
                        💾 Guardar Vale
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
