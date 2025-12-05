<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/estiloindex.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="fondo">
        <div class="login-box">
            <div class="logo">
                <img src="{{ asset('img/cetis.png') }}" alt="Logo Escuela" />
            </div>
            <h2>Asesorias CETIS 17</h2>
            <div>
                @if ($errors->has('login'))
                    <p style="color:red;">{{ $errors->first('login') }}</p>
                @endif
            </div>
            <form id="loginForm" method="POST" action="{{ route('login.post') }}">
                @csrf
                <label for="usuario">Correo </label>
                <input type="text" id="correo" name="correo" placeholder="Ingresa tu correo institucional">
                @error('correo') <small style="color:red;">{{ $message }}</small> @enderror

                <label for="password">Contraseña </label>
                <input type="password" id="pass" name="pass" placeholder="Ingresa tu contraseña">
                @error('pass') <small style="color:red;">{{ $message }}</small> @enderror

                <button type="submit">ACCESO</button>
                <div class="links">
                    <a href="/register">¿No tienes una cuenta?</a>
                    <a href="/resetpass">¿Se te olvidó tu contraseña?</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.getElementById("loginForm").addEventListener("submit", function(e) {
        const correo = document.getElementById("correo").value.trim();
        const pass = document.getElementById("pass").value;

        const regexCorreo = /^[a-zA-Z0-9._%+-]+@cetis17\.edu\.mx$/;
        const regexPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/; 
        
        // Función para mostrar la alerta con el estilo solicitado
        function mostrarAlerta(titulo, texto) {
            Swal.fire({
                icon: 'error', 
                title: titulo,
                text: texto,
                confirmButtonText: 'OK',
            });
        }

        // --- 1. Alerta de Campos Vacíos ---
        if (correo === "" || pass === "") {
            e.preventDefault();
            mostrarAlerta('Campos Vacíos', 'Debes ingresar tu correo y contraseña.');
            return;
        }

        // --- 2. Alerta de Correo Inválido (Dominio @cetis17.edu.mx) ---
        if (!regexCorreo.test(correo)) {
            e.preventDefault();
            mostrarAlerta('Correo inválido', 'Debes ingresar un correo institucional con dominio @cetis17.edu.mx');
            return;
        }
        
        // --- 3. Alerta de Contraseña Inválida (Simula 'Contraseña incorrecta') ---
        if (!regexPassword.test(pass)) {
            e.preventDefault();
            mostrarAlerta('Error de Acceso', 'Correo o contraseña incorrecto.');
            return;
        }

        // Si el código llega a este punto, significa que todas las validaciones de JavaScript pasaron.
        // El formulario se enviará automáticamente (comportamiento por defecto) al servidor,
        // a la ruta definida en action="{{ route('login.post') }}" y
        // si el servidor valida las credenciales, te redirigirá a la pantalla principal.

        // Por lo tanto, NO NECESITAS AÑADIR código aquí, ya que el 'submit' sigue su curso normal.

    });
</script>
</body>
</html>