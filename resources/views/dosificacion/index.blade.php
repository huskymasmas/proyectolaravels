@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Dosificaciones</h2>

    <form method="GET" action="{{ route('dosificacion.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <select name="tipo" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Filtrar por tipo --</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id_Tipo_dosificacion }}" 
                            {{ $tipoSeleccionado == $tipo->id_Tipo_dosificacion ? 'selected' : '' }}>
                            {{ $tipo->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <a href="{{ route('dosificacion.create') }}" class="btn btn-primary mb-3">Nueva Dosificación</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Proyecto</th>
                <th>Cemento<br>(m³ / kg)</th>
                <th>Arena<br>(m³)</th>
                <th>Pedrín<br>(m³)</th>
                <th>Aditivo<br>(m³ / L)</th>
                <th>Nota</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dosificaciones as $d)

            @php
                // Única conversión real: cemento
                $densidadCemento = 1440; // kg/m³
                $cementoKg = $d->Cemento * $densidadCemento;

                // Aditivo de m³ a litros
                $aditivoLitros = $d->Aditivo * 1000;
            @endphp

            <tr>
                <td>{{ $d->id_Dosificacion }}</td>
                <td>{{ $d->Tipo_dosificador->Nombre ?? 'Sin tipo' }}</td>
                <td>{{ $d->Proyecto->Nombre ?? 'Sin proyecto' }}</td>

                {{-- Cemento --}}
                <td>
                    {{ $d->Cemento }} m³ <br>
                    <strong>{{ number_format($cementoKg, 2) }} kg</strong>
                </td>

                {{-- Arena --}}
                <td>
                    {{ $d->Arena }} m³
                </td>

                {{-- Pedrín --}}
                <td>
                    {{ $d->Pedrin }} m³
                </td>

                {{-- Aditivo --}}
                <td>
                    {{ $d->Aditivo }} m³ <br>
                    <strong>{{ number_format($aditivoLitros, 2) }} L</strong>
                </td>

                <td>{{ $d->Nota }}</td>

                <td>
                    <a href="{{ route('dosificacion.edit', $d->id_Dosificacion) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('dosificacion.destroy', $d->id_Dosificacion) }}" 
                        method="POST" style="display:inline-block">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Eliminar esta dosificación?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>
@endsection
