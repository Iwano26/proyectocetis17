<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Usuario | Asesorias Cetis 17</title>

    <link rel="stylesheet" href="{{ asset('css/estiloregistro.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
</head>
<body>

    {{-- Mensajes de sesión --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Registro Exitoso!',
                    text: @json(session('success')),
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#A71F21'
                });
            });
        </script>
    @endif

    @if(session('mensaje'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json(session('mensaje')),
                    confirmButtonColor: '#A71F21'
                });
            });
        </script>
    @endif

    {{-- Error de correo duplicado con SweetAlert --}}
    @if($errors->has('correo') && str_contains($errors->first('correo'), 'registrado'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Correo ya registrado!',
                    text: @json($errors->first('correo')),
                    confirmButtonColor: '#A71F21',
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

    <div class="fondo">
        <div class="register-box">
            <div class="logo">
                <img src="{{ asset('img/cetis.png') }}" alt="Logo Cetis17" />
            </div>
            <h2>Asesorias Cetis 17</h2>

            <form id="registroForm" action="{{ route('register.store') }}" method="POST">
                @csrf

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" placeholder="Nombre"
                    value="{{ old('nombre') }}"
                    class="{{ $errors->has('nombre') ? 'input-error' : '' }}">
                @error('nombre')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="apellidoPa">Apellido Paterno</label>
                <input type="text" name="apellidoPa" placeholder="Apellido Paterno"
                    value="{{ old('apellidoPa') }}"
                    class="{{ $errors->has('apellidoPa') ? 'input-error' : '' }}">
                @error('apellidoPa')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="apellidoMa">Apellido Materno</label>
                <input type="text" name="apellidoMa" placeholder="Apellido Materno"
                    value="{{ old('apellidoMa') }}"
                    class="{{ $errors->has('apellidoMa') ? 'input-error' : '' }}">
                @error('apellidoMa')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" placeholder="Correo Personal o Institucional"
                    value="{{ old('correo') }}"
                    class="{{ $errors->has('correo') ? 'input-error' : '' }}">
                @error('correo')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" placeholder="Número de Teléfono (10 dígitos)"
                    maxlength="10"
                    value="{{ old('telefono') }}"
                    class="{{ $errors->has('telefono') ? 'input-error' : '' }}">
                @error('telefono')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <input type="hidden" name="rol" value="Estudiante">

                <label for="contrasennia">Contraseña</label>
                <input type="password" name="contrasennia" id="contrasennia"
                    placeholder="Mín 8 caracteres, Mayús/Minús/Número"
                    class="{{ $errors->has('contrasennia') ? 'input-error' : '' }}">
                @error('contrasennia')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="recontrasennia">Confirmar Contraseña</label>
                <input type="password" name="recontrasennia" id="recontrasennia"
                    placeholder="Confirma tu Contraseña"
                    class="{{ $errors->has('recontrasennia') ? 'input-error' : '' }}">
                @error('recontrasennia')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <button type="submit" id="enviarFormulario">REGISTRAR</button>

                <div class="links">
                    <a href="/login">¿Ya tienes cuenta? Iniciar Sesión</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

            $.validator.addMethod("correoValido", function(value, element) {
                const regexGeneral = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return this.optional(element) || regexGeneral.test(value);
            }, "Ingresa un correo electrónico válido.");

            $.validator.addMethod("regexContrasennia", function(value, element) {
                return this.optional(element) || regex.test(value);
            }, "La contraseña debe tener: 8 caracteres, una mayúscula, una minúscula y un número.");

            $.validator.addMethod("compararContrasennias", function(value, element) {
                return value === $("#contrasennia").val();
            }, "Las contraseñas no coinciden.");

            $.validator.addMethod("telefonoValido", function(value, element) {
                return this.optional(element) || /^\d{10}$/.test(value);
            }, "El teléfono debe tener exactamente 10 dígitos.");

            $('#registroForm').validate({
                rules: {
                    nombre:         { required: true },
                    apellidoPa:     { required: true },
                    apellidoMa:     { required: true },
                    correo: {
                        required: true,
                        email: true,
                        correoValido: true
                    },
                    telefono:       { required: true, telefonoValido: true },
                    contrasennia: {
                        required: true,
                        minlength: 8,
                        regexContrasennia: true
                    },
                    recontrasennia: {
                        required: true,
                        compararContrasennias: true
                    },
                },
                messages: {
                    nombre:     { required: "Ingresa tu nombre." },
                    apellidoPa: { required: "Ingresa tu apellido paterno." },
                    apellidoMa: { required: "Ingresa tu apellido materno." },
                    correo: {
                        required: "Ingresa tu correo.",
                        email:    "Formato de correo no válido."
                    },
                    telefono:   { required: "Ingresa tu teléfono." },
                    contrasennia: {
                        required:  "Ingresa una contraseña.",
                        minlength: "Mínimo 8 caracteres."
                    },
                    recontrasennia: {
                        required: "Confirma la contraseña."
                    },
                },
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('error-message');
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>

</body>
</html>x