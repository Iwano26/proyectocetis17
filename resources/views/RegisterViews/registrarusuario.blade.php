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
</head>
<body>
    
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Registro Exitoso!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Iniciar Sesión'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/login') }}"; 
                }
            });
        </script>
    @endif
    
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
                @error('nombre') <div class="error-message">{{ $message }}</div> @enderror

                <label for="apellidoPa">Apellido Paterno</label>
                <input type="text" name="apellidoPa" placeholder="Apellido Paterno">
                @error('apellidoPa') <div class="error-message">{{ $message }}</div> @enderror

                <label for="apellidoMa">Apellido Materno</label>
                <input type="text" name="apellidoMa" placeholder="Apellido Materno">
                @error('apellidoMa') <div class="error-message">{{ $message }}</div> @enderror
                
                <label for="correo">Correo Institucional</label>
                <input type="email" name="correo" placeholder="Correo Institucional">
                @error('correo') <div class="error-message">{{ $message }}</div> @enderror

                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" placeholder="Número de Teléfono" maxlength="10">
                @error('telefono') <div class="error-message">{{ $message }}</div> @enderror

                <input type="hidden" name="rol" value="Estudiante">

                <label for="contrasennia">Contraseña</label>
                <input type="password" name="contrasennia" id="contrasennia" placeholder="Contraseña, min 8, Mayús/Minús/Número">
                @error('contrasennia') <div class="error-message">{{ $message }}</div> @enderror
                
                <label for="recontrasennia">Confirmar Contraseña</label>
                <input type="password" name="recontrasennia" placeholder="Confirma tu Contraseña">
                @error('recontrasennia') <div class="error-message">{{ $message }}</div> @enderror
                
                <button type="submit" id="enviarFormulario">REGISTRAR</button>
                
                <div class="links">
                    <a href="/login">Iniciar Sesión</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/; 
            
            $.validator.addMethod("correoInstitucional", function(value, element) {
                const regexCorreo = /^[a-zA-Z0-9._%+-]+@cetis17\.edu\.mx$/;
                return this.optional(element) || regexCorreo.test(value);
            }, "Correo inválido");

            $.validator.addMethod("regexContrasennia", function(value, element) {
                return this.optional(element) || regex.test(value);
            }, "Tu contraseña debe tener como mínimo: 8 caracteres, una mayúscula, una minúscula y un número.");

            $.validator.addMethod("compararContrasennias", function(value, element) {
                return value == $("#contrasennia").val();
            });

            $.validator.addMethod("telefonoValido", function(value, element) {
                return this.optional(element) || /^\d{10}$/.test(value);
            }, "El número de teléfono debe tener exactamente 10 dígitos.");

            $('#registroForm').validate({
                rules: {
                    nombre: { required: true },
                    apellidoPa: { required: true },
                    apellidoMa: { required: true },
                    correo: { 
                        required: true, 
                        email: true,
                        correoInstitucional: true 
                    },
                    telefono: { required: true, telefonoValido: true },
                    // El campo 'rol' ya no necesita validación de usuario pero se deja por estructura
                    rol: { required: true },
                    contrasennia: {
                        required: true,                        
                        minlength: 8, 
                        regexContrasennia: true
                    },
                    recontrasennia: {
                        required: true,
                        compararContrasennias: true,
                    },
                },
                messages: {
                    nombre: { required: "Favor de ingresar tu nombre" },
                    apellidoPa: { required: "Favor de ingresar tu apellido paterno" },
                    apellidoMa: { required: "Favor de ingresar tu apellido materno" },
                    correo: { 
                        required: "Favor de ingresar un correo institucional", 
                        email: "Correo electrónico no válido",
                        correoInstitucional: "Correo inválido"
                    },
                    telefono: { required: "Favor de ingresar tu número de teléfono" },
                    contrasennia: {
                        required: "Favor de ingresar una contraseña",                      
                        minlength: "Contraseña débil", 
                        regexContrasennia: "Tu contraseña debe tener como mínimo: 8 caracteres, una mayúscula, una minúscula y un número."
                    },
                    recontrasennia: {
                        required: "Favor de confirmar la contraseña",
                        compararContrasennias: "Las contraseñas no coinciden."
                    },
                },
                errorElement: 'div', 
                errorPlacement: function (error, element) {                
                    error.addClass('error-message'); 
                    error.insertAfter(element);
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>