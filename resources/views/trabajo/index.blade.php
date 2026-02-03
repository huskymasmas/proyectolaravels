@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2 class="text-center mb-4">Listado de renglon de trabajos</h2>

    {{-- FILTRO --}}
    <form method="GET" action="{{ route('trabajo.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <select name="aldea" class="form-select">
                    <option value="">-- Seleccionar Aldea --</option>
                    @foreach($aldeas as $aldea)
                        <option value="{{ $aldea->id_aldea }}" {{ request('aldea') == $aldea->id_aldea ? 'selected' : '' }}>
                            {{ $aldea->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filtrar</button>
            </div>

            <div class="col-md-6 text-end">
                <a href="{{ route('trabajo.export.excel', ['aldea' => request('aldea')]) }}"
                   class="btn btn-success">Excel</a>

                <a href="{{ route('trabajo.export.pdf', ['aldea' => request('aldea')]) }}"
                   class="btn btn-danger">PDF</a>

                {{-- BOTÓN PARA SUBIR PLANO GLOBAL POR ALDEA --}}
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalPlanoAldea">
                    Subir Plano por Aldea
                </button>

                <a href="{{ route('trabajo.create') }}" class="btn btn-secondary">Nuevo</a>
            </div>
        </div>
    </form>


    {{-- TABLA PRINCIPAL --}}
    <table class="table table-bordered">
        <thead class="table-warning text-center">
            <tr>
                <th>No.</th>
                <th>Renglón</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Costo</th>
                <th>Subtotal</th>
                <th>Estado</th>
               
            </tr>
        </thead>

        <tbody>

            @php 
                $aldeaActual = '';
                $estadoActual = '';
                $totalPorEstado = 0;
            @endphp

            @foreach($trabajos->sortBy(['id_aldea','id_Estado_trabajo']) as $i => $t)

                {{-- CAMBIO DE ALDEA --}}
                @if($aldeaActual != ($t->aldea->Nombre ?? 'SIN ALDEA'))

                    @if($i != 0)
                        <tr class="table-light fw-bold">
                            <td colspan="5" class="text-end">Subtotal {{ strtoupper($estadoActual) }}</td>
                            <td class="text-end">Q{{ number_format($totalPorEstado, 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    @endif

                    @php
                        $aldeaActual = $t->aldea->Nombre ?? 'SIN ALDEA';
                        $estadoActual = '';
                        $totalPorEstado = 0;
                    @endphp

                    <tr class="table-primary fw-bold text-center">
                        <td colspan="8">ALDEA: {{ strtoupper($aldeaActual) }}</td>
                    </tr>
                @endif


                {{-- CAMBIO DE ESTADO --}}
                @if($estadoActual != ($t->estadoTrabajo->Nombre ?? 'SIN ESTADO'))

                    @if($estadoActual != '' && $i != 0)
                        <tr class="table-light fw-bold">
                            <td colspan="5" class="text-end">Subtotal {{ strtoupper($estadoActual) }}</td>
                            <td class="text-end">Q{{ number_format($totalPorEstado, 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    @endif

                    @php 
                        $estadoActual = $t->estadoTrabajo->Nombre ?? 'SIN ESTADO';
                        $totalPorEstado = 0;
                    @endphp

                    <tr class="table-secondary fw-bold text-center">
                        <td colspan="8">{{ strtoupper($estadoActual) }}</td>
                    </tr>

                @endif


                {{-- FILA DE TRABAJO --}}
                <tr>
                    <td>{{ $t->Numero_face }}</td>
                    <td>{{ $t->Nombre_face }}</td>
                    <td class="text-end">{{ number_format($t->Cantidad, 2) }}</td>
                    <td>{{ $t->unidad->Nombre ?? '' }}</td>
                    <td class="text-end">Q{{ number_format($t->CostoQ, 2) }}</td>
                    <td class="text-end">Q{{ number_format($t->Subtotal, 2) }}</td>
                    <td>{{ $estadoActual }}</td>

                    <td class="text-center">
                        @foreach($t->planos as $p)
                            <a href="{{ route('planos.ver', $p->id_planos) }}" 
                               target="_blank" 
                               class="badge bg-info text-dark d-block mb-1">
                                📄 {{ $p->nombre }}
                            </a>
                        @endforeach

                      
                    </td>
                </tr>

                @php
                    $totalPorEstado += $t->Subtotal;
                @endphp


                {{-- MODAL SUBIR PLANO POR TRABAJO --}}
                <div class="modal fade" id="modalPlano{{ $t->id_trabajos }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form action="{{ route('planos.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-header">
                                    <h5 class="modal-title">Agregar Plano al Trabajo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <input type="hidden" name="id_trabajo" value="{{ $t->id_trabajos }}">

                                    <div class="mb-3">
                                        <label>Nombre del Plano</label>
                                        <input type="text" name="nombre" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Archivo</label>
                                        <input type="file" name="plano" class="form-control" required>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-success">Guardar</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            @endforeach


            {{-- ÚLTIMO SUBTOTAL --}}
            @if(count($trabajos) > 0)
                <tr class="table-light fw-bold">
                    <td colspan="5" class="text-end">Subtotal {{ strtoupper($estadoActual) }}</td>
                    <td class="text-end">Q{{ number_format($totalPorEstado, 2) }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif

        </tbody>
    </table>


    {{-- ================================ --}}
    {{--     PLANOS DE LA ALDEA (Único)     --}}
    {{-- ================================ --}}
    @if(request('aldea'))
        <h4 class="mt-4">Planos de la Aldea Seleccionada</h4>

        @if($planosAldea->count() == 0)
            <p class="text-muted">Esta aldea no tiene planos guardados.</p>
        @else
            <table class="table table-bordered mt-3">
                <thead class="table-info text-center">
                    <tr>
                        <th>Plano</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($planosAldea as $p)
                        <tr>
                            <td>{{ $p->nombre }}</td>
                            <td class="text-center">
                                <a href="{{ route('planos.ver', $p->id_planos) }}" 
                                   target="_blank"
                                   class="btn btn-info btn-sm">
                                    Ver Plano
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif



    {{-- MODAL SUBIR PLANO DE ALDEA --}}
    <div class="modal fade" id="modalPlanoAldea" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="{{ route('planos.aldea.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Subir Plano para Aldea</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <label>Aldea</label>
                        <select name="id_aldea" class="form-select mb-3" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($aldeas as $a)
                                <option value="{{ $a->id_aldea }}">{{ $a->Nombre }}</option>
                            @endforeach
                        </select>

                        <label>Nombre del Plano</label>
                        <input type="text" name="nombre" class="form-control mb-3" required>

                        <label>Archivo</label>
                        <input type="file" name="plano" class="form-control" required>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success">Guardar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


</div>
@endsection
