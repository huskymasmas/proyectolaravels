

<div class="container">
    <h2>Reporte de Producción</h2>
    
    <form method="GET" action="{{ route('produccion.reporte') }}" class="row g-3 mb-3">
        <div class="col-md-3">
            <label>Desde</label>
            <input type="date" name="desde" value="{{ $desde }}" class="form-control">
        </div>
         
        <div class="col-md-3">
            <label>Hasta</label>
            <input type="date" name="hasta" value="{{ $hasta }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label>Planta</label>
            <div class="col-md-3">
    <label>Planta</label>
    <select name="id_planta" class="form-select">
        <option value="">Todas</option>
        @foreach($plantas as $pl)
            <option value="{{ $pl->id_Planta }}" {{ request('id_planta') == $pl->id_Planta ? 'selected' : '' }}>
                {{ $pl->Nombre }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <label>Proyecto</label>
    <select name="id_Proyecto" class="form-select">
        <option value="">Todos</option>
        @foreach($proyectos as $p)
            <option value="{{ $p->id_Proyecto }}" {{ request('id_Proyecto') == $p->id_Proyecto ? 'selected' : '' }}>
                {{ $p->Nombre }}
            </option>
        @endforeach
    </select>
</div>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>
           <div class="col-md-3">
        
        </div>
    <table border="1">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Proyecto</th>
                <th>Planta</th>
                <th>Volumen (m³)</th>
                <th>Cemento (kg)</th>
                <th>Arena (m³)</th>
                <th>Piedrín (m³)</th>
                <th>Aditivo (L)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registros as $r)
            <tr>
                <td>{{ $r->Fecha }}</td>
                <td>{{ $r->proyecto->Nombre ?? '' }}</td>
                <td>{{ $r->planta->Nombre ?? '' }}</td>
                <td>{{ $r->Volumen_m3 }}</td>
                <td>{{ $r->Cemento_kg }}</td>
                <td>{{ $r->Arena_m3 }}</td>
                <td>{{ $r->Piedrin_m3 }}</td>
                <td>{{ $r->Aditivo_l }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
<div class="card mt-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Totales del período</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <strong>Volumen total (m³):</strong> {{ number_format($totales['total_m3'], 2) }}
            </div>
            <div class="col-md-4">
                <strong>Horas totales:</strong> {{ number_format($totales['total_hours'], 2) }}
            </div>
            <div class="col-md-4">
                <strong>Producción promedio (m³/h):</strong> 
                {{ $totales['m3_per_hour'] ? number_format($totales['m3_per_hour'], 2) : 'N/A' }}
            </div>
        </div>

        <h6 class="mt-3">Consumo total de materiales:</h6>
        <ul class="list-group">
            <li class="list-group-item">Cemento (kg): {{ number_format($totales['consumo_real']['Cemento_kg'], 2) }}</li>
            <li class="list-group-item">Arena (m³): {{ number_format($totales['consumo_real']['Arena_m3'], 3) }}</li>
            <li class="list-group-item">Piedrín (m³): {{ number_format($totales['consumo_real']['Piedrin_m3'], 3) }}</li>
            <li class="list-group-item">Aditivo (L): {{ number_format($totales['consumo_real']['Aditivo_l'], 2) }}</li>
        </ul>
    </div>
</div>

    <div class="d-flex justify-content-center">
        {{ $registros->links() }}
    </div>
</div>
