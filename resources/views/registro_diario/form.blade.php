@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Nuevo Registro Diario</h2>

    <form action="{{ route('registro_diario.store') }}" method="POST" id="formRegistro">
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Empleado:</label>
                <select name="id_Empleados" id="id_Empleados" class="form-control" required>
                    <option value="">-- Seleccione --</option>
                    @foreach($empleados as $emp)
                        <option value="{{ $emp->id_Empleados }}">{{ $emp->Codigo_empleado }} - {{ $emp->Nombres }} {{ $emp->Apellido }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Fecha:</label>
                <input type="date" name="fecha_dia" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Trabajo:</label>
                <select name="Trabajo" id="Trabajo" class="form-control" required>
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Código:</label>
                <input type="text" id="Codigo_empleado" class="form-control" >
            </div>
            <div class="col-md-3">
                <label>Nombre:</label>
                <input type="text" id="Nombre_empleado" class="form-control" >
            </div>
            <div class="col-md-3">
                <label>Puesto:</label>
                <input type="text" id="Puesto" class="form-control" >
            </div>
            <div class="col-md-3">
                <label>Viáticos (Q):</label>
                <input type="number" id="Viaticos" class="form-control" >
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Días Viáticos:</label>
                <input type="number" step="1" name="dias_viaticos" id="dias_viaticos" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Adelanto Viáticos (Q):</label>
                <input type="number" step="0.01" name="Adelanto_viatico" id="Adelanto_viatico" class="form-control" >
            </div>
            <div class="col-md-2">
                <label>Horas Extras:</label>
                <input type="number" step="1" name="Horas_extras" id="Horas_extras" class="form-control">
            </div>
            <div class="col-md-2">
                <label>Adelantos (Q):</label>
                <input type="number" step="0.01" name="Adelantos" id="Adelantos" class="form-control">
            </div>
            <div class="col-md-2">
                <label>Total Día (Q):</label>
                <input type="number" step="0.01" name="Total_dia" id="Total_dia" class="form-control" >
            </div>
        </div>

        <button class="btn btn-success mt-3">💾 Guardar</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // Al seleccionar un empleado
    $('#id_Empleados').change(function() {
        var id = $(this).val();
        if (id) {
            $.get('/registro_diario/empleado/' + id, function(data) {
                $('#Codigo_empleado').val(data.codigo);
                $('#Nombre_empleado').val(data.nombre);
                $('#Puesto').val(data.puesto);
                $('#Viaticos').val(data.viaticos);
            });
        }
    });

    // Calcular Adelanto Viático y Total Día
    $('#dias_viaticos, #Horas_extras, #Adelantos, #Trabajo').on('input change', function() {
        var viaticos = parseFloat($('#Viaticos').val()) || 0;
        var dias = parseInt($('#dias_viaticos').val()) || 0;
        var adelantos = parseFloat($('#Adelantos').val()) || 0;
        var horas = parseInt($('#Horas_extras').val()) || 0;
        var trabajo = $('#Trabajo').val();
        var sueldo = parseFloat($('#Viaticos').data('sueldo')) || 0;

        var adelanto_viatico = dias * viaticos;
        $('#Adelanto_viatico').val(adelanto_viatico.toFixed(2));

        var total = 0;
        if (trabajo === "SI") {
            total = sueldo + viaticos + (horas * 25) - adelantos - adelanto_viatico;
        }
        $('#Total_dia').val(total.toFixed(2));
    });
});
</script>
@endsection
