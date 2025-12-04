<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña | Asesorias Cetis 17</title>

    {{-- Usaremos el mismo estilo CSS que el formulario de registro --}}
    <link rel="stylesheet" href="{{ asset('css/estiloregistro.css') }}"> 
</head>
<body>
    <div class="fondo">
        <div class="register-box">
            <div class="logo">
                {{-- Logo de AlmacenPLUS --}}
                <img src="{{ asset('img/cetis.png') }}" alt="Logo Asesoria" />
            </div>
            <h2>Asesorias Cetis 17</h2>
            <p class="box-msg">¿Olvidaste tu contraseña? <br>Aquí puedes recuperarla fácilmente.</p>

           <form omethod="post">
            @csrf
                
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" placeholder="Ingresa tu Correo Electrónico">
                
                {{-- En este caso, solo hay un botón --}}
                <button type="submit">SOLICITAR NUEVA CONTRASEÑA</button>
                
                <div class="links">
                    <a href="/login">Iniciar Sesión</a>
                </div>
            </form>
        </div>
    </div>
    
    {{-- Se eliminan todos los scripts de AdminLTE/jQuery que no son necesarios para esta vista --}}
</body>
</html>