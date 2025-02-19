@extends("layouts.plantilla")

@section("title", "Obras")

@section('script')
<script>
    function redirigir(id, modificar = false) {
        @if ($nivel > 0)
            const url = modificar
                ? `/obras/obra?modificar=1&id=${id}`
                : `/obras/obra?id=${id}`;
            window.location.href = url;
        @else
            window.location.href = `/obras/obra?id=${id}`;
        @endif
    }

    @if($nivel > 0)
    async function eliminar(id) {
        if (!confirm("¿Estás seguro de que deseas eliminar esta obra?")) return;

        try {
            const response = await fetch("{{ route('obra.destroy', '') }}/" + id, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ id: id })
            });

            if (!response.ok) {
                const data = await response.json();
                throw new Error(data.message || "Error al eliminar.");
            }

            alert("Obra eliminada correctamente.");
            location.reload();
        } catch (error) {
            alert(error.message);
        }
    }
    @endif

    @if($errors->has('error'))
    window.onload = function() {
        let error = ' ' + {{ $errors->first('error') }};
        alert("Error al crear la obra:" + error );
    };
    @endif

    document.addEventListener("DOMContentLoaded", function() {
        const imagenInput = document.getElementById("imagen");
        if (imagenInput) {
            imagenInput.addEventListener("change", function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById("preview");
                        preview.src = e.target.result;
                        preview.style.display = "block";
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });

</script>
@endsection

@section("content")
@if ($nivel > 0)
<div class="d-flex justify-content-center align-content-center flex-column">
    <form action="{{route('obra.create')}}" method="POST" enctype="multipart/form-data" id="nuevaInserccion" style="display: none;">
        @csrf
        <label>Nombre de la obra:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" id="descripcion">


        <label for="imagen">Selecciona una imagen:</label>
        <input type="file" name="imagen" accept="image/*" id="imagen" required>
        <img id="preview" src="" alt="Vista previa de la imagen" style="max-width: 300px; display: none;">

        <label>Año:</label>
        <input type="date" name="anio" id="anio" required>

        <label>Tamaño:</label>
        <input type="number" name="ancho" id="ancho" required placeholder="Ancho">
        <input type="number" name="alto" id="alto" required placeholder="Alto">
        <br>
        <select name="id_pintor" id="id_pintor" required>
            <option value="">Elige un pintor</option>
            @foreach ($pintores as $pintor)
                <option value="{{$pintor['id']}}">{{$pintor['nombre']}}</option>
            @endforeach
        </select>
        <select name="id_estilo" id="id_estilo" required>
            <option value="">Elige un estilo</option>
            @foreach ($estilos as $estilo)
                <option value="{{$estilo['id']}}">{{$estilo['nombre']}}</option>
            @endforeach
        </select>

        <input type="submit" value="Crear obra" class="boton">
    </form>
    <img src="{{asset(path: 'images/f-abajo.png')}}" alt="Aparecer formulario" style="width: 60px; height: 60px;margin-left: auto;margin-right: auto;" id="mostrar">
</div>
@endif
<div class="p-5" id="contenedores">
@foreach ($obras as $obra)
    <div class=" bg-dark text-white p-3 m-3 rounded" style="display: inline-block; width: auto;" onclick="redirigir({{ $obra['id'] }})">
        @if (isset($obra['imagen']) && isset($obra['tipo']))
                    <img src="data:image/{{ $obra['tipo'] }}; base64,{{ base64_encode($obra['imagen']) }}" alt="{{ $obra['nombre'] }}"
                        class="imagen" />
        @elseif (isset($obra['imagen']))
            <img src="{{ $obra['imagen'] }}" alt="{{ $obra['nombre'] }}" class="imagenPintor"  style="width: 200px; height: 200px;"/>
        @else
            <p>Imagen no disponible</p>
        @endif

        <h2 class="nombre">{{ $obra['nombre'] }}</h2>

        @if($nivel > 0)
            <button class="btn btn-primary" onclick="event.stopPropagation(); redirigir({{ $obra['id'] }}, true)">Modificar</button>
            <button class="btn btn-danger" onclick="event.stopPropagation(); eliminar({{ $obra['id'] }})">Eliminar</button>
        @endif
    </div>
@endforeach
</div>
@endsection
