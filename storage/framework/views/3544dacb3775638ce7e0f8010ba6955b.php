<?php $__env->startSection("title", "Estilos"); ?>

<?php $__env->startSection('script'); ?>
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
        <?php if($nivel > 0): ?>
            const url = bool
                ? `/estilos/${buscar}?modificar=1&id=${id}`
                : `/estilos/${buscar}?id=${id}`;
            window.location.href = url;
        <?php else: ?>
        window.location.href = `/estilos/${buscar}?id=${id}`;
        <?php endif; ?>
    }

    <?php if($nivel > 0): ?>
    async function eliminar(id) {
        if (!confirm("¿Estás seguro de que deseas eliminar este estilo?")) return;

        try {
            const response = await fetch("<?php echo e(route('estilo.destroy', '')); ?>/" + id, {
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
    <?php endif; ?>


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
<?php $__env->stopSection(); ?>

<?php $__env->startSection("content"); ?>
<?php if($nivel > 0): ?>
<div class="d-flex justify-content-center align-content-center flex-column">
    <form action="<?php echo e(route('estilo.create')); ?>" method="POST" enctype="multipart/form-data" id="nuevaInserccion" style="display: none;">
        <?php echo csrf_field(); ?>
        <label>Nombre del estilo:</label>
        <input type="text" name="nombre" id="nombre">

        <label>Caracteristicas:</label>
        <input type="text" name="caracteristicas" id="caracteristicas">

        <label>Epoca del estilo:</label>
        <input type="date" name="epoca_inicio" id="nacimiento">
        <input type="date" name="epoca_final" id="fallecimiento">

        <input type="submit" value="Crear estilo">
    </form>
    <img src="<?php echo e(asset(path: 'images/f-abajo.png')); ?>" alt="Aparecer formulario" style="width: 60px; height: 60px;margin-left: auto;margin-right: auto;" id="mostrar">
</div>
<?php endif; ?>

<div class="buscar d-flex flex-row justify-content-center align-items-center">
    <button id="pintor" class="btn btn-dark" onclick="cambiarBuscar(event)">Pintores</button>
    <button id="obra" class="btn btn-light" onclick="cambiarBuscar(event)">Obras</button>
</div>

<div class="p-5" id="contenedores">
    <?php $__currentLoopData = $estilos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-dark text-white p-3 m-3 rounded align-content-center d-inline-block"
        id="<?php echo e($estilo['epoca_inicio']); ?> <?php echo e($estilo['epoca_final']); ?>" onclick="redirigir(<?php echo e($estilo['id']); ?>)">
        <h2 class="text-white"><?php echo e($estilo['nombre']); ?></h2>
        <p><?php echo e($estilo['epoca_inicio']); ?> - <?php echo e($estilo['epoca_final']); ?></p>

        <?php if($nivel > 0): ?>
        <button class="btn btn-primary"
            onclick="event.stopPropagation(); redirigir(<?php echo e($estilo['id']); ?>, true)">Modificar</button>
        <button class="btn btn-danger"
            onclick="event.stopPropagation(); eliminar(<?php echo e($estilo['id']); ?>)">Eliminar</button>
        <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.plantilla", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pruebaLaravel\resources\views/estilo.blade.php ENDPATH**/ ?>