<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Usuario | Asesorias Cetis 17</title>

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
                <img src="{{ asset('img/cetis.png') }}" alt="Logo Cetis17" />
            </div>
            <h2>Asesorias Cetis 17</h2>

            <form id="registroForm" action="{{route('register.store')}}" method="post" >
                @csrf
                
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" placeholder="Nombre">

                <label for="apellidoPa">Apellido Paterno</label>
                <input type="text" name="apellidoPa" placeholder="Apellido Paterno">

                <label for="apellidoMa">Apellido Materno</label>
                <input type="text" name="apellidoMa" placeholder="Apellido Materno">
                
                <label for="correo">Correo Institucional</label>
                <input type="email" name="correo" placeholder="Correo Institucional">

                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" placeholder="Número de Teléfono" maxlength="10">

                <label for="rol">Rol (Estudiante/Asesor)</label>
                <select name="rol">
                    <option value="">Selecciona un rol</option>
                    <option value="Estudiante">Estudiante</option>
                    <option value="Asesor">Asesor</option>
                </select>

                <label for="contrasennia">Contraseña</label>
                <input type="password" name="contrasennia" id="contrasennia" placeholder="Contraseña, min 8, Mayús/Minús/Número">
                
                <label for="recontrasennia">Confirmar Contraseña</label>
                <input type="password" name="recontrasennia" placeholder="Confirma tu Contraseña">
                
                
                <button type="submit" id="enviarFormulario">REGISTRAR</button>
                
                <div class="links">
                    <a href="/login">Iniciar Sesión</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Requisitos de Contraseña: al menos 8 caracteres, una minúscula, una mayúscula y un número.
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/; 
            
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

            // Método de validación para el formato de la contraseña
            $.validator.addMethod(
                "regexContrasennia",
                function(value, element) {
                    if (this.optional(element)) {
                        return true;
                    }
                    return regex.test(value);
                },
                "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número."
            );

            // Método de validación para confirmar la contraseña
            $.validator.addMethod(
                "compararContrasennias",
                function(value, element) {
                    return value == $("#contrasennia").val();
                }
            );

            // Método de validación para el teléfono (10 dígitos)
            $.validator.addMethod(
                "telefonoValido",
                function(value, element) {
                    return this.optional(element) || /^\d{10}$/.test(value);
                },
                "El número de teléfono debe tener exactamente 10 dígitos."
            );

            $('#registroForm').validate({
                rules: {
                    nombre: { required: true },
                    apellidoPa: { required: true },
                    apellidoMa: { required: true },
                    correo: { required: true, email: true }, // Mantenido para consistencia con la DB
                    telefono: { required: true, telefonoValido: true },
                    rol: { required: true },
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
                    nombre: { required: "Favor de ingresar tu nombre" },
                    apellidoPa: { required: "Favor de ingresar tu apellido paterno" },
                    apellidoMa: { required: "Favor de ingresar tu apellido materno" },
                    correo: { required: "Favor de ingresar un correo institucional", email: "Correo electrónico no válido" },
                    telefono: { required: "Favor de ingresar tu número de teléfono" },
                    rol: { required: "Favor de seleccionar un rol" },
                    contrasennia: {
                        required: "Favor de ingresar una contraseña",
                        regexContrasennia: "Tu contraseña debe tener como mínimo: 8 caracteres, una mayúscula, una minúscula y un número."
                    },
                    recontrasennia: {
                        required: "Favor de confirmar la contraseña",
                        compararContrasennias: "Las contraseñas no coinciden."
                    },
                    terminos: "Debes aceptar los términos"
                },
                errorElement: 'div', 
                errorPlacement: function (error, element) {
                    error.addClass('error-message');
                    // Colocar el error después del elemento, con manejo especial para el select
                    if (element.attr("name") == "rol") {
                        error.insertAfter(element.parent());
                    } else if (element.attr("name") == "terminos") {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>