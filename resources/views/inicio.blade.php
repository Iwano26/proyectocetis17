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
            <form id="loginForm" method="POST" action="servidor/iniciosession.php">
                <label for="usuario">Usuario </label>
                <input type="text" id="correo" name="correo" placeholder="Ingresa tu usuario">

                <label for="password">Contraseña </label>
                <input type="text" id="contraseña" name="contraseña" placeholder="Ingresa tu contraseña">

                <button type="submit">ACCESO</button>
                <div class="links">
                    <a href="#">¿No tienes una cuenta?</a>
                    <a href="#">¿Se te olvidó tu contraseña?</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function(e) {
            const correo = document.getElementById("correo").value.trim();
            const contraseña = document.getElementById("contraseña").value;

            const regexCorreo = /^[a-zA-Z0-9._%+-]+@cetis17\.edu\.mx$/;
            const regexPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

            if (!regexCorreo.test(correo)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Correo inválido',
                    text: 'Debes ingresar un correo institucional con dominio @cetis17.edu.mx',
                });
                return;
            }

            if (!regexPassword.test(contraseña)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Contraseña inválida',
                    html: 'Tu contraseña debe tener como mínimo:<br>' +
                          '- 8 caracteres<br>' +
                          '- Una letra mayúscula<br>' +
                          '- Una letra minúscula<br>' +
                          '- Un número',
                });
                return;
            }
        });
    </script>
</body>
</html>
