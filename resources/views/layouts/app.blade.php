
<div class="container">
  <h2>Registrar mezcla (producción)</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form action="{{ route('produccion.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label>Proyecto</label>
      <select name="id_Proyecto" class="form-control" required>
        @foreach($proyectos as $p)
          <option value="{{ $p->id_Proyecto }}">{{ $p->Nombre }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label>Dosificación</label>
      <select name="id_Dosificacion" class="form-control" required>
        @foreach($dosificaciones as $d)
          <option value="{{ $d->id_Dosificacion }}">{{ $d->Tipo }} — Cemento:{{ $d->Cemento }} | Arena:{{ $d->Arena }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label>Planta</label>
      <select name="id_Planta" class="form-control" required>
        @foreach($plantas as $pl)
          <option value="{{ $pl->id_Planta }}">{{ $pl->Nombre }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label>Fecha</label>
      <input type="date" name="Fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
    </div>

    <div class="row">
      <div class="col">
        <label>Volumen (m³)</label>
        <input step="0.01" type="number" name="Volumen_m3" class="form-control" required>
      </div>
      <div class="col">
        <label>Cemento (kg)</label>
        <input step="0.01" type="number" name="Cemento_kg" class="form-control" required>
      </div>
      <div class="col">
        <label>Arena (m³)</label>
        <input step="0.001" type="number" name="Arena_m3" class="form-control" required>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label>Piedrín (m³)</label>
        <input step="0.001" type="number" name="Piedrin_m3" class="form-control" required>
      </div>
      <div class="col">
        <label>Aditivo (L)</label>
        <input step="0.01" type="number" name="Aditivo_l" class="form-control" required>
      </div>
      <div class="col">
        <label>ID Vale (opcional)</label>
        <input type="number" name="id_Vale" class="form-control">
      </div>
    </div>

    <button class="btn btn-primary mt-3">Guardar</button>
  </form>
</div>

