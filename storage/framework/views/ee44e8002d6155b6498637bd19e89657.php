<?php $__env->startSection("title", $info['nombre']); ?>
<?php $__env->startSection('script'); ?>
    <script>
        <?php if(isset($mod) && $mod === '1' && $nivel > 0): ?>

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
        <?php endif; ?>
        <?php if($nivel > 0 && $errors->has('error')): ?>
            window.onload = iniciar;
            function iniciar() {
                alert("Error al modificar la obra " + '<?php echo e($errors->first('error')); ?>');
            }
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("content"); ?>
    <div id="pintor" class="container py-5">
        <?php if(isset($mod) && $mod === '1' && $nivel > 0): ?>
            <div class="card">
                <form action="<?php echo e(route('obra.update', explode('=', explode('&', explode('?', url()->full())[1])[0])[1])); ?>"
                    method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <br>
                    <img id="preview" src="<?php if($info['imagen']): ?><?php echo e($info['imagen']); ?><?php endif; ?>" alt="Vista previa de la imagen"
                        class="imagenPintor" style="max-width: 300px;"><br>
                    <label for="imagen">Selecciona un archivo:</label>
                    <input type="file" name="imagen" accept="image/*" id="imagen">
                    <br>
                    Nombre de la obra:
                    <input type="text" name="nombre" id="nombre" value="<?php echo e($info['nombre']); ?>" required>
                    <br>
                    Descripcion:
                    <input type="text" name="descripcion" id="descripcion" value="<?php echo e($info['descripcion']); ?>">
                    <br>
                    <select name="id_pintor" id="id_estilo" required>
                        <option value="">Elige un pintor</option>
                        <?php $__currentLoopData = $pintores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pintor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($pintor['id']); ?>"><?php echo e($pintor['nombre']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <br>
                    Año:
                    <input type="date" name="anio" id="anio" value="<?php echo e(intval($info['anio'])); ?>-01-01" required>
                    <br>
                    Tamaño:
                    <input type="number" name="ancho" id="ancho" value="<?php echo e(intval(explode('x', $info['tamanio'])[0])); ?>"
                        required>
                    <input type="number" name="alto" id="alto" value="<?php echo e(intval(explode('x', $info['tamanio'])[1])); ?>" required>
                    <br>
                    <select name="tematica" id="id_estilo" required>
                        <option value="">Elige un estilo</option>
                        <?php $__currentLoopData = $estilos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($estilo['id']); ?>"><?php echo e($estilo['nombre']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <br>
                    <input type="submit" value="Crear obra" class="boton">
                </form>
            </div>
        <?php else: ?>
            <div class="card">
                <?php if(isset($info['imagen'])): ?>
                    <img src="<?php echo e($info['imagen']); ?>" alt="<?php echo e($info['nombre']); ?>" class="imagen img-thumbnail align-self-center" />
                <?php else: ?>
                    <p>Imagen no disponible</p>
                <?php endif; ?>
                <p class="h1 nombre"><?php echo e($info['nombre']); ?></p>
                <p class="mx-3"><?php echo e($info['descripcion']); ?></p>
                <p class="h4 mx-3"><a href="/pintores/obras?id=<?php echo e($info['id_pintor']); ?>" class="link link-dark link-underline-opacity-0 link-underline-opacity-50-hover"><?php echo e($info['pintor']); ?></a>, <?php echo e($info['anio']); ?></p>
                <h5 class="mx-3"><?php echo e($info['tamanio']); ?>, con la tematica: <?php echo e($info['estilo']); ?></h5>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.plantilla", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pruebaLaravel\resources\views/detalleObra.blade.php ENDPATH**/ ?>