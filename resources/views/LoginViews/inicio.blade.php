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
    <label for="correo">Correo </label>
    <input type="text" id="correo" name="correo" placeholder="Ingresa tu correo institucional" value="{{ old('correo') }}">
    @error('correo') <small style="color:red;">{{ $message }}</small> @enderror

    <label for="pass">Contraseña </label>
    <input type="password" id="pass" name="pass" placeholder="Ingresa tu contraseña">
    @error('pass') <small style="color:red;">{{ $message }}</small> @enderror

    <button type="submit">ACCESO</button>
    <div class="links">
        <a href="/register">¿No tienes una cuenta?</a>
        <a href="/resetpass">¿Se te olvidó tu contraseña?</a>
    </div>
</form>
    </div>

    <script>
document.getElementById("loginForm").addEventListener("submit", function(e) {
    const correo = document.getElementById("correo").value.trim();
    const pass = document.getElementById("pass").value;

    const regexCorreo = /^[a-zA-Z0-9._%+-]+@cetis17\.edu\.mx$/;
    
    function mostrarAlerta(titulo, texto) {
        Swal.fire({
            icon: 'error', 
            title: titulo,
            text: texto,
            confirmButtonText: 'OK',
        });
    }

    // 1. Solo validamos que no estén vacíos
    if (correo === "" || pass === "") {
        e.preventDefault();
        mostrarAlerta('Campos Vacíos', 'Debes ingresar tu correo y contraseña.');
        return;
    }

    // 2. Validar dominio institucional
    if (!regexCorreo.test(correo)) {
        e.preventDefault();
        mostrarAlerta('Correo inválido', 'Usa tu correo institucional @cetis17.edu.mx');
        return;
    }
    
    // ELIMINAMOS la validación regexPassword aquí. 
    // Deja que el servidor (Laravel) decida si la contraseña es correcta o no.
});
</script>
</body>
</html>