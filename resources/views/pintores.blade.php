@extends("layouts.plantilla")

@section("title", "Pintores")

@section('script')
    <script>
        function redirigir(id, bool = false) {
            @if($nivel > 0)
                const url = bool
                    ? `/pintores/obras?modificar=1&id=${id}`
                    : `/pintores/obras?id=${id}`;
                window.location.href = url;
            @else
                window.location.href = `/pintores/obras?id=${id}`;
            @endif
        }

        @if($nivel > 0)
            async function eliminar(id) {
                if (!confirm("¿Estás seguro de que deseas eliminar este pintor?")) return;

                try {
                    const response = await fetch("{{ route('pintor.destroy', '') }}/" + id, {
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

                    alert("Pintor eliminado correctamente.");
                    location.reload();
                } catch (error) {
                    alert(error.message);
                }
            }
        @endif

        @if($errors->has('error'))
            window.onload = function () {
                let error = '{{ $errors->first('error') }}';
                alert("Error al crear pinto: " + error);
            };
        @endif

        document.getElementById("imagen").addEventListener("change", function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById("preview");
                    preview.src = e.target.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });
        function filtrarPintores(event) {
            event.preventDefault();

            let epoca = document.getElementById("epoca").value;
            let nacionalidad = document.getElementById("nacionalidad").value;
            let pintores = document.getElementById("pintores").children;

            Array.from(pintores).forEach(pintor => {
                let tieneEpoca = epoca === "" || pintor.id.contains(epoca);
                let tieneNacionalidad = nacionalidad === "" || pintor.id.contains(nacionalidad);

                // Mostrar solo si cumple al menos un criterio o si ninguno está seleccionado
                if (tieneEpoca || tieneNacionalidad) {
                    pintor.style.display = "block";
                } else {
                    pintor.style.display = "none";
                }
            });
        }
    </script>
@endsection

@section("content")
    @if ($nivel > 0)
    <div class="d-flex justify-content-center align-content-center flex-column">
        <form action="{{route('pintor.create')}}" method="POST" enctype="multipart/form-data" id="nuevaInserccion" style="display: none;">
            @csrf
            <label>Nombre del pintor:</label>
            <input type="text" name="nombre" id="nombre">

            <label>Descripción:</label>
            <input type="text" name="descripcion" id="descripcion">

            <label>Nacionalidad:</label>
            <input type="text" name="nacionalidad" id="nacionalidad">

            <label for="imagen">Selecciona un archivo:</label>
            <input type="file" name="imagen" accept="image/*" id="imagen" required>
            <img id="preview" src="" alt="Vista previa de la imagen" style="max-width: 300px; display: none;">

            <label>Nacimiento:</label>
            <input type="date" name="nacimiento" id="nacimiento">

            <label>Fallecimiento:</label>
            <input type="date" name="fallecimiento" id="fallecimiento">

            <input type="submit" value="Crear pintor">
        </form>
        <img src="{{asset(path: 'images/f-abajo.png')}}" alt="Aparecer formulario" style="width: 60px; height: 60px;margin-left: auto;margin-right: auto;" id="mostrar">
    </div>
    @endif
    <div class="d-flex justify-content-center align-content-center">
        <form action="" class="d-flex justify-content-center align-content-center">
            <select name="epoca" id="epoca">
                <option value="">Elige una época</option>
                @foreach ($filtrar as $id)
                    @if(isset($id['epoca']))
                        <option value="{{$id['epoca']}}">{{$id['epoca']}}</option>
                    @endif
                @endforeach
            </select>

            <select name="nacionalidad" id="nacionalidad">
                <option value="">Elige una nacionalidad</option>
                @foreach ($filtrar as $id)
                    @if(isset($id['nacionalidad']))
                        <option value="{{$id['nacionalidad']}}">{{$id['nacionalidad']}}</option>
                    @endif
                @endforeach
            </select>

            <input type="submit" value="Buscar" class="btn btn-dark" onclick="filtrarPintores(event)">
        </form>
    </div>
    <div class="p-5" id="contenedores">
        @foreach ($pintores as $pintor)
            <div class="bg-dark text-white p-3 m-3 rounded" style="display: inline-block; width: auto;"
                id="{{ $pintor['epoca'] }} {{ $pintor['nacionalidad'] }}"
                onclick="redirigir({{ $pintor['id'] }})">
                @if (isset($pintor['imagen']))
                    <img src="{{ $pintor['imagen'] }}" alt="{{ $pintor['nombre'] }}" class="imagenPintor img-fluid"
                        style="width: 200px; height: 200px;">
                @else
                    <p>Imagen no disponible</p>
                @endif

                <h2 class="nombre">{{ $pintor['nombre'] }}</h2>

                @if($nivel > 0)
                    <button class="btn btn-primary"
                        onclick="event.stopPropagation(); redirigir({{ $pintor['id'] }}, true)">Modificar</button>
                    <button class="btn btn-danger"
                        onclick="event.stopPropagation(); eliminar({{ $pintor['id'] }})">Eliminar</button>
                @endif
            </div>
        @endforeach
    </div>
@endsection
