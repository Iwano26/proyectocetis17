<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - {{ $curso->nombre_curso }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --color-sep: #800020; /* Color guinda oficial */
        }
        .sidebar-link.active {
            background-color: var(--color-sep) !important;
            border-color: var(--color-sep) !important;
        }
        .card-custom {
            border: 2px solid var(--color-sep);
        }
    </style>
</head>
<body class="bg-light">

    <div class="container-fluid py-4">
        <div class="card card-custom mb-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-uppercase fw-bold m-0">{{ $curso->nombre_curso }}</h2>
                        <p class="text-muted mb-0">Impartido por: <strong>{{ $curso->correo_persona }}</strong></p>
                        <small class="text-muted">Materia: {{ $curso->materia }}</small>
                    </div>
                    <span class="badge bg-success px-3 py-2">ESTADO: ACTIVO</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="list-group shadow-sm fw-bold">
                    <a href="{{ route('cursos.show', $curso->id_curso) }}" class="list-group-item list-group-item-action py-3">
                        <i class="bi bi-info-circle-fill me-2 text-secondary"></i> INFORMACIÓN
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3">
                        <i class="bi bi-list-task me-2 text-secondary"></i> ACTIVIDADES
                    </a>
                    <a href="{{ route('foro.index', $curso->id_curso) }}" class="list-group-item list-group-item-action py-3">
                        <i class="bi bi-chat-dots-fill me-2 text-secondary"></i> FORO
                    </a>
                    <a href="{{ route('reportes.index', $curso->id_curso) }}" class="list-group-item list-group-item-action active sidebar-link py-3 text-white">
                        <i class="bi bi-file-earmark-pdf-fill me-2"></i> REPORTES
                    </a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color: var(--color-sep);">Listado de Estudiantes</h5>
                        <a href="{{ route('reportes.pdf', $curso->id_curso) }}" class="btn btn-dark btn-sm shadow">
                            <i class="bi bi-filetype-pdf me-2"></i>DESCARGAR REPORTE MENSUAL
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th>NOMBRE DEL ESTUDIANTE</th>
                                        <th>CORREO ELECTRÓNICO</th>
                                        <th>GRUPO</th>
                                        <th class="text-center">REGISTRO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($estudiantes as $inscrito)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center me-3" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    {{ substr($inscrito->estudiante->nombre ?? 'E', 0, 1) }}
                                                </div>
                                                <span class="fw-semibold text-dark">{{ $inscrito->estudiante->nombre ?? 'Estudiante' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-muted">{{ $inscrito->correo_estudiante }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ $inscrito->estudiante->grupo ?? 'S/G' }}</span></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary border-0" title="Editar Bitácora">
                                                <i class="bi bi-pencil-square fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">No hay estudiantes inscritos en este curso de asesoría.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>