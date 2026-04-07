<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9f9f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; border-radius: 8px; overflow: hidden; border: 1px solid #ddd; }
        .header { background-color: #A71F21; color: white; padding: 30px; text-align: center; }
        .body { padding: 40px; text-align: center; color: #333; }
        .btn { background-color: #A71F21; color: white !important; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; margin-top: 20px; }
        .footer { background: #f1f1f1; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>CETIS 17</h1>
            <p>Sistema de Asesorías Académicas</p>
        </div>
        <div class="body">
            <h2>¡Bienvenido, {{ $nombre }}!</h2>
            <p>Tu cuenta ha sido registrada exitosamente. Para poder ingresar al sistema, es necesario verificar tu identidad haciendo clic en el botón de abajo.</p>
            <a href="{{ route('correo.confirmar', $token) }}" class="btn">ACTIVAR MI CUENTA</a>
        </div>
        <div class="footer">
            Este es un correo automático, por favor no respondas a este mensaje. <br>
            © 2026 San Martín Texmelucan, Puebla.
        </div>
    </div>
</body>
</html>