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

<?php $__currentLoopData = $obras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card" onclick="redirigir(<?php echo e($obra['id']); ?>)">
        <?php if($obra['imagen']): ?>
            <img src="<?php echo e($obra['imagen']); ?>" alt="<?php echo e($obra['nombre']); ?>" class="imagenPintor" />
        <?php else: ?>
            <p>Imagen no disponible</p>
        <?php endif; ?>
        <h2 class="nombre"><?php echo e($obra['nombre']); ?></h2>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.plantilla", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pruebaLaravel\resources\views/detalle.blade.php ENDPATH**/ ?>