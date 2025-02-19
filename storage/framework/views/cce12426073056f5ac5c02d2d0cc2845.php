<?php $__env->startSection("title", $infoPintor['nombre']); ?>
<?php $__env->startSection('script'); ?>
    <script>
        function redirigir(id) {
            const url = `/pintores/obras/obra?id=${id}`;
            window.location.href = url;
        }
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
            <form action=" <?php echo e(route('pintor.update', explode('=', explode('&', explode('?', url()->full())[1])[0])[1])); ?>"
                method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="d-flex">
                    <div>
                        <img src="<?php echo e($infoPintor['imagen']); ?>" alt="imagen del pintor" class="img-fluid" style="width: auto;"
                            id="preview" /> <br>
                        <input type="file" name="imagen" accept="image/*" id="imagen">
                    </div>
                    <div>
                        <input type="text" name="nombre" id="nombre" value="<?php echo e($infoPintor['nombre']); ?>"
                            style="font-size: xx-large"> <br>
                        <textarea name="descripcion" id="descripcion"
                            maxlength="1000"><?php echo e($infoPintor['descripcion']); ?></textarea><br>

                        <label for="nacimiento">Fecha de nacimiento:</label> <br>
                        <input type="date" name="nacimiento" id="nacimiento" value="<?php echo e($infoPintor['nacimiento']); ?>"> <br>
                        <label for="nacimiento">Fecha de fallecimiento:</label> <br>
                        <input type="date" name="fallecimiento" id="fallecimiento" value="<?php echo e($infoPintor['fallecimiento']); ?>">
                        <br>
                        <label for="nacionalidad">Nacionalidad:</label> <br>
                        <input type="text" name="nacionalidad" id="nacionalidad" value="<?php echo e($infoPintor['nacionalidad']); ?>"> <br>
                        <select name="id_estilo" id="id_estilo" required>
                            <option value="">Elige un estilo</option>
                            <?php $__currentLoopData = $estilos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($estilo['id']); ?>"><?php echo e($estilo['nombre']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <input type="submit" value="Editar pintor" class="boton">
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="card">
                <?php if(isset($infoPintor['imagen']) && isset($infoPintor['tipo'])): ?>
                    <img src="data:image/<?php echo e($infoPintor['tipo']); ?>; base64,<?php echo e(base64_encode($infoPintor['imagen'])); ?>" alt="<?php echo e($infoPintor['nombre']); ?>"
                        class="imagen" />
                <?php elseif(isset($infoPintor['imagen'])): ?>
                    <img src="<?php echo e($infoPintor['imagen']); ?>" alt="<?php echo e($infoPintor['nombre']); ?>" class="imagenPintor" />
                <?php else: ?>
                    <p>Imagen no disponible</p>
                <?php endif; ?>
                <div>
                    <h1><?php echo e($infoPintor['nombre']); ?></h1>
                    <p class="mx-3"><?php echo e($infoPintor['descripcion']); ?></p>
                    <p class="mx-3">Fecha de nacimiento: <?php echo e($infoPintor['nacimiento']); ?> <br>
                        Fecha de fallecimiento: <?php echo e($infoPintor['fallecimiento']); ?><br>
                        Nacionalidad: <?php echo e($infoPintor['nacionalidad']); ?> <br>
                        <?php if(is_array($estilos) && count($estilos) > 0): ?>
                                Estilos: </p>
                            <ul>
                                <?php $__currentLoopData = $estilos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($estilo['nombre']); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="p-5" id="contenedores">
        <?php $__currentLoopData = $obras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card" onclick="redirigir(<?php echo e($obra['id']); ?>)">
                <?php if(isset($obra['imagen']) && isset($obra['tipo'])): ?>
                    <img src="data:image/<?php echo e($obra['tipo']); ?>; base64,<?php echo e(base64_encode($obra['imagen'])); ?>" alt="<?php echo e($obra['nombre']); ?>"
                        class="imagen" />
                <?php elseif(isset($obra['imagen'])): ?>
                    <img src="<?php echo e($obra['imagen']); ?>" alt="<?php echo e($obra['nombre']); ?>" class="imagenPintor" />
                <?php else: ?>
                    <p>Imagen no disponible</p>
                <?php endif; ?>
                <h2 class="nombre text-black"><?php echo e($obra['nombre']); ?></h2>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.plantilla", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pruebaLaravel\resources\views/detallePintores.blade.php ENDPATH**/ ?>