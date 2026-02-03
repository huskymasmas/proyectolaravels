@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Transferencia de Materiales y Maquinaria</h2>

    {{-- Mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Formulario de transferencia --}}
    <form action="{{ route('transferencia.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label>Material (Bodega General)</label>
                <select name="id_bodega_general" class="form-control" required>
                    <option value="">-- Seleccione Material --</option>
                    @foreach($bodegasGenerales as $bg)
                        <option value="{{ $bg->id_Bodega_general }}">
                            {{ $bg->Nombre }} ({{ $bg->Cantidad }} {{ $bg->unidad->Nombre ?? '' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Proyecto (Bodega Para Proyectos)</label>
                <select name="id_bodega_proyecto" class="form-control" required>
                    <option value="">-- Seleccione Proyecto --</option>
                    @foreach($bodegasProyectos as $bp)
                        <option value="{{ $bp->id_Bodega_para_proyectos }}">
                            {{ $bp->proyecto->Nombre ?? 'Proyecto #' . $bp->id_Proyecto }}
                            (Máx: {{ $bp->Cantidad_maxima }}, Almacenado: {{ $bp->Almazenado }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label>Cantidad</label>
                <input type="number" name="cantidad" class="form-control" step="0.001" min="0.001" required>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Transferir</button>
            </div>
        </div>
    </form>

    {{-- Listado de transferencias --}}
    <h4 class="mt-5">Historial de Transferencias</h4>
    <div class="accordion" id="transferenciasAccordion">
        @foreach($transferencias as $index => $tran)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $index }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                        {{ $tran->material }} → Proyecto: {{ $tran->proyecto }}
                        (Cantidad: {{ $tran->cantidad }} {{ $tran->unidad->Nombre ?? '' }})
                    </button>
                </h2>
                <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#transferenciasAccordion">
                    <div class="accordion-body">
                        <ul>
                            <li>Fecha: {{ $tran->Fecha_creacion }}</li>
                            <li>Creado por: {{ $tran->Creado_por }}</li>
                            <li>Estado: {{ $tran->Estado == 1 ? 'Activo' : 'Inactivo' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
