@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3 text-center">Listado de Detalles de Obra</h2>

    {{-- Filtro por proyecto --}}
    <form method="GET" action="{{ route('detalles.index') }}" class="row mb-3">
        <div class="col-md-6">
            <label for="id_Proyecto" class="form-label">Filtrar por Proyecto:</label>
            <select name="id_Proyecto" id="id_Proyecto" class="form-select" onchange="this.form.submit()">
                <option value="">-- Todos los proyectos --</option>
                @foreach ($proyectos as $proyecto)
                    <option value="{{ $proyecto->id_Proyecto }}" {{ $idProyecto == $proyecto->id_Proyecto ? 'selected' : '' }}>
                        {{ $proyecto->Nombre ?? 'Proyecto sin nombre' }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>


    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('detalles.create') }}" class="btn btn-success"> Nuevo Detalle</a>
    </div>


        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Proyecto</th>
                        <th>Tipo de Obra</th>
                        <th>Valor</th>
                        <th>Unidad</th>
                        <th>Detalle</th>
                        <th>Cálculo</th>
                        <th>Resultado</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detalle as $item)
                        <tr>
                            <td>{{ $item->id_Detalle_obra }}</td>
                            <td>{{ $item->proyecto->Nombre ?? 'N/A' }}</td>
                            <td>{{ $item->Tipo_Obra }}</td>
                            <td>{{ number_format($item->Valor, 2) }}</td>
                            <td>{{ $item->unidad->Nombre ?? 'N/A' }}</td>
                            <td>{{ $item->Detalle }}</td>
                            <td>{{ $item->Calculo }}</td>
                            <td class="fw-bold">{{ number_format($item->Resultado, 2) }}</td>
                            <td>{{ $item->Descripcion }}</td>
                            <td>
                                <form action="{{ route('detalles.destroy', $item->id_Detalle_obra) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este detalle?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"> Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No hay detalles registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 text-end">
                <h5 class="fw-bold">Total General: {{ number_format($detalletotal ?? 0, 2) }}</h5>
            </div>
        </div>

</div>
@endsection
