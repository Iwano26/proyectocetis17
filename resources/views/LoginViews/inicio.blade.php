<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio de Sesión | CETIS 17</title>
    <link rel="stylesheet" href="{{ asset('css/estiloindex.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    {{-- Mensajes del servidor --}}
    @if($errors->has('login'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Acceso denegado',
                    text: @json($errors->first('login')),
                    confirmButtonColor: '#8C001A',
                    confirmButtonText: 'Intentar de nuevo'
                });
            });
        </script>
    @endif

    @if(session('mensaje'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Aviso',
                    text: @json(session('mensaje')),
                    confirmButtonColor: '#8C001A',
                });
            });
        </script>
    @endif

    <div class="fondo">
        <div class="login-box">

            <div class="logo">
                <img src="{{ asset('img/cetis.png') }}" alt="Logo CETIS 17" />
            </div>

            <h2>Asesorías CETIS 17</h2>
            <p class="subtitulo">Plataforma de Refuerzo Académico</p>

            <form id="loginForm" method="POST" action="{{ route('login.post') }}">
                @csrf

                <label for="correo">Correo Electrónico</label>
                <input type="text" id="correo" name="correo"
                    placeholder="usuario@cetis17.edu.mx"
                    value="{{ old('correo') }}"
                    class="{{ $errors->has('correo') ? 'input-error' : '' }}">
                @error('correo')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="pass">Contraseña</label>
                <input type="password" id="pass" name="pass"
                    placeholder="Ingresa tu contraseña"
                    class="{{ $errors->has('pass') ? 'input-error' : '' }}">
                @error('pass')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <button type="submit">ACCEDER</button>

                <div class="links">
                    <a href="/register">¿No tienes cuenta? Regístrate</a>
                    <a href="/olvido-contrasennia">¿Olvidaste tu contraseña?</a>
                </div>
            </form>

        </div>
    </div>

    <script>
    document.getElementById("loginForm").addEventListener("submit", function(e) {
        const correo = document.getElementById("correo").value.trim();
        const pass   = document.getElementById("pass").value;

        // ══════════════════════════════════════════════════════════════════
        // MODO PRODUCCIÓN: Solo acepta correos institucionales @cetis17.edu.mx
        //const regexCorreo = /^[a-zA-Z0-9._%+-]+@cetis17\.edu\.mx$/;

        // MODO PRUEBAS: Acepta cualquier correo válido
        const regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        // ══════════════════════════════════════════════════════════════════

        function mostrarAlerta(titulo, texto) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: titulo,
                text: texto,
                confirmButtonColor: '#8C001A',
                confirmButtonText: 'Entendido',
            });
        }

        if (correo === "" || pass === "") {
            mostrarAlerta('Campos vacíos', 'Debes ingresar tu correo y contraseña.');
            return;
        }

        if (!regexCorreo.test(correo)) {
            mostrarAlerta(
                'Correo no válido',
                // ── MODO PRODUCCIÓN ──
                'Usa tu correo institucional @cetis17.edu.mx'
                // ── MODO PRUEBAS ──
                // 'Ingresa un correo electrónico válido.'
            );
            return;
        }
    });
    </script>

</body>
</html>