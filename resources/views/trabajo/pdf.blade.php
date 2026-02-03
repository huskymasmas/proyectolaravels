<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Trabajos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .table-group { background-color: #ddd; font-weight: bold; text-align: center; }
        .subtotal { font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h2 class="text-center">Listado de Trabajos</h2>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Número Face</th>
                <th>Nombre Face</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Costo Q/Unidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $grupo = ''; $totalGrupo = 0; @endphp
            @foreach($trabajos as $i => $t)
                {{-- Encabezado por Estado de Trabajo --}}
                @if($grupo != ($t->estadoTrabajo->Nombre ?? ''))
                    @if($i != 0)
                        <tr class="subtotal">
                            <td colspan="6" class="text-end">Subtotal {{ strtoupper($grupo) }}</td>
                            <td class="text-end">Q{{ number_format($totalGrupo, 2) }}</td>
                        </tr>
                        @php $totalGrupo = 0; @endphp
                    @endif
                    @php $grupo = $t->estadoTrabajo->Nombre; @endphp
                    <tr class="table-group">
                        <td colspan="7">{{ strtoupper($grupo) }}</td>
                    </tr>
                @endif

                {{-- Fila de trabajo --}}
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $t->Numero_face }}</td>
                    <td>{{ $t->Nombre_face }}</td>
                    <td class="text-end">{{ number_format($t->Cantidad, 2) }}</td>
                    <td>{{ $t->unidad->Nombre ?? '' }}</td>
                    <td class="text-end">Q{{ number_format($t->CostoQ ?? 0, 2) }}</td>
                    <td class="text-end">Q{{ number_format($t->Subtotal ?? 0, 2) }}</td>
                </tr>
                @php $totalGrupo += $t->Subtotal; @endphp
            @endforeach

            {{-- Total del último grupo --}}
            @if(count($trabajos) > 0)
                <tr class="subtotal">
                    <td colspan="6" class="text-end">Subtotal {{ strtoupper($grupo) }}</td>
                    <td class="text-end">Q{{ number_format($totalGrupo, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
