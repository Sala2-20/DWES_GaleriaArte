<?php $__env->startSection("title", "Formulario"); ?>
<?php $__env->startSection("content"); ?>
<div class="formulario" >
    <?php echo csrf_field(); ?>
    <form action="<?php echo $__env->yieldContent("enviar"); ?>" method="post">
        <?php echo $__env->yieldContent("inputs"); ?>
        <input type="submit" value="Enviar">
    </form>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.plantillaAjustes", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pruebaLaravel\resources\views/layouts/plantillaFormulario.blade.php ENDPATH**/ ?>