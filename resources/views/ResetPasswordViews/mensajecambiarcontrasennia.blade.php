<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña - Asesorías CETIS 17</title>
    <style>
        .header { background-color: #A31F2E; padding: 20px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px; }
        .footer { background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #666; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; border-top: 1px solid #ddd; }
    </style>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; padding: 20px 0;">
<div class="container" style="background-color: #fff; padding: 0; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); max-width: 600px; margin: 0 auto;">
    <div class="header"> 
        <h2 style="color: #fafafa; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;">Restablecimiento de Contraseña</h2>      
    </div>
    <div style="padding: 30px; border-top: 5px solid #A31F2E;">
        <h2 style="color: #A31F2E; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;">Solicitud de Cambio</h2>
        <p>Estimado/a <strong style="color: #000;">{{ $nombreCompleto }}</strong>,</p>
        <p>Haz clic en el botón a continuación para restablecer tu contraseña de asesorías CETIS 17:</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('password.reset', ['token' => $token]) }}" 
               style="background-color: #A31F2E; color: #ffffff; padding: 14px 25px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px; font-weight: bold; font-size: 16px;">
                Restablecer Contraseña
            </a>
        </div>
        <p style="font-size: 12px; color: #666; word-break: break-all;">
            O copia este enlace: <br>
            {{ route('password.reset', ['token' => $token]) }}
        </p>
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-style: italic; color: #999;">Nota: Este enlace expira en 15 minutos.</p>
    </div>
    <div class="footer">
        <p style="margin: 0;">Saludos cordiales,<br>El equipo de asesorías CETIS 17.</p>
    </div>
</div>
</body>
</html>