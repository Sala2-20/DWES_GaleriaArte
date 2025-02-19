@extends("layouts.plantilla")
@section("title", $info['nombre'])
@section('script')
    <script>
        @if (isset($mod) && $mod === '1' && $nivel > 0)

            document.getElementById("imagen").addEventListener("change", function (event) {
                const file = event.target.files[0]; // Obtenemos el archivo
                if (file) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const preview = document.getElementById("preview");
                        preview.src = e.target.result; // Asignamos la imagen cargada
                        preview.style.display = "block"; // Mostramos la imagen
                    };

                    reader.readAsDataURL(file); // Leemos la imagen como URL
                }
            });
        @endif
        @if ($nivel > 0 && $errors->has('error'))
            window.onload = iniciar;
            function iniciar() {
                alert("Error al modificar la obra " + '{{ $errors->first('error') }}');
            }
        @endif
    </script>
@endsection
@section("content")
    <div id="pintor" class="container py-5">
        @if (isset($mod) && $mod === '1' && $nivel > 0)
            <div class="card">
                <form action="{{route('obra.update', explode('=', explode('&', explode('?', url()->full())[1])[0])[1])}}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <br>
                    <img id="preview" src="@if ($info['imagen']){{ $info['imagen'] }}@endif" alt="Vista previa de la imagen"
                        class="imagenPintor" style="max-width: 300px;"><br>
                    <label for="imagen">Selecciona un archivo:</label>
                    <input type="file" name="imagen" accept="image/*" id="imagen">
                    <br>
                    Nombre de la obra:
                    <input type="text" name="nombre" id="nombre" value="{{ $info['nombre'] }}" required>
                    <br>
                    Descripcion:
                    <input type="text" name="descripcion" id="descripcion" value="{{ $info['descripcion'] }}">
                    <br>
                    <select name="id_pintor" id="id_estilo" required>
                        <option value="">Elige un pintor</option>
                        @foreach ($pintores as $pintor)
                            <option value="{{$pintor['id']}}">{{$pintor['nombre']}}</option>
                        @endforeach
                    </select>
                    <br>
                    Año:
                    <input type="date" name="anio" id="anio" value="{{ intval($info['anio']) }}-01-01" required>
                    <br>
                    Tamaño:
                    <input type="number" name="ancho" id="ancho" value="{{ intval(explode('x', $info['tamanio'])[0]) }}"
                        required>
                    <input type="number" name="alto" id="alto" value="{{ intval(explode('x', $info['tamanio'])[1]) }}" required>
                    <br>
                    <select name="tematica" id="id_estilo" required>
                        <option value="">Elige un estilo</option>
                        @foreach ($estilos as $estilo)
                            <option value="{{$estilo['id']}}">{{$estilo['nombre']}}</option>
                        @endforeach
                    </select>
                    <br>
                    <input type="submit" value="Crear obra" class="boton">
                </form>
            </div>
        @else
            <div class="card">
                @if (isset($info['imagen']))
                    <img src="{{ $info['imagen'] }}" alt="{{ $info['nombre'] }}" class="imagen img-thumbnail align-self-center" />
                @else
                    <p>Imagen no disponible</p>
                @endif
                <p class="h1 nombre">{{ $info['nombre'] }}</p>
                <p class="mx-3">{{ $info['descripcion'] }}</p>
                <p class="h4 mx-3"><a href="/pintores/obras?id={{ $info['id_pintor'] }}" class="link link-dark link-underline-opacity-0 link-underline-opacity-50-hover">{{ $info['pintor'] }}</a>, {{ $info['anio'] }}</p>
                <h5 class="mx-3">{{ $info['tamanio'] }}, con la tematica: {{ $info['estilo'] }}</h5>

            </div>
        @endif
    </div>
@endsection
