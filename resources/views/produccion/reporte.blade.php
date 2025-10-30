

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
            <button type="submit" class="btn btn-primary">Todos los datos</button>
        </div>
    <table class="table table-striped table-sm">
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
    

    <div class="d-flex justify-content-center">
        {{ $registros->links() }}
    </div>
</div>
