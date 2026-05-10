<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #800020; /* Guinda oficial */
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-weight: bold;
            font-size: 10px;
        }
        .info-section {
            margin-bottom: 15px;
            border-bottom: 1px solid #800020;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
            font-size: 9px;
            text-transform: uppercase;
        }
        /* Ajuste de anchos de columna */
        .col-dia { width: 10%; }
        .col-hora { width: 12%; }
        .col-nombre { width: 28%; }
        .col-grupo { width: 8%; }
        .col-tema { width: 20%; }
        .col-res { width: 22%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>EDUCACIÓN</h1>
        <p>SUBSECRETARÍA DE EDUCACIÓN MEDIA SUPERIOR</p>
        <p>CENTRO DE ESTUDIOS TECNOLÓGICOS industrial y de servicios no. 17</p>
    </div>

    <div class="info-section">
        <p><strong>CURSO DE ASESORÍA:</strong> {{ $curso->nombre_curso }}</p>
        <p><strong>DOCENTE:</strong> {{ $curso->correo_persona }}</p>
        <p><strong>MATERIA:</strong> {{ $curso->materia }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-dia">DÍA</th>
                <th class="col-hora">HORA</th>
                <th class="col-nombre">NOMBRE DEL ESTUDIANTE</th>
                <th class="col-grupo">GRUPO</th>
                <th class="col-tema">TEMA TRATADO</th>
                <th class="col-res">RESULTADOS / COMPROMISOS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registros as $inscrito)
            <tr>
                <td>{{ date('d/m/Y') }}</td>
                <td>{{ $curso->horario ?? '---' }}</td>
                <td style="text-align: left;">{{ $inscrito->estudiante->nombre ?? 'Estudiante' }}</td>
                <td>{{ $inscrito->estudiante->grupo ?? 'S/G' }}</td>
                <td>________________</td>
                <td>________________</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: center;">
        <div style="display: inline-block; width: 200px; border-top: 1px solid #000;">
            <p>Firma del Docente</p>
        </div>
    </div>
</body>
</html>