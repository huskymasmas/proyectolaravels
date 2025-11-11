@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2 class="mb-4 text-center fw-bold">Listado de Tramos</h2>

    {{-- ================== FILTRO POR PROYECTO ================== --}}
    <form method="GET" action="{{ route('tramos.index') }}" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="proyecto" class="form-label">Filtrar por Proyecto:</label>
                <select name="proyecto" id="proyecto" class="form-select">
                    <option value="">-- Todos los Proyectos --</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id_Proyecto }}" 
                            {{ request('proyecto') == $proyecto->id_Proyecto ? 'selected' : '' }}>
                            {{ $proyecto->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Buscar</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('tramos.index') }}" class="btn btn-secondary w-100">Limpiar</a>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('tramos.create') }}" class="btn btn-success">+ Nuevo Tramo</a>
            </div>
        </div>
    </form>

    {{-- ================== TABLA PRINCIPAL ================== --}}
    <div class="card">
        <div class="card-header bg-dark text-white">Tramos Registrados</div>
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Proyecto</th>
                        <th>Tipo Concreto</th>
                        <th>Cant. Concreto (m³)</th>
                        <th>Supervisor</th>
                        <th>Temperatura</th>
                        <th>Rodaduras</th>
                        <th>Cunetas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tramos as $tramo)
                    <tr>
                        <td class="text-center">{{ $tramo->id_tramo }}</td>
                        <td>{{ $tramo->fecha }}</td>
                        <td>{{ $tramo->proyecto->Nombre ?? 'Sin Proyecto' }}</td>
                        <td>{{ $tramo->tipo_concreto }}</td>
                        <td class="text-end">{{ number_format($tramo->cantidad_concreto_m3, 2) }}</td>
                        <td>{{ $tramo->supervisor }}</td>
                        <td class="text-center">{{ $tramo->temperatura }}</td>
                        <td>
                            @if($tramo->rodaduras->count() > 0)
                                <ul class="mb-0">
                                    @foreach($tramo->rodaduras as $rod)
                                        <li>
                                            Eje {{ $rod->id_Ejes }}:
                                            {{ $rod->estacion_inicial }} - {{ $rod->estacion_final }},
                                            {{ $rod->m2 }} m²
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <em class="text-muted">Sin rodaduras</em>
                            @endif
                        </td>
                        <td>
                            @if($tramo->cunetas->count() > 0)
                                <ul class="mb-0">
                                    @foreach($tramo->cunetas as $cun)
                                        <li>
                                            Eje {{ $cun->id_Ejes }}:
                                            {{ $cun->estacion_inicial }} - {{ $cun->estacion_final }},
                                            {{ $cun->m2 }} m²
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <em class="text-muted">Sin cunetas</em>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('tramos.show', $tramo->id_tramo) }}" class="btn btn-sm btn-info">Ver</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">No hay tramos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
