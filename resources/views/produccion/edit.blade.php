<h1>Editar Producción</h1>

<form action="{{ route('produccion.update', $produccion->id_Produccion) }}" method="POST">
    @csrf
    @method('PUT')

    Volumen (m3): <input type="text" name="Volumen_m3" value="{{ $produccion->Volumen_m3 }}"><br>
    Cemento (kg): <input type="text" name="Cemento_kg" value="{{ $produccion->Cemento_kg }}"><br>
    Arena (m3): <input type="text" name="Arena_m3" value="{{ $produccion->Arena_m3 }}"><br>
    Piedrin (m3): <input type="text" name="Piedrin_m3" value="{{ $produccion->Piedrin_m3 }}"><br>
    Aditivo (l): <input type="text" name="Aditivo_l" value="{{ $produccion->Aditivo_l }}"><br>

    <button type="submit">Actualizar</button>
</form>
