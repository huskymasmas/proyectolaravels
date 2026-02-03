@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Requerimientos de Materiales por Proyecto</h2>


 
    {{-- FILTRO DE PROYECTO --}}
    <form method="GET" action="{{ route('requerimientos.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <select name="id_Proyecto" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Seleccionar Proyecto --</option>
                    @foreach ($proyectos as $proyecto)
                        <option value="{{ $proyecto->id_Proyecto }}" 
                            {{ $idProyecto == $proyecto->id_Proyecto ? 'selected' : '' }}>
                            {{ $proyecto->Nombre ?? 'Proyecto '.$proyecto->id_Proyecto }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div class="text-end mb-3">
        <a href="{{ route('requerimientos.create') }}" class="btn btn-primary">
            Nuevo Requerimiento
        </a>
    </div>

        <div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dosificación</th>
                        <th>Detalle Obra</th>
                        <th>Cemento (kg)</th>
                        <th>Cemento (sacos)</th>
                        <th>Arena (m³)</th>
                        <th>Arena (kg)</th>
                        <th>Piedrín (m³)</th>
                        <th>Piedrín (kg)</th>
                        <th>Aditivo (L)</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requerimientos as $req)
                    <tr>
                        <td>{{ $req->id_requerimiento_material }}</td>
                        <td>{{ $req->dosificacion->id_Tipo_dosificacion ?? 'N/A' }}</td>
                        <td>{{ $req->detalleObra->Tipo_Obra ?? 'N/A' }}</td>
                        <td>{{ number_format($req->Cemento_kg, 3) }}</td>
                        <td>{{ number_format($req->Cemento_sacos, 3) }}</td>
                        <td>{{ number_format($req->Arena_m3, 3) }}</td>
                        <td>{{ number_format($req->Arena_kg, 3) }}</td>
                        <td>{{ number_format($req->Piedrin_m3, 3) }}</td>
                        <td>{{ number_format($req->Piedrin_kg, 3) }}</td>
                        <td>{{ number_format($req->Aditivo_l, 3) }}</td>
                        <td>{{ $req->Fecha_creacion }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

</div>
@endsection
