<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Usuario | AlmacenPLUS</title>

    <link rel="stylesheet" href="{{ asset('css/estiloregistro.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../plugins/jquery-validation/additional-methods.min.js"></script>

    {{-- Se eliminan todos los CSS de AdminLTE --}}
</head>
<body>
    <div class="fondo">
        <div class="register-box">
            <div class="logo">
                {{-- Aquí puedes poner un logo para AlmacenPLUS --}}
                <img src="{{ asset('img/cetis.png') }}" alt="Logo Cetis17" />
            </div>
            <h2>Asesorias Cetis 17</h2>

            <form id="registroForm" action="http://cetis.test/guardar" method="post" >
                @csrf
                
                <label for="nombre">Nombre Completo</label>
                <input type="text" name="nombre" placeholder="Nombre Completo">

                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" placeholder="Correo Electrónico">
                

                <label for="usuario">Nombre de Usuario</label>
                <input type="text" name="usuario" placeholder="Nombre de Usuario">

                <label for="contrasennia">Contraseña</label>
                <input type="password" name="contrasennia" id="contrasennia" placeholder="Contraseña, min 8, Mayús/Minús/Número/Símbolo">
                
                <label for="recontrasennia">Confirmar Contraseña</label>
                <input type="password" name="recontrasennia" placeholder="Confirma tu Contraseña">
                
                <div class="checkbox-group">
                    <input type="checkbox" id="agreeTerms" name="terminos" value="agree" >
                    <label for="agreeTerms">Acepto los <a href="#">términos</a></label>
                </div>
                
                <button type="submit" id="enviarFormulario">REGISTRAR</button>
                
                <div class="links">
                    <a href="/login">Iniciar Sesión</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Expresión regular ajustada para que coincida con el mensaje de error del JS
            // La expresión original era: /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/
            // El mensaje de error decía: "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número."
            // Se usa la RE que coincide con el mensaje de error más común para una mejor UX.
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/; 
            
            // Reemplazar la definición Toast con la de SweetAlert2 simple para errores
            function mostrarError(title, text) {
                 Swal.fire({
                    icon: 'error',
                    title: title,
                    text: text,
                    customClass: {
                        popup: 'swal-wide',
                    },
                 });
            }

            $.validator.addMethod(
                "regexContrasennia",
                function(value, element) {
                    // Si el campo es opcional y está vacío, pasa la validación
                    if (this.optional(element)) {
                        return true;
                    }
                    // Si no, prueba con la regex
                    return regex.test(value);
                },
                "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número."
            );

            $.validator.addMethod(
                "compararContrasennias",
                function(value, element) {
                    return value == $("#contrasennia").val();
                }
            );

            $('#registroForm').validate({
                rules: {
                    nombre: { required: true },
                    correo: { required: true, email: true },
                    usuario: { required: true, minlength: 3 },
                    contrasennia: {
                        required: true,
                        regexContrasennia: true
                    },
                    recontrasennia: {
                        required: true,
                        compararContrasennias: true,
                    },
                    terminos: { required: true },
                },
                messages: {
                    nombre: { required: "Favor de ingresar nombre completo" },
                    correo: { required: "Favor de ingresar un correo electrónico", email: "Correo electrónico no válido" },
                    usuario: { required: "Favor de ingresar un nombre de usuario", minlength: "El nombre de usuario debe tener al menos 3 caracteres" },
                    contrasennia: {
                        required: "Favor de ingresar una contraseña",
                        regexContrasennia: "Tu contraseña debe tener como mínimo: 8 caracteres, una mayúscula, una minúscula y un número."
                    },
                    recontrasennia: {
                        required: "Favor de ingresar una contraseña",
                        compararContrasennias: "Las contraseñas no coinciden."
                    },
                    terminos: "Debes aceptar los términos"
                },
                errorElement: 'div', // Usar un div simple
                errorPlacement: function (error, element) {
                    error.addClass('error-message'); // Clase para estilizar los errores
                    element.after(error); // Colocar el error después del elemento
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>