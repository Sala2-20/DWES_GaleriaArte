@extends("layouts.plantilla")
@section("title", $infoEstilo['nombre'])
@section('script')
    <script>
        @if ($nivel > 0 && $errors->has('error'))
            window.onload = iniciar;
            function iniciar() {
                alert("Error al modificar la obra " + '{{ $errors->first('error') }}');
            }
        @endif
        function redirigir(id) {
            @if ($vista)
                window.location.href = `/pintores/obras?id=${id}`;
            @else
                window.location.href = `/obras/obra?id=${id}`;
            @endif
        }
    </script>
@endsection
@section("content")
    <div id="pintor" class="container">
        @if (isset($mod) && $mod === '1' && $nivel > 0)
            <div class="card">
                <form action="{{route('obra.update', explode('=', explode('&', explode('?', url()->full())[1])[0])[1])}}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <label>Nombre del estilo:</label>
                    <input type="text" name="nombre" id="nombre" value="{{ $infoEstilo['nombre'] }}">

                    <label>Caracteristicas:</label>
                    <input type="text" name="caracteristicas" id="caracteristicas" value="{{ $infoEstilo['caracteristicas'] }}">

                    <label>Epoca del estilo:</label>
                    <input type="date" name="epoca_inicio" id="epoca_inicio" value="{{ $infoEstilo['epoca_inicio'] }}">
                    <input type="date" name="epoca_final" id="epoca_final" value="{{ $infoEstilo['epoca_final'] }}">
                    <select name="id_estilo" id="id_estilo" required>
                        <option value="">Elige un pintor</option>
                        @foreach ($pintores as $pintor)
                            <option value="{{$pintor['id']}}">{{$pintor['nombre']}}</option>
                        @endforeach
                    </select>
                    <input type="submit" value="Crear estilo">
                </form>
            </div>
        @else
            <div class="card container mt-5">
                <div>
                    <h1>{{ $infoEstilo['nombre'] }}</h1>
                    <p class="mx-3">{{ $infoEstilo['caracteristicas'] }}</p>
                    <p class="mx-3">{{ explode('-',$infoEstilo['epoca_inicio'])[0] }} - {{ explode('-',$infoEstilo['epoca_final'])[0] }}</p>
                    @if(is_array($pintores) && count($pintores) > 0)
                        <p class="mx-3">Pintores que han participado a este estilo:</p>
                        <ul class="mx-3">
                            @foreach ($pintores as $pintor)
                                <li class="mx-3">{{$pintor['nombre']}}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endif
    </div>
    <div class="p-5" id="contenedores">
        @foreach ($info as $obj)
            <div class="bg-dark text-white p-3 m-3 rounded" style="display: inline-block; width: auto;"
                onclick="redirigir({{ $obj['id'] }})">
                @if (isset($obj['imagen']) && isset($obj['tipo']))
                    <img src="data:image/{{ $obj['tipo'] }}; base64,{{ base64_encode($obj['imagen']) }}" alt="{{ $obj['nombre'] }}"
                        class="imagen" />
                @elseif (isset($obj['imagen']))
                    <img src="{{ $obj['imagen'] }}" alt="{{ $obj['nombre'] }}" class="imagenPintor" />
                @else
                    <p>Imagen no disponible</p>
                @endif
                <h2 class="text-white">{{ $obj['nombre'] }}</h2>
            </div>
        @endforeach
    </div>
@endsection
