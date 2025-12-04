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
        /* ... TU BLOQUE DE ESTILOS (style) DEBE SER COPIADO AQUÍ ... */
        :root {
            --cetis-primary: #8C001A; /* Rojo oscuro/institucional */
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
                        <a class="nav-link active" aria-current="page" href="{{ route('gestioncurso.index') }}"><i class="bi bi-book-fill me-1"></i> Cursos</a>
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
        
        {{-- BLOQUE DE MENSAJES DE SESIÓN (ÉXITO/ERROR) --}}
        @if(session('mensaje'))
        @php
            // Determinar si es un mensaje de éxito o error
            $isSuccess = session('sessionInsertado') == 'true' || session('sessionEliminado') == 'true';
            $alertClass = $isSuccess ? 'success' : 'danger';
            $icon = $isSuccess ? 'bi-check-circle-fill' : 'bi-x-octagon-fill';
        @endphp
        <div class="alert alert-{{ $alertClass }} alert-dismissible fade show" role="alert">
            <i class="bi {{ $icon }} me-2"></i>
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
                            <input type="hidden" name="id_curso" id="id_cursoField"> <div class="mb-3">
                                <label for="nombre_curso" class="form-label">Nombre del Curso</label>
                                <input type="text" name="nombre_curso" id="nombre_curso" placeholder="Ej: Bases de Datos I" class="form-control" value="{{ old('nombre_curso') }}" maxlength="60" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="materia" class="form-label">Materia</label>
                                <input type="text" name="materia" id="materia" placeholder="Ej: Programación" class="form-control" value="{{ old('materia') }}" maxlength="50" required>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea name="descripcion" id="descripcion" placeholder="Breve descripción del contenido" class="form-control" maxlength="50" rows="2">{{ old('descripcion') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}" required>
                                </div>
                            </div>
                            @error('fecha_fin')
                                <div class="error-message">La fecha de fin debe ser igual o posterior a la fecha de inicio.</div>
                            @enderror
                            
                            <div class="mb-3">
                                <label for="horas_disponibles" class="form-label">Horas Disponibles (Entero)</label>
                                <input type="number" name="horas_disponibles" id="horas_disponibles" placeholder="Ej: 20" class="form-control" value="{{ old('horas_disponibles') }}" min="1" max="24" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select name="estado" id="estado" class="form-select" required>
                                    <option value="" disabled selected>Seleccione un estado</option>
                                    <option value="ACTIVO" {{ old('estado') == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                                    <option value="INACTIVO" {{ old('estado') == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                                    <option value="COMPLETADO" {{ old('estado') == 'COMPLETADO' ? 'selected' : '' }}>COMPLETADO</option>
                                </select>
                                <div class="invalid-feedback">
                                    Seleccione un estado.
                                </div>
                            </div>

                            <div class="text-end d-flex justify-content-end align-items-center mt-4">
                                <button type="button" class="btn btn-secondary me-2 d-none" id="cancelarEdicionBtn" onclick="cancelarEdicion()">
                                    <i class="bi bi-x-circle-fill me-1"></i> Cancelar Edición
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
                                        <th>Nombre del Curso</th>
                                        <th>Materia</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Hrs. Disp.</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    {{-- BUCLE DINÁMICO: Itera sobre la colección de cursos ($cursos) --}}
                                    @foreach ($cursos ?? [] as $curso) 
                                    <tr>
                                        <td>{{ $curso->id_curso }}</td>
                                        <td>{{ $curso->nombre_curso }}</td>
                                        <td>{{ $curso->materia }}</td>
                                        <td>{{ $curso->fecha_inicio }}</td>
                                        <td>{{ $curso->fecha_fin }}</td>
                                        <td>{{ $curso->horas_disponibles }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($curso->estado == 'ACTIVO') bg-success 
                                                @elseif($curso->estado == 'INACTIVO') bg-warning text-dark
                                                @else bg-secondary 
                                                @endif">
                                                {{ $curso->estado }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap">
                                            {{-- CONEXIÓN EDITAR: Pasa la URL de actualización y los datos del curso a la función JS --}}
                                            <a href="#" class="btn btn-warning btn-sm text-white me-1" 
                                                title="Editar"
                                                onclick="iniciarEdicion(event, 
                                                    '{{ route('gestioncurso.update', ['gestioncurso' => $curso->id_curso]) }}', 
                                                    {
                                                        id_curso: '{{ $curso->id_curso }}', 
                                                        nombre_curso: '{{ $curso->nombre_curso }}', 
                                                        descripcion: '{{ $curso->descripcion }}', 
                                                        fecha_inicio: '{{ $curso->fecha_inicio }}', 
                                                        fecha_fin: '{{ $curso->fecha_fin }}', 
                                                        materia: '{{ $curso->materia }}',
                                                        horas_disponibles: '{{ $curso->horas_disponibles }}', 
                                                        estado: '{{ $curso->estado }}'
                                                    }
                                                )">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            {{-- CONEXIÓN DELETE --}}
                                            <a href="#" class="btn btn-danger btn-sm" 
                                                title="Eliminar"
                                                onclick="mostrarAlertaEliminar(event, '{{ $curso->nombre_curso }}', '{{ route('gestioncurso.destroy', ['gestioncurso' => $curso->id_curso]) }}')">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr> 
                                    @endforeach
                                    @if (empty($cursos) || count($cursos) == 0)
                                    <tr>
                                        <td colspan="8" class="text-muted fst-italic py-3">No hay cursos registrados en el sistema.</td>
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
            <small>&copy; 2024 CETIS 17 | Administración de Plataforma. | DGETI.</small>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Variables globales para guardar el estado inicial del formulario
        const defaultAction = "{{ route('gestioncurso.store') }}"; 
        const defaultMethod = "POST";

        /**
         * Función para iniciar el modo de edición y pre-cargar los datos del curso.
         */
        function iniciarEdicion(event, updateUrl, courseData) {
            event.preventDefault();
            
            // 1. Llenar el formulario con los datos del curso
            document.getElementById('id_cursoField').value = courseData.id_curso;
            document.getElementById('nombre_curso').value = courseData.nombre_curso;
            document.getElementById('descripcion').value = courseData.descripcion;
            document.getElementById('fecha_inicio').value = courseData.fecha_inicio;
            document.getElementById('fecha_fin').value = courseData.fecha_fin;
            document.getElementById('materia').value = courseData.materia;
            document.getElementById('horas_disponibles').value = courseData.horas_disponibles;
            document.getElementById('estado').value = courseData.estado;
            
            // 2. No hay campos clave que deshabilitar/limpiar como la contraseña en cursos, pero el ID va en un campo oculto.
            
            // 3. Cambiar la acción y el método del formulario para la actualización (PUT/PATCH)
            document.getElementById('registroForm').action = updateUrl;
            document.getElementById('methodField').value = 'PUT';
            
            // 4. Actualizar textos de botones e interfaz
            document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil-square me-1"></i> Edición de Curso (ID: ' + courseData.id_curso + ')';
            document.getElementById('submitBtn').innerHTML = '<i class="bi bi-arrow-up-circle-fill me-1"></i> Actualizar Curso';
            document.getElementById('cancelarEdicionBtn').classList.remove('d-none'); // Mostrar botón de cancelar
            document.getElementById('registroForm').classList.remove('was-validated'); // Limpiar validación
            
            // Scroll hacia el formulario para mejor UX
            document.getElementById('registroForm').scrollIntoView({ behavior: 'smooth' });
        }

        /**
         * Función para revertir el formulario al modo de registro (Creación).
         */
        function cancelarEdicion() {
            // 1. Resetear el formulario y limpiar estilos de validación
            document.getElementById('registroForm').reset();
            document.getElementById('registroForm').classList.remove('was-validated');
            
            // 2. Restaurar la acción y el método por defecto (POST para store)
            document.getElementById('registroForm').action = defaultAction;
            document.getElementById('methodField').value = defaultMethod;
            document.getElementById('id_cursoField').value = ''; // Limpiar el ID oculto
            
            // 3. Restaurar botones e interfaz
            document.getElementById('formTitle').innerHTML = '<i class="bi bi-file-earmark-plus-fill me-1"></i> Registro de Curso';
            document.getElementById('submitBtn').innerHTML = '<i class="bi bi-send-fill me-1"></i> Registrar Curso';
            document.getElementById('cancelarEdicionBtn').classList.add('d-none'); // Ocultar botón de cancelar
        }

        // FUNCIÓN: Simula el envío DELETE para Laravel usando un formulario oculto.
        function mostrarAlertaEliminar(event, nombreCurso, deleteUrl) {
            event.preventDefault(); // Detiene el evento de clic por defecto
            Swal.fire({
                title: '¿Estás seguro?',
                text: `Se eliminará el curso: ${nombreCurso}. ¡Esta acción es irreversible!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crea un formulario oculto para enviar la petición POST con el método DELETE
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl; // Usa la URL de la ruta 'gestioncurso.destroy'
                    
                    // 1. Añadir el token CSRF (obligatorio en Laravel)
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}'; 
                    form.appendChild(csrfInput);

                    // 2. Añadir el campo de método para simular DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    // Enviar el formulario y recargar la página
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Validación de formularios de Bootstrap
        (function () {
            'use strict'
            var form = document.getElementById('registroForm');
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        })();
    </script>

</body>
</html>