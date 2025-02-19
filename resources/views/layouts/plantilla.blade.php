@extends('layouts.plantillaInicio')
@section('cssInicio')
    <link rel="stylesheet" href="{{ asset('css/basico.css')}}">
@endsection
@section("scriptInicio")
    <script>
        @auth
            @if(Auth::user()->nivel > 0)
                window.onload = function () {
                    formulario();
                    @if($errors->has('error'))
                        let error = ' ' + {{ $errors->first('error') }};
                        alert("Error al crear el estilo:" + error);
                    @endif
                    @if (Session::has('correcto'))
                        alert("Error al crear el estilo:" + 'Session::get("correcto")');
                    @endif
                }
            @endif
        @endauth
        document.getElementById("cabecera").addEventListener("click", function (event) {
            if (event.target.tagName === "H3") {
                window.location.href = event.target.id;
            }
        });
        function formulario() {
            let img = document.getElementById("mostrar");
            let form = document.getElementById("nuevaInserccion");
            img.addEventListener("click", () => {
                if (form.style.display === "none") {
                    img.src = "{{asset(path: 'images/f-arriba.png')}}";
                    form.style.display = "block";
                } else {
                    img.src = "{{asset(path: 'images/f-abajo.png')}}";
                    form.style.display = "none";
                }
            });
        }
    </script>
    @yield('script')
@endsection
@section("contentInicio")
    <header id="cabecera">
        <h3 id="/pintores">Pintores</h3>
        <h3 id="/obras">Obras</h3>
        <h3 id="/estilos">Estilos</h3>
        <h3 id="/ajustes" style="align-self: self-end;">Ajustes</h3>
    </header>
    @yield('content')

@endsection
