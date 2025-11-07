@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Registrar Vale de Despacho</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vale_despacho.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ============================
             SECCIÓN 1: DESPACHO CONCRETO
        ============================= --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Datos del Despacho de Concreto</h5>
            </div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Código Planta</label>
                    <input type="text" name="Codigo_planta" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Proyecto (ID)</label>
                    <input type="number" name="id_Proyecto" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Empresa (ID)</label>
                    <input type="number" name="id_Empresa" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="Fecha" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Volumen (m³)</label>
                    <input type="number" step="0.01" name="Volumen_carga_M3" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo de Concreto</label>
                    <input type="text" name="Tipo_Concreto" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Hora Salida Planta</label>
                    <input type="time" name="Hora_salida_plata" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Inicio Carga</label>
                    <input type="time" name="Inicio_Carga" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Finaliza Carga</label>
                    <input type="time" name="Finaliza_carga" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Hora Llega Proyecto</label>
                    <input type="time" name="Hora_llega_Proyecto" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo Elemento</label>
                    <input type="text" name="Tipo_elemento" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Placa / Número de Camión</label>
                    <input type="text" name="Placa_numero" class="form-control">
                </div>
            </div>
        </div>

        {{-- ============================
             SECCIÓN 2: DOSIFICACIÓN
        ============================= --}}
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Dosificación del Concreto</h5>
            </div>
            <div class="card-body row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kg Cemento Granel</label>
                    <input type="number" step="0.01" name="kg_cemento_granel" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sacos de Cemento</label>
                    <input type="number" step="0.01" name="Sacos_Cemento" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kg Piedrín</label>
                    <input type="number" step="0.01" name="kg_piedirn" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kg Arena</label>
                    <input type="number" step="0.01" name="Kg_arena" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Litros de Agua</label>
                    <input type="number" step="0.01" name="lts_agua" class="form-control">
                </div>
            </div>
        </div>

        {{-- ============================
             SECCIÓN 3: ADITIVOS
        ============================= --}}
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Aditivos Aplicados</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="col-md-3">
                            <label class="form-label">Nombre Aditivo {{ $i }}</label>
                            <input type="text" name="Nombre{{ $i }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cantidad (Lts o Kg)</label>
                            <input type="number" step="0.01" name="Cantidad{{ $i }}" class="form-control">
                        </div>
                    @endfor
                </div>

                <hr>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label class="form-label">Nombre Encargado Planta</label>
                        <input type="text" name="Nombre_encargado_palata" class="form-control">
                        <label class="form-label mt-2">Firma Encargado Planta</label>
                        <input type="file" name="Firma1" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombre Conductor</label>
                        <input type="text" name="Nombre_coductor" class="form-control">
                        <label class="form-label mt-2">Firma Conductor</label>
                        <input type="file" name="Firma2" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombre Recibe Conforme</label>
                        <input type="text" name="Nombre_Resibi_conforme" class="form-control">
                        <label class="form-label mt-2">Firma Recibe Conforme</label>
                        <input type="file" name="Firma3" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================
             BOTÓN GUARDAR
        ============================= --}}
        <div class="text-center">
            <button type="submit" class="btn btn-lg btn-primary px-5">Guardar Vale de Despacho</button>
            <a href="{{ route('vale_despacho.index') }}" class="btn btn-secondary btn-lg px-4 ms-3">Cancelar</a>
        </div>
    </form>
</div>
@endsection
