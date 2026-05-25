<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Asesoría - Asesorías CETIS 17</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; padding: 20px 0;">

<div style="background-color: #fff; padding: 0; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">

    {{-- Header --}}
    <div style="background-color: #8C001A; padding: 24px 20px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px;">
        <h2 style="color: #ffffff; margin: 0; font-size: 1.4rem;">
            📚 Nueva Asesoría Disponible
        </h2>
        <p style="color: rgba(255,255,255,0.8); margin: 6px 0 0; font-size: 0.9rem;">
            Asesorías CETIS 17
        </p>
    </div>

    {{-- Contenido --}}
    <div style="padding: 30px; border-top: 5px solid #8C001A;">

        <p style="font-size: 1rem;">
            Hola, <strong style="color: #000;">{{ $nombreAlumno }}</strong>:
        </p>

        <p>
            Se ha publicado una nueva asesoría en tu curso
            <strong style="color: #8C001A;">{{ $nombreCurso }}</strong>.
            ¡No te la pierdas!
        </p>

        {{-- Tarjeta de info --}}
        <div style="background-color: #f9f9f9; border-left: 5px solid #8C001A; border-radius: 6px; padding: 20px; margin: 24px 0;">
            <h3 style="color: #8C001A; margin: 0 0 16px; font-size: 1.1rem;">
                {{ $nombreAsesoria }}
            </h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 6px 0; color: #666; font-size: 0.85rem; width: 30%;">
                        📅 Fecha
                    </td>
                    <td style="padding: 6px 0; font-weight: bold;">
                        {{ \Carbon\Carbon::parse($fechaAsesoria)->translatedFormat('d \d\e F \d\e Y') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #666; font-size: 0.85rem;">
                        🕐 Horario
                    </td>
                    <td style="padding: 6px 0; font-weight: bold;">
                        {{ \Carbon\Carbon::parse($horaInicio)->format('h:i A') }}
                        —
                        {{ \Carbon\Carbon::parse($horaFin)->format('h:i A') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #666; font-size: 0.85rem;">
                        📍 Lugar
                    </td>
                    <td style="padding: 6px 0; font-weight: bold;">
                        {{ $lugar ?? 'Por confirmar' }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- Botón --}}
        <div style="text-align: center; margin: 28px 0;">
            <a href="{{ url('/curso/' . $idCurso . '/eventos') }}"
               style="background-color: #8C001A; color: #ffffff; padding: 14px 32px; text-decoration: none; display: inline-block; border-radius: 6px; font-weight: bold; font-size: 1rem;">
                Ver Asesoría
            </a>
        </div>

        <p style="font-size: 0.85rem; color: #888; margin-top: 24px;">
            Si no deseas recibir estos correos, ignora este mensaje.
        </p>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

        <p style="font-style: italic; color: #999; font-size: 0.85rem;">
            Este correo fue generado automáticamente por el sistema de Asesorías CETIS 17.
        </p>
    </div>

    {{-- Footer --}}
    <div style="background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #666; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; border-top: 1px solid #ddd;">
        <p style="margin: 0;">
            Saludos cordiales,<br>
            <strong>El equipo de Asesorías CETIS 17</strong>
        </p>
    </div>

</div>
</body>
</html>