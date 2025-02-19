<?php $__env->startSection('cssInicio'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/basico.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("scriptInicio"); ?>
    <script>
        <?php if(auth()->guard()->check()): ?>
            <?php if(Auth::user()->nivel > 0): ?>
                window.onload = function () {
                    formulario();
                    <?php if($errors->has('error')): ?>
                        let error = ' ' + <?php echo e($errors->first('error')); ?>;
                        alert("Error al crear el estilo:" + error);
                    <?php endif; ?>
                    <?php if(Session::has('correcto')): ?>
                        alert("Error al crear el estilo:" + 'Session::get("correcto")');
                    <?php endif; ?>
                }
            <?php endif; ?>
        <?php endif; ?>
        document.getElementById("cabecera").addEventListener("click", function (event) {
            if (event.target.tagName === "H3") {
                window.location.href = event.target.id;
            }
        });
        function formulario() {
            let img = document.getElementById("mostrar");
            let form = document.getElementById("nuevaInserccion");
            img.addEventListener("click", () => {
                if (form.style.display === "none") {
                    img.src = "<?php echo e(asset(path: 'images/f-arriba.png')); ?>";
                    form.style.display = "block";
                } else {
                    img.src = "<?php echo e(asset(path: 'images/f-abajo.png')); ?>";
                    form.style.display = "none";
                }
            });
        }
    </script>
    <?php echo $__env->yieldContent('script'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("contentInicio"); ?>
    <header id="cabecera">
        <h3 id="/pintores">Pintores</h3>
        <h3 id="/obras">Obras</h3>
        <h3 id="/estilos">Estilos</h3>
        <h3 id="/ajustes" style="align-self: self-end;">Ajustes</h3>
    </header>
    <?php echo $__env->yieldContent('content'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.plantillaInicio', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pruebaLaravel\resources\views/layouts/plantilla.blade.php ENDPATH**/ ?>