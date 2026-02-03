@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded">
        <div class="card-header bg-info text-white text-center">
            <h4>Agregar Detalle de Nómina</h4>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('reportes.nomina.detalle.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-12">
                        <label>Empleado</label>
                        <select name="id_Empleados" class="form-control" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($empleados as $item)
                                <option value="{{ $item->id_Empleados }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mt-3">

                    <div class="col-md-4">
                        <label>Horas Extras</label>
                        <input type="number" name="Horas_extras" class="form-control" min="0" required>
                    </div>

                    <div class="col-md-4">
                        <label>Días Trabajados</label>
                        <input type="number" name="cantidad_dias" class="form-control" min="0" required>
                    </div>

           
                </div>

                <button class="btn btn-primary w-100 mt-4">Guardar Detalle</button>

            </form>

        </div>
    </div>
</div>
@endsection
