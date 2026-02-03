@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Control de Producción</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- FORMULARIO --}}
    <div class="card mb-3">
        <div class="card-body">
            <form id="formControl" method="POST" action="{{ route('control_produccion.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Proyecto</label>
                        <select name="id_Proyecto" id="id_Proyecto" class="form-select" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($proyectos as $p)
                                <option value="{{ $p->id_Proyecto }}">{{ $p->Nombre_Proyecto ?? $p->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Tipo de Dosificación</label>
                        <select name="id_Tipo_dosificacion" id="id_Tipo_dosificacion" class="form-select" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($dosificaciones as $d)
                                <option value="{{ $d->id_Tipo_dosificacion }}">{{ $d->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success">Agregar siguiente vale</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

 

    {{-- TABLA DE CONTROL DE PRODUCCIÓN --}}
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Historial de Control de Producción</h5>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Proyecto</th>
                        <th>Dosificación</th>
                        <th>Fecha</th>
                        <th>Sacos</th>
                        <th>Cemento total</th>
                        <th>Arena</th>
                        <th>Piedrín</th>
                        <th>Aditivo</th>
                        <th>Piedrin salida</th>
                        <th>Arena salida</th>
                        <th>Placa</th>
                        <th>Conductor</th>
                        <th>Viajes</th>
                        <th>Fecha creación</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($controles as $c)
                        <tr>
                            <td>{{ $c->id_control_produccion }}</td>
                            <td>{{ $c->proyecto->Nombre_Proyecto ?? $c->proyecto->Nombre }}</td>
                            <td>{{ $c->dosificacion->Nombre }}</td>
                            <td>{{ $c->fecha }}</td>
                            <td>{{ $c->cemento_sacos }}</td>
                            <td>{{ $c->Cemento_total }}</td>
                            <td>{{ $c->Arena_kg }}</td>
                            <td>{{ $c->Piedrin_kg }}</td>
                            <td>{{ $c->Aditivo }}</td>
                            <td>{{ $c->Piedrin_salida }}</td>
                            <td>{{ $c->Arena_salida }}</td>
                            <td>{{ $c->Placa }}</td>
                            <td>{{ $c->Coductor }}</td>
                            <td>{{ $c->viajes }}</td>
                            <td>{{ $c->Fecha_creacion }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center">No hay registros aún</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.getElementById('btnPreview').addEventListener('click', function() {
    const proyecto = document.getElementById('id_Proyecto').value;
    if (!proyecto) {
        alert('Seleccione un proyecto primero.');
        return;
    }

    fetch("{{ route('control_produccion.siguiente_vale') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({ id_Proyecto: proyecto })
    })
    .then(r => r.json())
    .then(resp => {
        if (!resp.ok) {
            alert(resp.message || 'No hay vales disponibles.');
            document.getElementById('previewCard').classList.add('d-none');
            return;
        }

        const v = resp.vale;
        document.getElementById('pv_id').innerText = v.id;
        document.getElementById('pv_placa').innerText = v.placa || '—';
        document.getElementById('pv_conductor').innerText = v.conductor || '—';
        document.getElementById('pv_sacos').innerText = v.sacos_cemento;
        document.getElementById('pv_granel').innerText = v.cemento_granel_kg;
        document.getElementById('pv_arena').innerText = v.arena_kg;
        document.getElementById('pv_piedrin').innerText = v.piedrin_kg;
        document.getElementById('pv_aditivo').innerText = v.aditivo_total;

        document.getElementById('previewCard').classList.remove('d-none');
    })
    .catch(err => {
        console.error(err);
        alert('Error al obtener el siguiente vale.');
    });
});
</script>
@endsection
