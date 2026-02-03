@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded">
        <div class="card-header bg-primary text-white text-center">
            <h4>Crear Nómina</h4>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('reportes.nomina.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <label>Sueldo Base</label>
                        <input type="number" step="0.01" name="sueldo_Base" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Costo Horas Extras</label>
                        <input type="number" step="0.01" name="Costo_horas_extras" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Viáticos</label>
                        <input type="number" step="0.01" name="viaticosnomina" class="form-control">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Bonos</label>
                        <input type="number" step="0.01" name="Bonos" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label>Bonos Adicionales</label>
                        <input type="number" step="0.01" name="Bonos_adicional" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label>Estado</label>
                        <select name="Estado" class="form-control" required>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>

        
                

                <button class="btn btn-success w-100 mt-4">Guardar Nómina Completa</button>

            </form>

        </div>
    </div>
</div>
@endsection
