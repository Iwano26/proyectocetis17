<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Cursos | CETIS 17</title>
    
    <!-- Bootstrap y Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --cetis-primary: #8C001A;
            --cetis-secondary: #004A77; 
            --cetis-light: #f8f9fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cetis-light);
        }
        .navbar-brand, .text-cetis {
            color: var(--cetis-primary) !important;
        }
        .btn-cetis-primary {
            background-color: var(--cetis-primary);
            border-color: var(--cetis-primary);
            color: white;
            border-radius: 0.5rem;
            transition: background-color 0.3s;
        }
        .btn-cetis-primary:hover {
            background-color: #A61E34; 
            border-color: #A61E34;
            color: white;
        }
        .filter-sidebar {
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 20px; 
        }
        .main-content {
            padding-top: 20px;
        }
        .form-check-input:checked {
            background-color: var(--cetis-primary);
            border-color: var(--cetis-primary);
        }
        .course-table thead {
            background-color: var(--cetis-secondary);
            color: white;
        }
        .course-table tbody tr {
            transition: background-color 0.2s;
        }
        .course-table tbody tr:hover {
            background-color: #e0e0e0;
            cursor: pointer;
        }
        /* Colores de badges según estado */
        .bg-activo { background-color: #198754 !important; } /* Verde */
        .bg-inactivo { background-color: #6c757d !important; } /* Gris */
        .bg-completado { background-color: #0d6efd !important; } /* Azul */
    </style>
</head>
<body>

    <!-- Barra de Navegación Simplificada -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <img src="https://placehold.co/32x32/8C001A/ffffff?text=C17" alt="Logo" class="d-inline-block align-text-top rounded-full me-2">
                Catálogo de Cursos
            </a>
            <!-- Solo mostramos enlace a cursos, sin cerrar sesión ni inicio -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold text-cetis" href="principal">Volver</a> 
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="container main-content">
        <h1 class="display-5 fw-bolder mb-4 text-dark text-center">Cursos Disponibles</h1>
        <p class="lead text-muted text-center mb-5">Explora la oferta académica actual registrada en el sistema.</p>

        <div class="row g-4">
            
            <!-- PANEL IZQUIERDO: FILTROS (Estático visualmente por ahora) -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <h5 class="fw-bold mb-3 text-cetis"><i class="bi bi-search me-2"></i> Filtros</h5>
                    
                    <div class="mb-4">
                        <label for="search-input" class="form-label fw-bold small">Buscar Curso</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-input" placeholder="Nombre o materia...">
                            <button class="btn btn-cetis-primary" type="button"><i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>
                    <hr>
                    <h6 class="fw-bold mb-3 text-dark">Materia / Área</h6>
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check-prog">
                            <label class="form-check-label" for="check-prog">Programación</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check-mat">
                            <label class="form-check-label" for="check-mat">Matemáticas</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check-hum">
                            <label class="form-check-label" for="check-hum">Humanidades</label>
                        </div>
                    </div>
                    <button class="btn btn-outline-secondary w-100">Limpiar</button>
                </div>
            </div>

            <!-- PANEL DERECHO: TABLA DE DATOS -->
            <div class="col-lg-9">
                <div class="card p-3 shadow-sm border-0 rounded-4">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover course-table text-center align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre del Curso</th>
                                    <th scope="col">Materia</th>
                                    <th scope="col">Inicio</th>
                                    <th scope="col">Fin</th>
                                    <th scope="col">Hrs. Disp.</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Info</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Bucle Blade para mostrar datos reales --}}
                                @forelse ($cursos as $curso)
                                    @php
                                        $badgeClass = match ($curso->estado) {
                                            'ACTIVO' => 'bg-activo',
                                            'INACTIVO' => 'bg-inactivo',
                                            'COMPLETADO' => 'bg-completado',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="fw-bold text-start">{{ $curso->nombre_curso }}</td>
                                        <td>{{ $curso->materia }}</td>
                                        <td>{{ $curso->fecha_inicio }}</td>
                                        <td>{{ $curso->fecha_fin }}</td>
                                        <td>{{ $curso->horas_disponibles }}</td>
                                        <td>
                                            <span class="badge {{ $badgeClass }} rounded-pill">{{ $curso->estado }}</span>
                                        </td>
                                        <td>
                                            <!-- Botón que activa el modal con detalles -->
                                            <button class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#cursoModal{{ $curso->id_curso }}">
                                                <i class="bi bi-info-circle"></i>
                                            </button>

                                            <!-- Modal Individual por Curso -->
                                            <div class="modal fade text-start" id="cursoModal{{ $curso->id_curso }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-cetis-primary text-white">
                                                            <h5 class="modal-title">{{ $curso->nombre_curso }}</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>Descripción:</strong><br> {{ $curso->descripcion ?? 'Sin descripción disponible.' }}</p>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-6"><strong>Materia:</strong> {{ $curso->materia }}</div>
                                                                <div class="col-6"><strong>Horas:</strong> {{ $curso->horas_disponibles }}</div>
                                                                <div class="col-6 mt-2"><strong>Inicio:</strong> {{ $curso->fecha_inicio }}</div>
                                                                <div class="col-6 mt-2"><strong>Fin:</strong> {{ $curso->fecha_fin }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No hay cursos registrados en este momento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <small>&copy; 2024 CETIS 17 | Catálogo Académico.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>