@extends('layouts.plantilla')
@section('title', 'Ajustes')
@section('script')
<script>
    let realizado = {
        modificar: false,
        cerrar: false,
        crear: false,
        borrar: false,
    };
    let contenido = {
        modificar: '<form action="{{route('log.update', Auth::user()->id)}}" method="POST" id="inicio" ">@csrf @method('PUT') Nombre:<br><input type="text" name="nombre" id="nombreInicio" value="{{ Auth::user()->nombre }}"><br><br>Correo electrónico:<br><input type="email" name="correo" id="correoInicio" value="{{ Auth::user()->correo }}"><br><br><input type="submit" value="Modificar" class="btn btn-light m-3"></form>',
        borrar: '<form action="{{route('log.destroy', Auth::user()->id)}}" method="POST">@csrf @method('DELETE')<input type="submit" value="¿Estas seguro de que quieres borrar la cuenta?" class="btn btn-danger m-3"></form>',
        crear: '<br><form action="{{route('log.create')}}" method="POST" id="registro"">@csrf Nombre:<br><input type="text" name="nombre" id="nombre"><br><br>Correo electrónico:<br><input type="email" name="correo" id="correo"><br><br>Repetir correo electrónico:<br><input type="email" name="correoR" id="correoR"><br><h5 id="mensajeEmail"></h5><br>Contraseña:<br><input type="password" name="contrasenia" id="contrasenia"><br><br>Repetir contraseña:<br><input type="password" name="contraseniaR" id="contraseniaR"><br><h5 id="mensajeCont"></h5><br><select name="nivel" id="nivel"><option value="2">SuperAdministrador</option><option value="1">Administrador</option><option value="0">Usuario</option></select><br><input type="submit" value="REGISTRARSE" class="btn btn-light m-3"></form><br>',
        cerrar: '<form action="{{ route('log.cerrar') }}" method="post">@csrf <input type="submit" value="¿Estas seguro?" class="btn btn-warning m-3"></form>',
    };
    let volver ={
        modificar: '<button id="modificar">Modificar Datos</button>',
        cerrar: '<button>Cerrar Sesion</button>',
        crear: '<button>Crear Usuario</button>',
        borrar: '<button style="color:red">Borrar Datos(Eliminar Cuenta)</button>',
    };
</script>
<script src="js/ajustes.js"></script>
@endsection

@section('content')
<div class="d-flex flex-row">
    <img src="/images/0000.png" alt="Icono usuario" class=" img-fluid rounded" width="10%">
    <h1>{{ Auth::user()->nombre }} </h1>
</div>
<div class="d-flex flex-column align-items-center">
    <div class="button"  id="modificar">
        <button class="btn btn-primary m-3">Modificar Datos</button>
    </div>
    <div class="button" id="cerrar">
        <button class="btn btn-warning m-3">Cerrar Sesion</button>
    </div>
    <div class="button" id="borrar">
        <button class="btn btn-danger m-3">Borrar Datos(Eliminar Cuenta)</button>
    </div>
</div>
<div class="d-flex flex-column align-items-center">
    @auth
        @if(Auth::user()->nivel > 0)
            <p><strong>Opciones de administrador:</strong></p>
            <div class="button" id="crear">
                <button class="btn btn-primary">Crear Usuario</button>
            </div>
            <div class="button" id="mostrar">
                <button class="btn btn-primary mt-3">Mostrar Usuarios</button>
            </div>
        @endif
    @endauth
</div>
@endsection
