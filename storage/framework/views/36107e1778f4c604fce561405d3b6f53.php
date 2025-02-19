<?php $__env->startSection("title", "Pintores"); ?>
<?php $__env->startSection('script'); ?>
<script>
    function redirigir(id) {
        const url = `/pintores/obras?id=${id}`;
        window.location.href = url;
    }
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("content"); ?>
<?php if($nivel > 0): ?>
    <?php if($errors->first('error')): ?>
        <script>
            alert("Error al crear la obra " + '<?php echo e($errors->first('error')); ?>');
        </script>
    <?php endif; ?>
    <form action="<?php echo e(route('pintor.create')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        Nombre del pintor:
        <br>
        <input type="text" name="nombre" id="nombre">
        <br>
        Descripcion:
        <br>
        <input type="text" name="descripcion" id="descripcion">
        <br>
        Nacionalidad:
        <br>
        <input type="text" name="nacionalidad" id="nacionalidad">
        <br>
        <label for="imagen">Selecciona un archivo:</label>
        <br>
        <input type="file" name="imagen" required>
        <br>
        Nacimiento:
        <br>
        <input type="date" name="nacimiento" id="nacimiento">
        <br>
        Fallecimiento:
        <br>
        <input type="date" name="fallecimiento" id="fallecimiento">
        <br>
        <input type="submit" value="Crear pintor" class="boton">
    </form>
<?php endif; ?>
<?php $__currentLoopData = $pintores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pintor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card" onclick="redirigir(<?php echo e($pintor['id']); ?>)">
        <?php if($pintor['imagen']): ?>
            <img src="<?php echo e($pintor['imagen']); ?>" alt="<?php echo e($pintor['nombre']); ?>" class="imagenPintor" />
        <?php else: ?>
            <p>Imagen no disponible</p>
        <?php endif; ?>
        <h2 class="nombre"><?php echo e($pintor['nombre']); ?></h2>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.plantilla", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pruebaLaravel\resources\views/start.blade.php ENDPATH**/ ?>