<?php $__env->startSection('title', 'APF'); ?>
<?php $__env->startSection('cssInicio'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/registro.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("scriptInicio"); ?>
<script>
    //mostrar formularios
    function mostrarInicio() {
        document.getElementById("inicio").style.display = 'block';
        document.getElementById("btnInicio").style.display = 'none';
    }
    function mostrarRegis() {
        document.getElementById("registro").style.display = 'block';
        document.getElementById("btnRegistro").style.display = 'none';
    }

    //contraseñas
    const repetirCont = document.getElementById("contraseniaR");
    repetirCont.addEventListener("input", compContra);
    const cont = document.getElementById("contrasenia");
    cont.addEventListener("input", compContra);
    //correos
    const correo = document.getElementById("correo");
    correo.addEventListener("input", compCorreo);
    const repetirCorreo = document.getElementById("correoR");
    repetirCorreo.addEventListener("input", compCorreo);
    //formularios
    const formularioRegistro = document.getElementById("registro");
    formularioRegistro.addEventListener("submit", validarFormularioRegistro);
    const formularioInicio = document.getElementById("inicioSesion");
    formularioInicio.addEventListener("submit", validarFormulario);

    //comparar contraseñas
    function compContra() {
        const mensaje = document.getElementById("mensajeCont");
        if (repetirCont.value !== cont.value) {
            mensaje.innerHTML = "Contraseñas diferentes";
            mensaje.style.color = 'red';
        } else {
            mensaje.innerHTML = "";
        }
    }
    //comparar correos
    function compCorreo() {
        const mensaje = document.getElementById("mensajeEmail");
        if (repetirCorreo.value !== correo.value) {
            mensaje.innerHTML = "Contraseñas diferentes";
            mensaje.style.color = 'red';
        } else {
            mensaje.innerHTML = "";
        }
    }

    //validar formularios
    function validarFormularioRegistro(event) {
        let errores = [];

        if (nombre.value.trim() === "") {
            errores.push("❌ El nombre es obligatorio.");
        } else if (alfanumerico(nombreI)) {
            errores.push("❌ EL nombre no es valida.");
        }

        if (correo.value.trim() === "" || repetirCorreo.value.trim() === "") {
            errores.push("❌ El correo es obligatorio.");
        } else if (correo.value !== repetirCorreo.value) {
            errores.push("❌ Los correos no coinciden.");
        } else if (correoRegular(correo) || correoRegular(repetirCorreo)) {
            errores.push("❌ El correo no es válido.");
        }

        if (cont.value.trim() === "" || repetirCont.value.trim() === "") {
            errores.push("❌ La contraseña es obligatoria.");
        } else if (cont.value !== repetirCont.value) {
            errores.push("❌ Las contraseñas no coinciden.");
        } else if (alfanumerico(cont) || alfanumerico(repetirCont)) {
            errores.push("❌ La contraseña no es valida.");
        }

        if (errores.length > 0) {
            event.preventDefault();
            alert(errores.join("\n"));
        }
    }
    function validarFormulario(event) {
        let errores = [];
        const nombreI = document.getElementById("nombreInicio");
        const contI = document.getElementById("contraseniaInicio");
        const correoI = document.getElementById("correoInicio");

        if (nombreI.value.trim() === "") {
            errores.push("❌ El nombre es obligatorio.");
        } else if (alfanumerico(nombreI)) {
            errores.push("❌ EL nombre no es valida.");
        }

        if (correoI.value.trim() === "") {
            errores.push("❌ El correo es obligatorio.");
        } else if (correoRegular(correoI)) {
            errores.push("❌ El correo no es válido.");
        }

        if (contI.value.trim() === "") {
            errores.push("❌ La contraseña es obligatoria.");
        } else if (alfanumerico(contI)) {
            errores.push("❌ La contraseña no es valida.");
        }

        if (errores.length > 0) {
            event.preventDefault();
            alert(errores.join("\n"));
        }
    }

    //comprobar formas
    function correoRegular(correoReg) {
        return /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/.test(correoReg);
    }
    function alfanumerico(str) {
        return /^[a-zA-Z0-9]+$/.test(str);
    }
    <?php if($errors->has('error')): ?>
    //errores al crear usuarios
        window.onload = iniciar;
        function iniciar() {
            alert("Error al crear la obra" + '<?php echo e($errors->first('error')); ?>');
        }
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('contentInicio'); ?>
<header id="cabecera">
    <h1>APF</h1>
</header>
<div id="inicioSesion" class="contenedor" onclick="mostrarInicio()">
    <button class="boton" id="btnInicio">INICIAR SESION</button>
    <form action="<?php echo e(route('log.store')); ?>" method="POST" id="inicio" style="display: none;">
        <?php echo csrf_field(); ?>
        Nombre:
        <br>
        <input type="text" name="nombre" id="nombreInicio">
        <br>
        <br>
        Correo electrónico:
        <br>
        <input type="email" name="correo" id="correoInicio">
        <br>
        <br>
        Contraseña:
        <br>
        <input type="password" name="contrasenia" id="contraseniaInicio">
        <br>
        <br>
        <input type="submit" value="INICIAR SESION" class="botonInicio">
    </form>
</div>

<div id="registrarse" class="contenedor" onclick="mostrarRegis()">
    <button class="boton" id="btnRegistro">REGISTRARSE</button>
    <form action="<?php echo e(route('log.create')); ?>" method="POST" id="registro" style="display: none;">
        <?php echo csrf_field(); ?>
        Nombre:
        <br>
        <input type="text" name="nombre" id="nombre">
        <br>
        <br>
        Correo electrónico:
        <br>
        <input type="email" name="correo" id="correo">
        <br>
        <br>
        Repetir correo electrónico:
        <br>
        <input type="email" name="correoR" id="correoR">
        <br>
        <h5 id="mensajeEmail"></h5>
        <br>
        Contraseña:
        <br>
        <input type="password" name="contrasenia" id="contrasenia">
        <br>
        <br>
        Repetir contraseña:
        <br>
        <input type="password" name="contraseniaR" id="contraseniaR">
        <br>
        <h5 id="mensajeCont"></h5>
        <br>
        <input type="submit" value="REGISTRARSE" class="boton">
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.plantillaInicio', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pruebaLaravel\resources\views/index.blade.php ENDPATH**/ ?>