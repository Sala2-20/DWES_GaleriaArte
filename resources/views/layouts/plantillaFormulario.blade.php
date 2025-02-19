@extends("plantillaAjustes")
@section("title", "Formulario")
@section("content")
<div class="formulario" >
    <form action="@yield("enviar")" method="post">
        @yield("inputs")
        <input type="submit" value="Enviar">
    </form>
</div>

@endsection()
