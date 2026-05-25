<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Examen - Asesorías CETIS 17</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; padding: 20px 0;">

<div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">

    {{-- Header --}}
    <div style="background-color: #1a3a5c; padding: 24px 20px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px;">
        <img src="{{ config('app.url') }}/img/cetis.png"
             alt="CETIS 17"
             style="width:65px; height:65px; border-radius:50%; border:3px solid rgba(255,255,255,0.5); margin-bottom:10px; display:block; margin-left:auto; margin-right:auto;">
        <h2 style="color:#ffffff; margin:0; font-size:1.4rem;">
            📝 Nuevo Examen Publicado
        </h2>
        <p style="color:rgba(255,255,255,0.8); margin:6px 0 0; font-size:0.9rem;">
            Asesorías CETIS 17
        </p>
    </div>

    {{-- Contenido --}}
    <div style="padding: 30px; border-top: 5px solid #1a3a5c;">

        <p style="font-size:1rem;">
            Hola, <strong>{{ $nombreAlumno }}</strong>:
        </p>

        <p>
            Se ha publicado un nuevo examen en tu curso
            <strong style="color:#1a3a5c;">{{ $nombreCurso }}</strong>.
            ¡Prepárate con tiempo!
        </p>

        {{-- Tarjeta info --}}
        <div style="background-color:#f0f4ff; border-left:5px solid #1a3a5c; border-radius:6px; padding:20px; margin:24px 0;">
            <h3 style="color:#1a3a5c; margin:0 0 16px; font-size:1.1rem;">
                {{ $nombreExamen }}
            </h3>
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="padding:6px 0; color:#666; font-size:0.85rem; width:35%;">📅 Fecha</td>
                    <td style="padding:6px 0; font-weight:bold;">
                        {{ \Carbon\Carbon::parse($fechaExamen)->translatedFormat('d \d\e F \d\e Y') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#666; font-size:0.85rem;">🕐 Horario</td>
                    <td style="padding:6px 0; font-weight:bold;">
                        {{ \Carbon\Carbon::parse($horaInicio)->format('h:i A') }}
                        —
                        {{ \Carbon\Carbon::parse($horaFin)->format('h:i A') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#666; font-size:0.85rem;">🔄 Intentos</td>
                    <td style="padding:6px 0; font-weight:bold;">
                        {{ $oportunidades }} oportunidad{{ $oportunidades != 1 ? 'es' : '' }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- Alerta --}}
        <div style="background-color:#fff3cd; border:1px solid #ffc107; border-radius:6px; padding:14px 18px; margin:20px 0;">
            <p style="margin:0; font-size:0.9rem; color:#856404;">
                ⚠️ <strong>¡Estudia con anticipación!</strong> Asegúrate de revisar el material antes de la fecha del examen.
            </p>
        </div>

        {{-- Botón --}}
        <div style="text-align:center; margin:28px 0;">
            <a href="{{ url('/curso/' . $idCurso . '/eventos') }}"
               style="background-color:#1a3a5c; color:#ffffff; padding:14px 32px; text-decoration:none; display:inline-block; border-radius:6px; font-weight:bold; font-size:1rem;">
                Ver Examen
            </a>
        </div>

        <hr style="border:0; border-top:1px solid #eee; margin:20px 0;">
        <p style="font-style:italic; color:#999; font-size:0.85rem;">
            Este correo fue generado automáticamente por el sistema de Asesorías CETIS 17.
        </p>
    </div>

    {{-- Footer --}}
    <div style="background-color:#f4f4f4; padding:15px; text-align:center; font-size:12px; color:#666; border-bottom-left-radius:8px; border-bottom-right-radius:8px; border-top:1px solid #ddd;">
        <p style="margin:0;">Saludos cordiales,<br><strong>El equipo de Asesorías CETIS 17</strong></p>
    </div>

</div>
</body>
</html>