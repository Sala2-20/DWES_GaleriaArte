@extends("layouts.plantilla")

@section("title", "Estilos")

@section('script')
<script>
    let buscar = "pintor";
    function cambiarBuscar(event) {
        let id = event.target.id;
        let botones = document.querySelectorAll(".buscar button");
        botones.forEach(obj =>{
            if (obj.id === id) {
                obj.classList = "btn btn-dark";
                buscar = obj.id;
            }else{
                obj.classList = "btn btn-light";
            }
        })
    }
    function redirigir(id, bool = false) {
        @if($nivel > 0)
            const url = bool
                ? `/estilos/${buscar}?modificar=1&id=${id}`
                : `/estilos/${buscar}?id=${id}`;
            window.location.href = url;
        @else
        window.location.href = `/estilos/${buscar}?id=${id}`;
        @endif
    }

    @if($nivel > 0)
    async function eliminar(id) {
        if (!confirm("¿Estás seguro de que deseas eliminar este estilo?")) return;

        try {
            const response = await fetch("{{ route('estilo.destroy', '') }}/" + id, {
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

            alert("Estilo eliminado correctamente.");
            location.reload();
        } catch (error) {
            alert(error.message);
        }
    }
    @endif


    function filtrarEstilos(event) {
    event.preventDefault();

    let epoca = document.getElementById("epoca").value;
    let nacionalidad = document.getElementById("nacionalidad").value;
    let estilo = document.getElementById("estilos").children;

    Array.from(estilos).forEach(estilo => {
        let tieneEpoca = epoca === "" || estilo.id.contains(epoca);
        let tieneNacionalidad = nacionalidad === "" || estilo.id.contains(nacionalidad);

        // Mostrar solo si cumple al menos un criterio o si ninguno está seleccionado
        if (tieneEpoca || tieneNacionalidad) {
            estilo.style.display = "block";
        } else {
            estilo.style.display = "none";
        }
    });
}
</script>
@endsection

@section("content")
@if ($nivel > 0)
<div class="d-flex justify-content-center align-content-center flex-column">
    <form action="{{route('estilo.create')}}" method="POST" enctype="multipart/form-data" id="nuevaInserccion" style="display: none;">
        @csrf
        <label>Nombre del estilo:</label>
        <input type="text" name="nombre" id="nombre">

        <label>Caracteristicas:</label>
        <input type="text" name="caracteristicas" id="caracteristicas">

        <label>Epoca del estilo:</label>
        <input type="date" name="epoca_inicio" id="nacimiento">
        <input type="date" name="epoca_final" id="fallecimiento">

        <input type="submit" value="Crear estilo">
    </form>
    <img src="{{asset(path: 'images/f-abajo.png')}}" alt="Aparecer formulario" style="width: 60px; height: 60px;margin-left: auto;margin-right: auto;" id="mostrar">
</div>
@endif

<div class="buscar d-flex flex-row justify-content-center align-items-center">
    <button id="pintor" class="btn btn-dark" onclick="cambiarBuscar(event)">Pintores</button>
    <button id="obra" class="btn btn-light" onclick="cambiarBuscar(event)">Obras</button>
</div>

<div class="p-5" id="contenedores">
    @foreach ($estilos as $estilo)
    <div class="bg-dark text-white p-3 m-3 rounded align-content-center d-inline-block"
        id="{{ $estilo['epoca_inicio'] }} {{ $estilo['epoca_final'] }}" onclick="redirigir({{ $estilo['id'] }})">
        <h2 class="text-white">{{ $estilo['nombre'] }}</h2>
        <p>{{ $estilo['epoca_inicio'] }} - {{ $estilo['epoca_final'] }}</p>

        @if($nivel > 0)
        <button class="btn btn-primary"
            onclick="event.stopPropagation(); redirigir({{ $estilo['id'] }}, true)">Modificar</button>
        <button class="btn btn-danger"
            onclick="event.stopPropagation(); eliminar({{ $estilo['id'] }})">Eliminar</button>
        @endif
    </div>
    @endforeach
</div>
@endsection
