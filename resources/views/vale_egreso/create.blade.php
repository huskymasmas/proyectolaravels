@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Crear Vale de Egreso (Concreto)</h2>

    <form action="{{ route('vale_egreso.store') }}" method="POST">
        @csrf

        {{-- =========================
            DATOS GENERALES DEL DESPACHO
        ========================== --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Datos del Despacho</div>
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label class="form-label">Código Planta</label>
                    <input type="text" name="Codigo_planta" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Proyecto</label>
                    <select name="id_Proyecto" class="form-control" required>
                        <option value="">-- Seleccione --</option>
                        @foreach($proyectos as $p)
                        <option value="{{ $p->id_Proyecto }}">{{ $p->Nombre}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Empresa</label>
                    <select name="id_Empresa" class="form-control">
                        <option value="">-- Seleccione --</option>
                        @foreach($empresas as $e)
                        <option value="{{ $e->id_Empresa }}">{{ $e->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="Fecha" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Hora salida planta</label>
                    <input type="time" name="Hora_salida_plata" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tipo Concreto</label>
                    <input type="text" name="Tipo_Concreto" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Volumen carga (m³)</label>
                    <input type="number" step="0.01" name="Volumen_carga_M3" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Placa vehículo</label>
                    <input type="text" name="Placa_numero" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo elemento</label>
                    <input type="text" name="Tipo_elemento" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select name="Estado" class="form-control">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- =========================
            DOSIFICACIÓN
        ========================== --}}
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">Dosificación de Materiales</div>
            <div class="card-body row g-3">

                <div class="col-md-3">
                    <label class="form-label">Cemento granel (kg)</label>
                    <input type="number" step="0.01" name="kg_cemento_granel" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sacos cemento (unidades)</label>
                    <input type="number" step="0.01" name="Sacos_Cemento" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Piedrín (kg)</label>
                    <input type="number" step="0.01" name="kg_piedirn" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Arena (kg)</label>
                    <input type="number" step="0.01" name="Kg_arena" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Agua (lts)</label>
                    <input type="number" step="0.01" name="lts_agua" class="form-control">
                </div>

            </div>
        </div>

        {{-- =========================
            ADITIVOS (SIN FIRMAS)
        ========================== --}}
        <div class="card mb-4">
            <div class="card-header bg-info text-white">Aditivos Aplicados</div>
            <div class="card-body row g-3">

                @for ($i = 1; $i <= 4; $i++)
                <div class="col-md-6">
                    <label class="form-label">Aditivo {{ $i }} - Nombre</label>
                    <input type="text" name="Nombre{{ $i }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Aditivo {{ $i }} - Cantidad</label>
                    <input type="number" step="0.01" name="Cantidad{{ $i }}" class="form-control">
                </div>
                @endfor

                <hr>

                <div class="col-md-4">
                    <label class="form-label">Encargado planta</label>
                    <input type="text" name="Nombre_encargado_palata" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Conductor</label>
                    <input type="text" name="Nombre_coductor" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Recibe conforme</label>
                    <input type="text" name="Nombre_Resibi_conforme" class="form-control">
                </div>

            </div>
        </div>

        {{-- ================
            BOTÓN
        ================= --}}
        <button class="btn btn-success w-100">Guardar Vale</button>

    </form>

</div>
@endsection
