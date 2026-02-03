@extends('layouts.app')

@section('content')
<div class="container">

    <h2>Reporte de Nómina</h2>

    <!-- Filtro por empleado -->
    <form method="GET" class="mb-3 row">
        <div class="col-md-4">
            <label>Filtrar por empleado:</label>
            <select name="empleado" class="form-select">
                <option value="">-- TODOS --</option>
                @foreach($empleados as $emp)
                    <option value="{{ $emp->id_Empleados }}" 
                        {{ (isset($empleado) && $empleado == $emp->id_Empleados) ? 'selected' : '' }}>
                        {{ $emp->nombre }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="col-md-3 mt-4">
            <button class="btn btn-primary w-100 mt-1">Buscar</button>
        </div>
        <div class="row mb-4">

    <div class="col-md-6">
    <br>
        <a href="{{ route('reportes.nomina.create') }}" 
           class="btn btn-primary btn-lg w-100 shadow-sm">
            <i class="fa fa-plus-circle"></i> Crear Nómina
        </a>
    </div>
    <br>

    <div class="col-md-6">
    <br>
        <a href="{{ route('reportes.nomina.detalle.create') }}" 
           class="btn btn-success btn-lg w-100 shadow-sm">
            <i class="fa fa-file-invoice-dollar"></i> Crear Detalle de Nómina
        </a>
    </div>

</div>
    </form>

    <!-- Tabla 1: Resumen de nómina -->
    <h4>Resumen de Nómina</h4>
    @php
        $total_dev = 0;
        $total_saldo = 0;
        $sum_adelanto_total = 0;
        $sum_adelanto_viaticos = 0;
        $sum_total_pagos = 0;
    @endphp

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Días Trabajados</th>
                <th>Horas Extras</th>
                <th>Adelantos (Q)</th>
                <th>Adelanto Viáticos (Q)</th>
                <th>Pagos Parciales (Q)</th>
                <th>Total Devengado (Q)</th>
                <th>Saldo Pendiente (Q)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportes as $rep)
            @php
                // usar ?? 0 por si la propiedad viene nula
                $adelanto = $rep->Adelanto_total ?? 0;
                $adelanto_viat = $rep->Adelanto_viaticos ?? 0;
                $pagos_parc = $rep->Total_Pagos ?? 0;
                $dev = $rep->total_devengado ?? 0;
                $saldo = $rep->saldo_pendiente ?? 0;

                $sum_adelanto_total += $adelanto;
                $sum_adelanto_viaticos += $adelanto_viat;
                $sum_total_pagos += $pagos_parc;
                $total_dev += $dev;
                $total_saldo += $saldo;
            @endphp

            <tr>
                <td>{{ $rep->Codigo ?? '' }}</td>
                <td>{{ $rep->Nombre ?? '' }}</td>
                <td>{{ $rep->rol ?? '' }}</td>
                <td class="text-center">{{ $rep->Dias_trabajados ?? 0 }}</td>
                <td class="text-center">{{ $rep->Total_horas_extras ?? 0 }}</td>
                <td class="text-end">Q {{ number_format($adelanto, 2) }}</td>
                <td class="text-end">Q {{ number_format($adelanto_viat, 2) }}</td>
                <td class="text-end">Q {{ number_format($pagos_parc, 2) }}</td>
                <td class="text-end">Q {{ number_format($dev, 2) }}</td>
                <td class="text-end">Q {{ number_format($saldo, 2) }}</td>
            </tr>
            @endforeach
        </tbody>

        <!-- TOTAL GENERAL -->
        <tfoot class="table-secondary fw-bold">
            <tr>
                <td colspan="4" class="text-end">TOTAL GENERAL:</td>
                <td class="text-center">{{ /* espacio para Horas Extras total si quieres */ '' }}</td>
                <td class="text-end">Q {{ number_format($sum_adelanto_total, 2) }}</td>
                <td class="text-end">Q {{ number_format($sum_adelanto_viaticos, 2) }}</td>
                <td class="text-end">Q {{ number_format($sum_total_pagos, 2) }}</td>
                <td class="text-end">Q {{ number_format($total_dev, 2) }}</td>
                <td class="text-end">Q {{ number_format($total_saldo, 2) }}</td>
            </tr>
        </tfoot>

    </table>

    <!-- Tabla 2: Detalle por empleado -->
    <h4>Detalle por Empleado</h4>

    @php
        $total_detalle = 0;
    @endphp

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Nombre Completo</th>
                <th>Sueldo Base (Q)</th>
                <th>Viáticos (Q)</th>
                <th>Estado Trabajo</th>
                <th>Horas Extras</th>
                <th>Costo HE (Q)</th>
                <th>Días</th>
                <th>Total a Pagar (Q)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $d)
            @php
                $total_row = $d->total_a_pagar ?? 0;
                $total_detalle += $total_row;
            @endphp
            <tr>
                <td>{{ $d->Codigo_empleado ?? '' }}</td>
                <td>{{ ($d->Nombres ?? '') . ' ' . ($d->Apellido ?? '') . ' ' . ($d->Apellido2 ?? '') }}</td>
                <td class="text-end">Q {{ number_format($d->sueldo_base ?? 0, 2) }}</td>
                <td class="text-end">Q {{ number_format($d->viaticosnomina ?? 0, 2) }}</td>
                <td class="text-center">{{ $d->Estado_trabajo ?? '' }}</td>
                <td class="text-center">{{ $d->Horas_extras ?? 0 }}</td>
                <td class="text-end">Q {{ number_format($d->Costo_horas_extras ?? 0, 2) }}</td>
                <td class="text-center">{{ $d->cantidad_dias ?? 0 }}</td>
                <td class="text-end">Q {{ number_format($total_row, 2) }}</td>
            </tr>
            @endforeach
        </tbody>

        <!-- TOTAL GENERAL -->
        <tfoot class="table-secondary fw-bold">
            <tr>
                <td colspan="8" class="text-end">TOTAL GENERAL:</td>
                <td class="text-end">Q {{ number_format($total_detalle, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- ===================================================== -->
<!-- TABLA 3: SOLO NÓMINAS -->
<!-- ===================================================== -->

<h4 class="mt-5">Listado de Nóminas</h4>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Código Empleado</th>
            <th>Nombre</th>
            <th>Sueldo Base</th>
            <th>Viáticos</th>
            <th>Costo HE</th>
            <th>Estado</th>
            <th>Fecha Creación</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @foreach($tablaNomina as $n)
        <tr>
            <td>{{ $n->id_Nomina }}</td>
            <td>{{ $n->Codigo ?? '—' }}</td>
            <td>{{ $n->Nombre ?? '—' }}</td>
            <td>Q {{ number_format($n->sueldo_Base, 2) }}</td>
            <td>Q {{ number_format($n->viaticosnomina, 2) }}</td>
            <td>Q {{ number_format($n->Costo_horas_extras, 2) }}</td>
            <td>{{ $n->Estado == 1 ? 'Activo' : 'Inactivo' }}</td>
            <td>{{ $n->Fecha_creacion }}</td>

            <td class="text-center">
                <a href="{{ route('reportes.nomina.edit', $n->id_Nomina) }}" 
                   class="btn btn-warning btn-sm">Editar</a>

                <form action="{{ route('reportes.nomina.destroy', $n->id_Nomina) }}" 
                      method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- ===================================================== -->
<!-- TABLA 4: SOLO DETALLE NOMINA -->
<!-- ===================================================== -->

<h4 class="mt-5">Listado de Detalle de Nómina</h4>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID Detalle</th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Horas Extras</th>
            <th>Días</th>
            <th>Total a Pagar</th>
            <th>Fecha Creación</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @foreach($tablaDetalle as $d)
        <tr>
            <td>{{ $d->id_detalle_nomina }}</td>
            <td>{{ $d->Codigo }}</td>
            <td>{{ $d->Nombre }}</td>
            <td>{{ $d->Horas_extras }}</td>
            <td>{{ $d->cantidad_dias }}</td>
            <td>Q {{ number_format($d->totla_A_pagar, 2) }}</td>
            <td>{{ $d->Fecha_creacion }}</td>

            <td>
                <a href="{{ route('reportes.nomina.detalle.edit', $d->id_detalle_nomina) }}" 
                   class="btn btn-warning btn-sm">Editar</a>

                <form action="{{ route('reportes.nomina.detalle.destroy', $d->id_detalle_nomina) }}" 
                      method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>




</div>
@endsection
