
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>
  <body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<div class="container">
    <h2>Registrar Producción</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produccion.store') }}" method="POST">
        @csrf
        <div>
            <label for="id_Proyecto">Proyecto</label>
            <select name="id_Proyecto" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach($proyectos as $p)
                    <option value="{{ $p->id_Proyecto }}">{{ $p->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="id_Dosificacion" >Tipo de Dosificación</label>
            <select name="id_Dosificacion" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach($dosificaciones as $d)
                    <option value="{{ $d->id_Dosificacion }}">{{ $d->Tipo }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="id_Planta" >Planta</label>
            <select name="id_Planta" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach($plantas as $pl)
                    <option value="{{ $pl->id_Planta }}">{{ $pl->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="Fecha" >Fecha</label>
            <input type="date" name="Fecha" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="Volumen_m3" class="form-label">Volumen (m³)</label>
            <input type="number" step="0.01" name="Volumen_m3" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="Cemento_kg" class="form-label">Cemento (kg)</label>
            <input type="number" step="0.01" name="Cemento_kg" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="Arena_m3" class="form-label">Arena (m³)</label>
            <input type="number" step="0.01" name="Arena_m3" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="Piedrin_m3" class="form-label">Piedrín (m³)</label>
            <input type="number" step="0.01" name="Piedrin_m3" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="Aditivo_l" class="form-label">Aditivo (L)</label>
            <input type="number" step="0.01" name="Aditivo_l" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Producción</button>
        <br>
        <br>
    </form>
</div>
<div >
<table border="1" class="table" >
    <tr>
        <th  scope="col">Fecha</th>
        <th  scope="col" >Proyecto</th>
        <th  scope="col" >Planta</th>
        <th  scope="col" >Volumen (m³)</th>
        <th  scope="col" >Cemento (kg)</th>
        <th  scope="col" >Arena (m³)</th>
                <th scope="col" >Piedrín (m³)</th>
                <th scope="col" >Aditivo (L)</th>
    </tr>
    @foreach($producciones as $r)
    <tr>
          <td >{{ $r->Fecha }}</td>
                <td >{{ $r->proyecto->Nombre ?? '' }}</td>
                <td  >{{ $r->planta->Nombre ?? '' }}</td>
                <td  >{{ $r->Volumen_m3 }}</td>
                <td  >{{ $r->Cemento_kg }}</td>
                <td >{{ $r->Arena_m3 }}</td>
                <td  >{{ $r->Piedrin_m3 }}</td>
                <td  >{{ $r->Aditivo_l }}</td>
        <td>
            <a href="{{ route('produccion.edit', $r->id_Produccion) }}">Editar</a>
           
        </td>
    </tr>
    @endforeach
</table>
</div>
  </body>
</html>