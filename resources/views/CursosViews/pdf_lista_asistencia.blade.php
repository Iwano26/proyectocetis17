<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1.2cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9px; color: #222; }

        .header { text-align: center; margin-bottom: 14px; border-bottom: 3px solid #8C001A; padding-bottom: 10px; }
        .header h1 { color: #8C001A; margin: 0 0 2px; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { margin: 0 0 2px; font-size: 10px; font-weight: normal; color: #444; }
        .header p  { margin: 1px 0; font-size: 8px; color: #666; }

        .info-box { background: #f9f9f9; border: 1px solid #ddd; border-left: 4px solid #8C001A; padding: 7px 12px; margin-bottom: 14px; border-radius: 4px; }
        .info-box table { width: 100%; border: none; }
        .info-box td { border: none; padding: 2px 8px; font-size: 9px; }
        .info-box .label { font-weight: bold; color: #8C001A; width: 120px; }

        .seccion-titulo { background: #8C001A; color: white; padding: 5px 10px; font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 4px; margin-bottom: 6px; margin-top: 14px; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        table.data th { background: #f2f2f2; border: 1px solid #ccc; padding: 5px 6px; text-align: center; font-size: 8px; text-transform: uppercase; color: #555; font-weight: bold; }
        table.data td { border: 1px solid #ddd; padding: 5px 6px; font-size: 8.5px; vertical-align: middle; }
        table.data tbody tr:nth-child(even) { background: #fafafa; }

        .asesoria-header { background: #fff0f2; border: 1px solid #f5c0c8; border-radius: 4px; padding: 5px 10px; margin-top: 12px; margin-bottom: 4px; }
        .asesoria-header strong { color: #8C001A; font-size: 9.5px; }
        .asesoria-header span { color: #555; font-size: 8.5px; margin-left: 10px; }

        .text-center { text-align: center; }
        .text-left   { text-align: left; }
        .firma-col   { width: 100px; }

        .sin-asistentes { color: #999; font-style: italic; text-align: center; padding: 8px; font-size: 8px; }

        .pie { margin-top: 20px; border-top: 1px solid #ddd; padding-top: 6px; text-align: center; font-size: 7.5px; color: #999; }

        .firma-asesor { margin-top: 35px; text-align: center; }
        .firma-linea  { display: inline-block; width: 200px; border-top: 1px solid #333; padding-top: 4px; font-size: 8.5px; }
    </style>
</head>
<body>

    {{-- ENCABEZADO --}}
    <div class="header">
        <h1>Centro de Estudios Tecnológicos Industrial y de Servicios No. 17</h1>
        <h2>Lista de Asistencia — Curso de Asesorías</h2>
        <p>Generado el {{ date('d/m/Y') }}</p>
    </div>

    {{-- DATOS DEL CURSO --}}
    <div class="info-box">
        <table>
            <tr>
                <td class="label">Curso:</td>
                <td>{{ $curso->nombre_curso }}</td>
                <td class="label">Materia:</td>
                <td>{{ $curso->materia }}</td>
            </tr>
            <tr>
                <td class="label">Asesor:</td>
                <td>{{ $curso->nombre_asesor ?? $curso->correo_persona }}</td>
                <td class="label">Estado:</td>
                <td>{{ $curso->estado }}</td>
            </tr>
        </table>
    </div>

    {{-- ASESORÍAS CON SUS ASISTENTES --}}
    @forelse($asesorias as $asesoria)

        <div class="asesoria-header">
            <strong>{{ $asesoria->nombre_evento }}</strong>
            <span>Fecha: {{ \Carbon\Carbon::parse($asesoria->fecha_asesoria)->format('d/m/Y') }}</span>
            <span>Hora: {{ \Carbon\Carbon::parse($asesoria->hora_inicio)->format('H:i') }}{{ $asesoria->hora_fin ? ' — ' . \Carbon\Carbon::parse($asesoria->hora_fin)->format('H:i') : '' }}</span>
            @if($asesoria->lugar)
                <span>Lugar: {{ $asesoria->lugar }}</span>
            @endif
            <span style="float:right; background:#8C001A; color:white; padding:1px 8px; border-radius:10px;">
                {{ $asesoria->asistentes->count() }} asistente(s)
            </span>
        </div>

        @if($asesoria->asistentes->count() > 0)
            <table class="data">
                <thead>
                    <tr>
                        <th style="width:25px">#</th>
                        <th>Nombre Completo del Alumno</th>
                        <th>Correo</th>
                        <th>Tema Tratado</th>
                        <th class="firma-col">Firma del Alumno</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asesoria->asistentes as $i => $asistente)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-left">{{ $asistente->nombre_completo }}</td>
                        <td class="text-center">{{ $asistente->correo }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="sin-asistentes">Sin asistentes registrados en esta asesoría.</p>
        @endif

    @empty
        <p style="text-align:center; color:#999; margin-top:20px;">No hay asesorías registradas para este curso.</p>
    @endforelse

    {{-- FIRMA DEL ASESOR --}}
    <div class="firma-asesor">
        <div class="firma-linea">
            {{ $curso->nombre_asesor ?? $curso->correo_persona }}<br>
            <strong>Firma del Asesor</strong>
        </div>
    </div>

    <div class="pie">
        CETIS 17 — Sistema de Asesorías © {{ date('Y') }} — Documento generado automáticamente
    </div>

</body>
</html>