<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1.2cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #222; }

        /* ── ENCABEZADO ── */
        .header { text-align: center; margin-bottom: 16px; border-bottom: 3px solid #8C001A; padding-bottom: 10px; }
        .header h1 { color: #8C001A; margin: 0 0 2px; font-size: 16px; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { margin: 0 0 2px; font-size: 11px; font-weight: normal; color: #444; }
        .header p  { margin: 1px 0; font-size: 9px; color: #666; }

        /* ── INFO DEL CURSO ── */
        .info-box { background: #f9f9f9; border: 1px solid #ddd; border-left: 4px solid #8C001A; padding: 8px 12px; margin-bottom: 14px; border-radius: 4px; }
        .info-box table { width: 100%; border: none; }
        .info-box td { border: none; padding: 2px 8px; font-size: 10px; }
        .info-box .label { font-weight: bold; color: #8C001A; width: 130px; }

        /* ── ESTADÍSTICAS ── */
        .stats-row { width: 100%; margin-bottom: 14px; }
        .stat-box { display: inline-block; width: 22%; background: #8C001A; color: white; border-radius: 6px; padding: 8px; text-align: center; margin-right: 2%; vertical-align: top; }
        .stat-box .numero { font-size: 22px; font-weight: bold; display: block; }
        .stat-box .etiqueta { font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9; }

        /* ── TÍTULOS DE SECCIÓN ── */
        .seccion-titulo { background: #8C001A; color: white; padding: 5px 10px; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 4px; margin-bottom: 6px; margin-top: 14px; }

        /* ── TABLAS ── */
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.data th { background: #f2f2f2; border: 1px solid #ccc; padding: 5px 7px; text-align: center; font-size: 8.5px; text-transform: uppercase; color: #555; font-weight: bold; }
        table.data td { border: 1px solid #ddd; padding: 5px 7px; font-size: 9px; vertical-align: middle; }
        table.data tbody tr:nth-child(even) { background: #fafafa; }
        .text-center { text-align: center; }
        .text-left   { text-align: left; }

        /* ── BADGES DE ESTADO ── */
        .badge { padding: 2px 7px; border-radius: 10px; font-size: 8px; font-weight: bold; }
        .badge-terminada  { background: #d4edda; color: #155724; }
        .badge-disponible { background: #cce5ff; color: #004085; }
        .badge-cancelada  { background: #f8d7da; color: #721c24; }
        .badge-en-curso   { background: #fff3cd; color: #856404; }

        /* ── FIRMA ── */
        .firma { margin-top: 40px; text-align: center; }
        .firma-linea { display: inline-block; width: 220px; border-top: 1px solid #333; padding-top: 5px; font-size: 9px; }

        /* ── PIE ── */
        .pie { margin-top: 20px; border-top: 1px solid #ddd; padding-top: 6px; text-align: center; font-size: 8px; color: #999; }
    </style>
</head>
<body>

    {{-- ── ENCABEZADO INSTITUCIONAL ── --}}
    <div class="header">
        <h1>Centro de Estudios Tecnológicos Industrial y de Servicios No. 17</h1>
        <h2>Subsecretaría de Educación Media Superior</h2>
        <p>Reporte de Curso de Asesorías — Generado el {{ date('d/m/Y') }}</p>
    </div>

    {{-- ── DATOS DEL CURSO ── --}}
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
            <tr>
                <td class="label">Fecha inicio:</td>
                <td>{{ $curso->fecha_inicio ? \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') : '---' }}</td>
                <td class="label">Fecha fin:</td>
                <td>{{ $curso->fecha_fin ? \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') : '---' }}</td>
            </tr>
        </table>
    </div>

    {{-- ── ESTADÍSTICAS ── --}}
    <div class="seccion-titulo">Resumen Estadístico</div>
    <div class="stats-row">
        <div class="stat-box">
            <span class="numero">{{ $estudiantes->count() }}</span>
            <span class="etiqueta">Estudiantes inscritos</span>
        </div>
        <div class="stat-box">
            <span class="numero">{{ $totalAsesorias }}</span>
            <span class="etiqueta">Total asesorías</span>
        </div>
        <div class="stat-box">
            <span class="numero">{{ $asesoriasTerminadas }}</span>
            <span class="etiqueta">Asesorías terminadas</span>
        </div>
        <div class="stat-box">
            <span class="numero">{{ $promedioAsistencia }}</span>
            <span class="etiqueta">Total asistencias registradas</span>
        </div>
    </div>

    {{-- ── GRÁFICA DE ASISTENCIA POR ASESORÍA ── --}}
    <div class="seccion-titulo">Asistencia por Asesoría</div>

    <table style="width:100%; border:none; margin-bottom:14px;">
        @foreach($asesorias as $asesoria)
        @php
            $total    = $asesoria->total_inscritos > 0 ? $asesoria->total_inscritos : 1;
            $pct      = round(($asesoria->total_asistieron / $total) * 100);
            $barWidth = max(1, $pct); // mínimo 1% para que se vea la barra
            $color    = $pct >= 75 ? '#2d8a4e' : ($pct >= 40 ? '#c87d00' : '#8C001A');
        @endphp
        <tr>
            <td style="width:160px; font-size:8px; color:#333; padding:3px 6px 3px 0; border:none; vertical-align:middle;">
                {{ \Illuminate\Support\Str::limit($asesoria->nombre_evento, 28) }}
            </td>
            <td style="border:none; padding:3px 0; vertical-align:middle;">
                <div style="background:#eee; border-radius:4px; height:13px; width:100%;">
                    <div style="background:{{ $color }}; width:{{ $barWidth }}%; height:13px; border-radius:4px;"></div>
                </div>
            </td>
            <td style="width:80px; font-size:8px; color:#555; padding:3px 0 3px 8px; border:none; vertical-align:middle; white-space:nowrap;">
                {{ $asesoria->total_asistieron }}/{{ $asesoria->total_inscritos }} ({{ $pct }}%)
            </td>
        </tr>
        @endforeach
    </table>

    {{-- Leyenda de colores --}}
    <div style="font-size:7.5px; color:#777; margin-bottom:12px;">
        <span style="display:inline-block; width:10px; height:10px; background:#2d8a4e; border-radius:2px; margin-right:4px;"></span> Mayor a 75% &nbsp;&nbsp;
        <span style="display:inline-block; width:10px; height:10px; background:#c87d00; border-radius:2px; margin-right:4px;"></span> Entre 40% y 74% &nbsp;&nbsp;
        <span style="display:inline-block; width:10px; height:10px; background:#8C001A; border-radius:2px; margin-right:4px;"></span> Menor a 40%
    </div>

    {{-- ── LISTA DE ESTUDIANTES ── --}}
    <div class="seccion-titulo">Estudiantes Inscritos</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width:30px">#</th>
                <th>Nombre del Estudiante</th>
                <th>Correo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estudiantes as $i => $inscrito)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td class="text-left">{{ $inscrito->estudiante->nombre ?? 'Sin nombre' }}</td>
                <td class="text-center">{{ $inscrito->correo_estudiante }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center" style="color:#999; padding:10px;">Sin estudiantes inscritos</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── ASESORÍAS REALIZADAS ── --}}
    <div class="seccion-titulo">Asesorías del Curso</div>
    <table class="data">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Hora inicio</th>
                <th>Hora fin</th>
                <th>Lugar</th>
                <th>Estado</th>
                <th>Asistieron</th>
                <th>Inscritos</th>
            </tr>
        </thead>
        <tbody>
            @forelse($asesorias as $i => $asesoria)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td class="text-left">{{ $asesoria->nombre_evento }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($asesoria->fecha_asesoria)->format('d/m/Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($asesoria->hora_inicio)->format('H:i') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($asesoria->hora_fin)->format('H:i') }}</td>
                <td class="text-center">{{ $asesoria->lugar ?? '---' }}</td>
                <td class="text-center">
                    @php
                        $estado = strtolower($asesoria->estado);
                        $clase = match($estado) {
                            'terminada'  => 'badge-terminada',
                            'disponible' => 'badge-disponible',
                            'cancelada'  => 'badge-cancelada',
                            default      => 'badge-en-curso'
                        };
                    @endphp
                    <span class="badge {{ $clase }}">{{ $asesoria->estado }}</span>
                </td>
                <td class="text-center">{{ $asesoria->total_asistieron }}</td>
                <td class="text-center">{{ $asesoria->total_inscritos }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="color:#999; padding:10px;">Sin asesorías registradas</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── FIRMA ── --}}
    <div class="firma">
        <div class="firma-linea">
            {{ $curso->nombre_asesor ?? $curso->correo_persona }}<br>
            <strong>Firma del Asesor</strong>
        </div>
    </div>

    {{-- ── PIE DE PÁGINA ── --}}
    <div class="pie">
        CETIS 17 — Sistema de Asesorías © {{ date('Y') }} — Documento generado automáticamente
    </div>

</body>
</html>