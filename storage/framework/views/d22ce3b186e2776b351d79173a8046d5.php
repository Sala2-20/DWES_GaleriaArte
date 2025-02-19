<?php $__env->startSection("title", "Pintores"); ?>
<?php $__env->startSection('script'); ?>
<script>
    /*function redirigir(id) {
        const url = `/pintores/obras?id=${id}`;
        window.location.href = url;
    }*/
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("content"); ?>
<?php if($nivel > 0): ?>
    <?php if($errors): ?>
        <script>
            alert("Error al crear la obra" + '<?php echo e($errors->first('error')); ?>');
        </script>
    <?php endif; ?>
    <form action="<?php echo e(route('obra.create')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        Nombre de la obra:
        <br>
        <input type="text" name="nombre" id="nombre" required>
        <br>
        Descripcion:
        <br>
        <input type="text" name="descripcion" id="descripcion">
        <br>
        Id pintor:
        <br>
        <input type="number" name="id_pintor" id="id_pintor" required>
        <br>
        <label for="imagen">Selecciona un archivo:</label>
        <br>
        <input type="file" name="imagen" required>
        <br>
        Año:
        <br>
        <input type="date" name="anio" id="anio" required>
        <br>
        Tamaño:
        <br>
        <input type="number" name="ancho" id="ancho" required>
        <input type="number" name="alto" id="alto" required>
        <br>
        Tematica:
        <br>
        <input type="text" name="tematica" id="tematica" required>
        <br>
        <input type="submit" value="Crear obra" class="boton">
    </form>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.plantilla", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pruebaLaravel\resources\views/NOindex.blade.php ENDPATH**/ ?>