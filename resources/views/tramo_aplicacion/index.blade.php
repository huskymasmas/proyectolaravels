@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Listado de Tramos de Aplicación</h2>

    {{-- 🔹 Filtro por Proyecto --}}
    <form action="{{ route('tramo_aplicacion.index') }}" method="GET" class="mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <select name="proyecto" class="form-select">
                    <option value="">-- Seleccione Proyecto --</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id_Proyecto }}" 
                            {{ request('proyecto') == $proyecto->id_Proyecto ? 'selected' : '' }}>
                            {{ $proyecto->nombre ?? 'Proyecto '.$proyecto->id_Proyecto }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
            <div class="col-auto">
                <a href="{{ route('tramo_aplicacion.create') }}" class="btn btn-success">+ Nuevo Tramo</a>
            </div>
        </div>
    </form>

    {{-- 🔹 Tabla de tramos --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Proyecto</th>
                    <th>Fecha</th>
                    <th>Aplicador</th>
                    <th>Supervisor</th>
                    <th>Rodaduras</th>
                    <th>Cunetas</th>
                    
                    <th>Ancho Aditivo</th>
                    <th>Rendimiento (m²)</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tramos as $tramo)
                    <tr>
                        <td class="text-center">{{ $tramo->id_tramo }}</td>
                        <td>{{ $tramo->proyecto->Nombre ?? 'Proyecto '.$tramo->id_Proyecto }}</td>
                        <td>{{ $tramo->fecha }}</td>
                        <td>{{ $tramo->aplicador }}</td>
                        <td>{{ $tramo->supervisor }}</td>
                        
                        
                        <td>
                            @if($tramo->rodaduras->count() > 0)
                                <ul class="list-unstyled mb-0">
                                    @foreach($tramo->rodaduras as $rod)
                                        <li>eje {{ $rod->id_Ejes == 1 ? 'A' : 'B' }} | estacion final {{ $rod->estacion_final }} | estacion_inicial: {{ $rod->estacion_inicial }} </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Sin rodaduras</span>
                            @endif
                        </td>
                        <td>
                            @if($tramo->cunetas->count() > 0)
                                <ul class="list-unstyled mb-0">
                                    @foreach($tramo->cunetas as $cun)
                                        <li>eje {{ $cun->id_Ejes == 1 ? 'A' : 'B' }} | estacion final {{ $cun->estacion_final }} | estacion_inicial: {{ $cun->estacion_inicial }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Sin cunetas</span>
                            @endif
                        </td>
                        <td>{{ $tramo->Aditivo_Ancho }}</td>
                        <td>{{ $tramo->Rendimiento_M2 }}</td>
                        <td>{{ $tramo->observaciones }}</td>
                        <td class="text-center">
                            <a href="{{ route('tramo_aplicacion.edit', $tramo->id_tramo) }}" class="btn btn-sm btn-primary">Editar</a>
                            {{-- Aquí puedes agregar botón eliminar si quieres --}}
                        </td>
                        
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No hay tramos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
