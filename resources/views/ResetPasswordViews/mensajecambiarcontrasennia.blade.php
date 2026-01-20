<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña - Asesorías CETIS 17</title>
    <style>
        /* CSS INLINE es la mejor práctica para correos */
        .header {
            background-color: #A31F2E; /* Rojo institucional */
            padding: 20px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; padding: 20px 0;">

<div class="container" style="background-color: #fff; padding: 0; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); max-width: 600px; margin: 0 auto;">

    <div class="header"> 
        <h2 style="color: #fafafa; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;">Restablecimiento de Contraseña</h2>      
    </div>
    
    <div style="padding: 30px; border-top: 5px solid #A31F2E;"> <h2 style="color: #A31F2E; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;">Solicitud de Restablecimiento de Contraseña</h2>
        
        <p>Estimado/a <strong style="color: #000;">{{ $nombreCompleto }}</strong>,</p>
        
        <p>Hemos recibido una solicitud para restablecer la contraseña de su cuenta de asesorías CETIS 17. Para completar el proceso de forma segura, haga clic en el botón a continuación:</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="cetis17.test/cambiarpass" 
               style="background-color: #A31F2E; color: #ffffff; padding: 14px 25px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px; font-weight: bold; font-size: 16px;">
                Restablecer Contraseña
            </a>
        </div>
        
        <p style="font-size: 14px; color: #666;">Si el botón no funciona o no se visualiza correctamente, puede copiar y pegar el siguiente enlace en la barra de direcciones de su navegador:</p>
        <p style="font-size: 12px; word-break: break-all;"><a href="cetis17.test/cambiarpass">{{ env('APP_URL') }}/cambiarpass</a></p>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        
        <p style="font-style: italic; color: #999;">
            **Nota de Seguridad:** Si usted no solicitó este cambio de contraseña, por favor, ignore este correo electrónico. Su contraseña actual se mantendrá segura y sin cambios.
        </p>
    </div>

    <div class="footer">
        <p style="margin: 0;">Saludos cordiales,<br>El equipo de asesorías CETIS 17.</p>
        <p style="margin-top: 5px;">Sistema de asesorias - CETIS No. 17</p>
    </div>
</div>

</body>
</html>