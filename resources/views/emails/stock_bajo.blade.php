<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Alerta de Stock Bajo</title>
</head>
<body>
    <h2 style="color: red;">Alerta: Productos con Stock Bajo</h2>
    <p>Se detectaron los siguientes productos con cantidad menor a 9:</p>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $item)
                <tr>
                    <td>{{ $item->Nombre }}</td>
                    <td>{{ $item->Descripcion }}</td>
                    <td>{{ $item->Cantidad }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
