<?php $__env->startSection("title", "Obras"); ?>

<?php $__env->startSection('script'); ?>
<script>
    function redirigir(id, modificar = false) {
        <?php if($nivel > 0): ?>
            const url = modificar
                ? `/obras/obra?modificar=1&id=${id}`
                : `/obras/obra?id=${id}`;
            window.location.href = url;
        <?php else: ?>
            window.location.href = `/obras/obra?id=${id}`;
        <?php endif; ?>
    }

    <?php if($nivel > 0): ?>
    async function eliminar(id) {
        if (!confirm("¿Estás seguro de que deseas eliminar esta obra?")) return;

        try {
            const response = await fetch("<?php echo e(route('obra.destroy', '')); ?>/" + id, {
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
    <?php endif; ?>

    <?php if($errors->has('error')): ?>
    window.onload = function() {
        let error = ' ' + <?php echo e($errors->first('error')); ?>;
        alert("Error al crear la obra:" + error );
    };
    <?php endif; ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection("content"); ?>
<?php if($nivel > 0): ?>
<div class="d-flex justify-content-center align-content-center flex-column">
    <form action="<?php echo e(route('obra.create')); ?>" method="POST" enctype="multipart/form-data" id="nuevaInserccion" style="display: none;">
        <?php echo csrf_field(); ?>
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
            <?php $__currentLoopData = $pintores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pintor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($pintor['id']); ?>"><?php echo e($pintor['nombre']); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="id_estilo" id="id_estilo" required>
            <option value="">Elige un estilo</option>
            <?php $__currentLoopData = $estilos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($estilo['id']); ?>"><?php echo e($estilo['nombre']); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <input type="submit" value="Crear obra" class="boton">
    </form>
    <img src="<?php echo e(asset(path: 'images/f-abajo.png')); ?>" alt="Aparecer formulario" style="width: 60px; height: 60px;margin-left: auto;margin-right: auto;" id="mostrar">
</div>
<?php endif; ?>
<div class="p-5" id="contenedores">
<?php $__currentLoopData = $obras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class=" bg-dark text-white p-3 m-3 rounded" style="display: inline-block; width: auto;" onclick="redirigir(<?php echo e($obra['id']); ?>)">
        <?php if(isset($obra['imagen']) && isset($obra['tipo'])): ?>
                    <img src="data:image/<?php echo e($obra['tipo']); ?>; base64,<?php echo e(base64_encode($obra['imagen'])); ?>" alt="<?php echo e($obra['nombre']); ?>"
                        class="imagen" />
        <?php elseif(isset($obra['imagen'])): ?>
            <img src="<?php echo e($obra['imagen']); ?>" alt="<?php echo e($obra['nombre']); ?>" class="imagenPintor"  style="width: 200px; height: 200px;"/>
        <?php else: ?>
            <p>Imagen no disponible</p>
        <?php endif; ?>

        <h2 class="nombre"><?php echo e($obra['nombre']); ?></h2>

        <?php if($nivel > 0): ?>
            <button class="btn btn-primary" onclick="event.stopPropagation(); redirigir(<?php echo e($obra['id']); ?>, true)">Modificar</button>
            <button class="btn btn-danger" onclick="event.stopPropagation(); eliminar(<?php echo e($obra['id']); ?>)">Eliminar</button>
        <?php endif; ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.plantilla", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pruebaLaravel\resources\views/obras.blade.php ENDPATH**/ ?>