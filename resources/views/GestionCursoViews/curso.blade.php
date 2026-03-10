<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Cursos | CETIS 17</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --cetis-primary: #8C001A; /* Rojo institucional */
            --cetis-light: #f8f9fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cetis-light);
        }
        .navbar-cetis { background-color: var(--cetis-primary); }
        .btn-cetis-primary {
            background-color: var(--cetis-primary);
            border-color: var(--cetis-primary);
            color: white;
            transition: background-color 0.3s;
        }
        .btn-cetis-primary:hover {
            background-color: #A61E34;
            border-color: #A61E34;
            color: white;
        }
        .card-header-cetis {
            background-color: var(--cetis-primary) !important;
            color: white;
            font-weight: 600;
        }
        .error-message {
            color: var(--cetis-primary);
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        .was-validated input:invalid, .was-validated select:invalid,
        .is-invalid:not(form) {
            border-color: var(--cetis-primary) !important;
        }
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.03);
        }
        .main-content {
            padding-top: 80px; 
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-cetis fixed-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-gear-fill me-2"></i> Panel de Administración
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('gestioncurso.index') }}"><i class="bi bi-book-fill me-1"></i> Cursos</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="/principal"><i class="bi bi-box-arrow-right me-1"></i> Inicio</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="main-content container mt-4 mb-5">
        <h2 class="text-center mb-5 fw-bolder" style="color: var(--cetis-primary);">
            <i class="bi bi-journal-bookmark-fill me-2"></i> Gestión de Cursos
        </h2>
        
        @if(session('mensaje'))
        <div class="alert alert-{{ (session('sessionInsertado') == 'true' || session('sessionEliminado') == 'true') ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
            <i class="bi {{ (session('sessionInsertado') == 'true') ? 'bi-check-circle-fill' : 'bi-info-circle-fill' }} me-2"></i>
            {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-12 col-lg-5 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-header card-header-cetis">
                        <span id="formTitle"><i class="bi bi-file-earmark-plus-fill me-1"></i> Registro de Curso</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('gestioncurso.store') }}" id="registroForm" class="needs-validation" novalidate>
                            @csrf 
                            <input type="hidden" name="_method" value="POST" id="methodField">
                            <input type="hidden" name="id_curso" id="id_cursoField">

                            <div class="mb-3">
                                <label for="correo_persona" class="form-label">Correo Responsable</label>
                                <input type="email" name="correo_persona" id="correo_persona" placeholder="ejemplo@correo.com" class="form-control" value="{{ old('correo_persona') }}" maxlength="150" required>
                            </div>

                            <div class="mb-3">
                                <label for="nombre_curso" class="form-label">Nombre del Curso</label>
                                <input type="text" name="nombre_curso" id="nombre_curso" placeholder="Ej: Programación Web" class="form-control" value="{{ old('nombre_curso') }}" maxlength="255" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="materia" class="form-label">Materia</label>
                                <input type="text" name="materia" id="materia" placeholder="Ej: Tecnologías de la Info." class="form-control" value="{{ old('materia') }}" maxlength="100" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="horas_disponibles" class="form-label">Horas Disponibles</label>
                                <input type="number" name="horas_disponibles" id="horas_disponibles" class="form-control" value="{{ old('horas_disponibles') }}" min="1" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select name="estado" id="estado" class="form-select" required>
                                    <option value="" disabled selected>Seleccione un estado</option>
                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>
                                    <option value="COMPLETADO">COMPLETADO</option>
                                </select>
                            </div>

                            <div class="text-end d-flex justify-content-end align-items-center mt-4">
                                <button type="button" class="btn btn-secondary me-2 d-none" id="cancelarEdicionBtn" onclick="cancelarEdicion()">
                                    <i class="bi bi-x-circle-fill me-1"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-cetis-primary" id="submitBtn">
                                    <i class="bi bi-send-fill me-1"></i> Registrar Curso
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-7">
                <div class="card shadow-lg border-0">
                    <div class="card-header card-header-cetis">
                        <i class="bi bi-list-check me-1"></i> Cursos Registrados
                    </div>                  
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped text-center align-middle m-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Materia</th>
                                        <th>Inicio / Fin</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>                                        
                                    </tr>
                                </thead>
                                <tbody> 
                                    @foreach ($cursos ?? [] as $curso) 
                                    <tr>
                                        <td>{{ $curso->id_curso }}</td>
                                        <td class="text-start">
                                            <div class="fw-bold">{{ $curso->nombre_curso }}</div>
                                            <small class="text-muted">{{ $curso->correo_persona }}</small>
                                        </td>
                                        <td>{{ $curso->materia }}</td>
                                        <td>
                                            <small>{{ $curso->fecha_inicio }}</small><br>
                                            <small>{{ $curso->fecha_fin }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $curso->estado == 'ACTIVO' ? 'bg-success' : ($curso->estado == 'INACTIVO' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                                {{ $curso->estado }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap">
                                            <button class="btn btn-warning btn-sm text-white" 
                                                onclick="iniciarEdicion(event, '{{ route('gestioncurso.update', $curso->id_curso) }}', {{ json_encode($curso) }})">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button class="btn btn-danger btn-sm" 
                                                onclick="mostrarAlertaEliminar(event, '{{ $curso->nombre_curso }}', '{{ route('gestioncurso.destroy', $curso->id_curso) }}')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr> 
                                    @endforeach
                                    @if(empty($cursos) || count($cursos) == 0)
                                    <tr>
                                        <td colspan="6" class="text-muted py-3">No hay registros.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <small>&copy; 2026 CETIS 17 | Administración de Plataforma. | DGETI.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const defaultAction = "{{ route('gestioncurso.store') }}"; 

        function iniciarEdicion(event, updateUrl, data) {
            event.preventDefault();
            
            document.getElementById('id_cursoField').value = data.id_curso;
            document.getElementById('correo_persona').value = data.correo_persona;
            document.getElementById('nombre_curso').value = data.nombre_curso;
            document.getElementById('materia').value = data.materia;
            document.getElementById('fecha_inicio').value = data.fecha_inicio;
            document.getElementById('fecha_fin').value = data.fecha_fin;
            document.getElementById('horas_disponibles').value = data.horas_disponibles;
            document.getElementById('estado').value = data.estado;
            
            document.getElementById('registroForm').action = updateUrl;
            document.getElementById('methodField').value = 'PUT';
            
            document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil-square me-1"></i> Editando ID: ' + data.id_curso;
            document.getElementById('submitBtn').innerHTML = '<i class="bi bi-arrow-up-circle-fill me-1"></i> Actualizar Curso';
            document.getElementById('cancelarEdicionBtn').classList.remove('d-none');
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function cancelarEdicion() {
            document.getElementById('registroForm').reset();
            document.getElementById('registroForm').action = defaultAction;
            document.getElementById('methodField').value = 'POST';
            document.getElementById('formTitle').innerHTML = '<i class="bi bi-file-earmark-plus-fill me-1"></i> Registro de Curso';
            document.getElementById('submitBtn').innerHTML = '<i class="bi bi-send-fill me-1"></i> Registrar Curso';
            document.getElementById('cancelarEdicionBtn').classList.add('d-none');
        }

        function mostrarAlertaEliminar(event, nombre, deleteUrl) {
            event.preventDefault();
            Swal.fire({
                title: '¿Eliminar curso?',
                text: `Se borrará: ${nombre}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8C001A',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;
                    
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);

                    const method = document.createElement('input');
                    method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>