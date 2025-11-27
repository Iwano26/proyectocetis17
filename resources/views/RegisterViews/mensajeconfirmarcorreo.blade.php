<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Correo Electrónico</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>¡Bienvenido a Almacén Plus!</h2>
    <p>Estimado/a {{ $nombreCompleto }},</p>
    <p>Gracias por registrarte en nuestro sistema. Para activar tu cuenta, por favor confirma tu dirección de correo electrónico haciendo clic en el siguiente botón:</p>
    <a href="{{ env('APP_URL') }}/register/{{$correo}}/confirmar/" style="background-color: #007bff; color: #ffffff; padding: 14px 25px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px; margin-top: 20px;">Confirmar Correo Electrónico</a>
    <p>Si el botón no funciona, puedes copiar y pegar el siguiente enlace en tu navegador:</p>
    <p><a href="{{ env('APP_URL') }}/register/{{$correo}}/confirmar/">{{ env('APP_URL') }}/register/{{$correo}}/confirmar/</a></p>
    <p>Si no te registraste en Almacén Plus, por favor ignora este correo.</p>
    <p>Saludos cordiales,<br>El equipo de Almacén Plus</p>
</div>
</body>
</html>
