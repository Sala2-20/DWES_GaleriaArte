<?php $__env->startSection("title", "Pintores"); ?>

<?php $__env->startSection('script'); ?>
    <script>
        function redirigir(id, bool = false) {
            <?php if($nivel > 0): ?>
                const url = bool
                    ? `/pintores/obras?modificar=1&id=${id}`
                    : `/pintores/obras?id=${id}`;
                window.location.href = url;
            <?php else: ?>
                window.location.href = `/pintores/obras?id=${id}`;
            <?php endif; ?>
        }

        <?php if($nivel > 0): ?>
            async function eliminar(id) {
                if (!confirm("¿Estás seguro de que deseas eliminar este pintor?")) return;

                try {
                    const response = await fetch("<?php echo e(route('pintor.destroy', '')); ?>/" + id, {
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
        <?php endif; ?>

        <?php if($errors->has('error')): ?>
            window.onload = function () {
                let error = '<?php echo e($errors->first('error')); ?>';
                alert("Error al crear pinto: " + error);
            };
        <?php endif; ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection("content"); ?>
    <?php if($nivel > 0): ?>
    <div class="d-flex justify-content-center align-content-center flex-column">
        <form action="<?php echo e(route('pintor.create')); ?>" method="POST" enctype="multipart/form-data" id="nuevaInserccion" style="display: none;">
            <?php echo csrf_field(); ?>
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
        <img src="<?php echo e(asset(path: 'images/f-abajo.png')); ?>" alt="Aparecer formulario" style="width: 60px; height: 60px;margin-left: auto;margin-right: auto;" id="mostrar">
    </div>
    <?php endif; ?>
    <div class="d-flex justify-content-center align-content-center">
        <form action="" class="d-flex justify-content-center align-content-center">
            <select name="epoca" id="epoca">
                <option value="">Elige una época</option>
                <?php $__currentLoopData = $filtrar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(isset($id['epoca'])): ?>
                        <option value="<?php echo e($id['epoca']); ?>"><?php echo e($id['epoca']); ?></option>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="nacionalidad" id="nacionalidad">
                <option value="">Elige una nacionalidad</option>
                <?php $__currentLoopData = $filtrar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(isset($id['nacionalidad'])): ?>
                        <option value="<?php echo e($id['nacionalidad']); ?>"><?php echo e($id['nacionalidad']); ?></option>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <input type="submit" value="Buscar" class="btn btn-dark" onclick="filtrarPintores(event)">
        </form>
    </div>
    <div class="p-5" id="contenedores">
        <?php $__currentLoopData = $pintores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pintor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-dark text-white p-3 m-3 rounded" style="display: inline-block; width: auto;"
                id="<?php echo e($pintor['epoca']); ?> <?php echo e($pintor['nacionalidad']); ?>"
                onclick="redirigir(<?php echo e($pintor['id']); ?>)">
                <?php if(isset($pintor['imagen'])): ?>
                    <img src="<?php echo e($pintor['imagen']); ?>" alt="<?php echo e($pintor['nombre']); ?>" class="imagenPintor img-fluid"
                        style="width: 200px; height: 200px;">
                <?php else: ?>
                    <p>Imagen no disponible</p>
                <?php endif; ?>

                <h2 class="nombre"><?php echo e($pintor['nombre']); ?></h2>

                <?php if($nivel > 0): ?>
                    <button class="btn btn-primary"
                        onclick="event.stopPropagation(); redirigir(<?php echo e($pintor['id']); ?>, true)">Modificar</button>
                    <button class="btn btn-danger"
                        onclick="event.stopPropagation(); eliminar(<?php echo e($pintor['id']); ?>)">Eliminar</button>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.plantilla", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pruebaLaravel\resources\views/pintores.blade.php ENDPATH**/ ?>