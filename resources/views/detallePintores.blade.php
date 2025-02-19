@extends("layouts.plantilla")
@section("title", $infoPintor['nombre'])
@section('script')
    <script>
        function redirigir(id) {
            const url = `/pintores/obras/obra?id=${id}`;
            window.location.href = url;
        }
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
            <form action=" {{ route('pintor.update', explode('=', explode('&', explode('?', url()->full())[1])[0])[1]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="d-flex">
                    <div>
                        <img src="{{ $infoPintor['imagen'] }}" alt="imagen del pintor" class="img-fluid" style="width: auto;"
                            id="preview" /> <br>
                        <input type="file" name="imagen" accept="image/*" id="imagen">
                    </div>
                    <div>
                        <input type="text" name="nombre" id="nombre" value="{{ $infoPintor['nombre'] }}"
                            style="font-size: xx-large"> <br>
                        <textarea name="descripcion" id="descripcion"
                            maxlength="1000">{{ $infoPintor['descripcion'] }}</textarea><br>

                        <label for="nacimiento">Fecha de nacimiento:</label> <br>
                        <input type="date" name="nacimiento" id="nacimiento" value="{{ $infoPintor['nacimiento']}}"> <br>
                        <label for="nacimiento">Fecha de fallecimiento:</label> <br>
                        <input type="date" name="fallecimiento" id="fallecimiento" value="{{ $infoPintor['fallecimiento']}}">
                        <br>
                        <label for="nacionalidad">Nacionalidad:</label> <br>
                        <input type="text" name="nacionalidad" id="nacionalidad" value="{{ $infoPintor['nacionalidad'] }}"> <br>
                        <select name="id_estilo" id="id_estilo" required>
                            <option value="">Elige un estilo</option>
                            @foreach ($estilos as $estilo)
                                <option value="{{$estilo['id']}}">{{$estilo['nombre']}}</option>
                            @endforeach
                        </select>
                        <input type="submit" value="Editar pintor" class="boton">
                    </div>
                </div>
            </form>
        @else
            <div class="card">
                @if (isset($infoPintor['imagen']) && isset($infoPintor['tipo']))
                    <img src="data:image/{{ $infoPintor['tipo'] }}; base64,{{ base64_encode($infoPintor['imagen']) }}" alt="{{ $infoPintor['nombre'] }}"
                        class="imagen" />
                @elseif (isset($infoPintor['imagen']))
                    <img src="{{ $infoPintor['imagen'] }}" alt="{{ $infoPintor['nombre'] }}" class="imagenPintor" />
                @else
                    <p>Imagen no disponible</p>
                @endif
                <div>
                    <h1>{{ $infoPintor['nombre'] }}</h1>
                    <p class="mx-3">{{ $infoPintor['descripcion'] }}</p>
                    <p class="mx-3">Fecha de nacimiento: {{ $infoPintor['nacimiento']}} <br>
                        Fecha de fallecimiento: {{ $infoPintor['fallecimiento'] }}<br>
                        Nacionalidad: {{ $infoPintor['nacionalidad'] }} <br>
                        @if(is_array($estilos) && count($estilos) > 0)
                                Estilos: </p>
                            <ul>
                                @foreach ($estilos as $estilo)
                                    <li>{{$estilo['nombre']}}</li>
                                @endforeach
                            </ul>
                        @endif
                </div>
            </div>
        @endif
    </div>
    <div class="p-5" id="contenedores">
        @foreach ($obras as $obra)
            <div class="card" onclick="redirigir({{ $obra['id'] }})">
                @if (isset($obra['imagen']) && isset($obra['tipo']))
                    <img src="data:image/{{ $obra['tipo'] }}; base64,{{ base64_encode($obra['imagen']) }}" alt="{{ $obra['nombre'] }}"
                        class="imagen" />
                @elseif (isset($obra['imagen']))
                    <img src="{{ $obra['imagen'] }}" alt="{{ $obra['nombre'] }}" class="imagenPintor" />
                @else
                    <p>Imagen no disponible</p>
                @endif
                <h2 class="nombre text-black">{{ $obra['nombre'] }}</h2>
            </div>
        @endforeach

    </div>
@endsection
