<?php $__env->startSection("title", $infoEstilo['nombre']); ?>
<?php $__env->startSection('script'); ?>
    <script>
        <?php if($nivel > 0 && $errors->has('error')): ?>
            window.onload = iniciar;
            function iniciar() {
                alert("Error al modificar la obra " + '<?php echo e($errors->first('error')); ?>');
            }
        <?php endif; ?>
        function redirigir(id) {
            <?php if($vista): ?>
                window.location.href = `/pintores/obras?id=${id}`;
            <?php else: ?>
                window.location.href = `/obras/obra?id=${id}`;
            <?php endif; ?>
        }
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("content"); ?>
    <div id="pintor" class="container">
        <?php if(isset($mod) && $mod === '1' && $nivel > 0): ?>
            <div class="card">
                <form action="<?php echo e(route('obra.update', explode('=', explode('&', explode('?', url()->full())[1])[0])[1])); ?>"
                    method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <label>Nombre del estilo:</label>
                    <input type="text" name="nombre" id="nombre" value="<?php echo e($infoEstilo['nombre']); ?>">

                    <label>Caracteristicas:</label>
                    <input type="text" name="caracteristicas" id="caracteristicas" value="<?php echo e($infoEstilo['caracteristicas']); ?>">

                    <label>Epoca del estilo:</label>
                    <input type="date" name="epoca_inicio" id="epoca_inicio" value="<?php echo e($infoEstilo['epoca_inicio']); ?>">
                    <input type="date" name="epoca_final" id="epoca_final" value="<?php echo e($infoEstilo['epoca_final']); ?>">
                    <select name="id_estilo" id="id_estilo" required>
                        <option value="">Elige un pintor</option>
                        <?php $__currentLoopData = $pintores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pintor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($pintor['id']); ?>"><?php echo e($pintor['nombre']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <input type="submit" value="Crear estilo">
                </form>
            </div>
        <?php else: ?>
            <div class="card container mt-5">
                <div>
                    <h1><?php echo e($infoEstilo['nombre']); ?></h1>
                    <p class="mx-3"><?php echo e($infoEstilo['caracteristicas']); ?></p>
                    <p class="mx-3"><?php echo e(explode('-',$infoEstilo['epoca_inicio'])[0]); ?> - <?php echo e(explode('-',$infoEstilo['epoca_final'])[0]); ?></p>
                    <?php if(is_array($pintores) && count($pintores) > 0): ?>
                        <p class="mx-3">Pintores que han participado a este estilo:</p>
                        <ul class="mx-3">
                            <?php $__currentLoopData = $pintores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pintor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mx-3"><?php echo e($pintor['nombre']); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="p-5" id="contenedores">
        <?php $__currentLoopData = $info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-dark text-white p-3 m-3 rounded" style="display: inline-block; width: auto;"
                onclick="redirigir(<?php echo e($obj['id']); ?>)">
                <?php if(isset($obj['imagen']) && isset($obj['tipo'])): ?>
                    <img src="data:image/<?php echo e($obj['tipo']); ?>; base64,<?php echo e(base64_encode($obj['imagen'])); ?>" alt="<?php echo e($obj['nombre']); ?>"
                        class="imagen" />
                <?php elseif(isset($obj['imagen'])): ?>
                    <img src="<?php echo e($obj['imagen']); ?>" alt="<?php echo e($obj['nombre']); ?>" class="imagenPintor" />
                <?php else: ?>
                    <p>Imagen no disponible</p>
                <?php endif; ?>
                <h2 class="text-white"><?php echo e($obj['nombre']); ?></h2>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.plantilla", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pruebaLaravel\resources\views/detalleEstilo.blade.php ENDPATH**/ ?>