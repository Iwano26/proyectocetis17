<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Usuarios | CETIS 17</title>

    <!-- Usando Bootstrap 5 y SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Fuente Inter para un look moderno -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Paleta de colores ajustada al tema institucional (rojos y neutros) */
        :root {
            --cetis-primary: #8C001A; /* Rojo oscuro/institucional */
            --cetis-light: #f8f9fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cetis-light);
        }

        /* Estilos del Header y Botones */
        .navbar-cetis {
            background-color: var(--cetis-primary);
        }
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

        /* Estilos de las Tarjetas y Títulos */
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
        
        /* Ajuste de color para la validación de Bootstrap */
        .was-validated input:invalid, .was-validated select:invalid,
        .is-invalid:not(form) {
            border-color: var(--cetis-primary) !important;
        }

        /* Estilos de Tabla */
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.03);
        }

        /* Espacio para el header fijo */
        .main-content {
            padding-top: 80px; 
        }
    </style>
</head>
<body>

    <!-- 1. ENCABEZADO FIJO (HEADER) -->
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
                        <a class="nav-link active" aria-current="page" href="#"><i class="bi bi-people-fill me-1"></i> Usuarios</a>
                    </li>                    
                    <li class="nav-item">
                        <a class="nav-link" href="/principal"><i class="bi bi-box-arrow-right me-1"></i> Inicio</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- CONTENIDO PRINCIPAL -->
    <div class="main-content container mt-4 mb-5">
        <h2 class="text-center mb-5 fw-bolder" style="color: var(--cetis-primary);">
            <i class="bi bi-person-gear me-2"></i> Gestión de Usuarios del CETIS 17
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
            <!-- FORMULARIO DE REGISTRO/CREACIÓN/EDICIÓN A LA IZQUIERDA -->
            <div class="col-12 col-lg-5 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-header card-header-cetis">
                        <span id="formTitle"><i class="bi bi-person-plus-fill me-1"></i> Registro de Usuario</span>
                    </div>
                    <div class="card-body">
                        <!-- CONEXIÓN: action apunta a la ruta nombrada 'gestionusuario.store' por defecto -->
                        <form method="POST" action="{{ route('gestionusuario.store') }}" id="registroForm" class="needs-validation" novalidate>
                            @csrf 
                            
                            <!-- CAMPO OCULTO PARA SIMULAR PUT/PATCH O DELETE (Por defecto es POST para store) -->
                            <input type="hidden" name="_method" value="POST" id="methodField">

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" name="nombre" id="nombre" placeholder="Nombre" class="form-control" value="{{ old('nombre') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellidoPa" class="form-label">Apellido Paterno</label>
                                <input type="text" name="apellidoPa" id="apellidoPa" placeholder="Apellido paterno" class="form-control" value="{{ old('apellidoPa') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellidoMa" class="form-label">Apellido Materno</label>
                                <input type="text" name="apellidoMa" id="apellidoMa" placeholder="Apellido materno" class="form-control" value="{{ old('apellidoMa') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" name="correo" id="correo" class="form-control" placeholder="usuario@cetis17.edu.mx" value="{{ old('correo') }}" required pattern="^[a-zA-Z0-9._%+-]+@cetis17\.edu\.mx$">
                                <div class="invalid-feedback">
                                    El correo debe ser institucional (@cetis17.edu.mx).
                                </div>
                                <!-- Mostrar error de validación de Laravel -->
                                @error('correo')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" name="telefono" id="telefono" placeholder="10 dígitos" class="form-control" value="{{ old('telefono') }}" required pattern="\d{10}" maxlength="10">
                                <div class="invalid-feedback">
                                    El teléfono debe contener 10 dígitos.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol</label>
                                <select name="rol" id="rol" class="form-select" required>
                                    <option value="" disabled selected>Seleccione un rol</option>
                                    <option value="Administrador" {{ old('rol') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                    <option value="Asesor" {{ old('rol') == 'Asesor' ? 'selected' : '' }}>Asesor</option>
                                    <option value="Estudiante" {{ old('rol') == 'Estudiante' ? 'selected' : '' }}>Estudiante</option>
                                </select>
                                <div class="invalid-feedback">
                                    Seleccione un rol.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="pass" class="form-label">Contraseña</label>
                                <!-- Al editar, la contraseña es opcional. -->
                                <input type="password" name="contrasennia" id="pass" placeholder="Contraseña (Dejar vacío para no cambiar)" class="form-control">
                                <div class="form-text">Mínimo 8 caracteres, mayúsculas, minúsculas y números. Deje vacío si no desea cambiar al editar.</div>
                                
                                <!-- Mostrar error de validación de Laravel -->
                                @error('contrasennia')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end d-flex justify-content-end align-items-center mt-4">
                                <!-- Botón de Cancelar (Inicialmente oculto) -->
                                <button type="button" class="btn btn-secondary me-2 d-none" id="cancelarEdicionBtn" onclick="cancelarEdicion()">
                                    <i class="bi bi-x-circle-fill me-1"></i> Cancelar Edición
                                </button>
                                
                                <!-- Botón de Registro/Actualizar -->
                                <button type="submit" class="btn btn-cetis-primary" id="submitBtn">
                                    <i class="bi bi-send-fill me-1"></i> Registrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- TABLA DE VISUALIZACIÓN DE USUARIOS -->
            <div class="col-12 col-lg-7">
                <div class="card shadow-lg border-0">
                    <div class="card-header card-header-cetis">
                        <i class="bi bi-table me-1"></i> Usuarios Registrados
                    </div>                  
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped text-center align-middle m-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre Completo</th>
                                        <th>Correo</th>
                                        <th>Teléfono</th>
                                        <th>Rol</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    {{-- BUCLE DINÁMICO: Itera sobre la colección de usuarios ($usuarios) --}}
                                    @foreach ($usuarios ?? [] as $usuario) 
                                    <tr>
                                        <td>{{ $usuario->nombre }} {{ $usuario->apellidoPa }} {{ $usuario->apellidoMa }}</td>
                                        <td>{{ $usuario->correo }}</td>
                                        <td>{{ $usuario->telefono }}</td>
                                        <td>{{ $usuario->rol }}</td>
                                        <td class="text-nowrap">
                                            {{-- CONEXIÓN EDITAR: Pasa la URL de actualización y los datos del usuario a la función JS --}}
                                            <a href="#" class="btn btn-warning btn-sm text-white me-1" 
                                                title="Editar"
                                                onclick="iniciarEdicion(event, 
                                                    '{{ route('gestionusuario.update', ['gestionusuario' => $usuario->correo]) }}', 
                                                    {
                                                        correo: '{{ $usuario->correo }}', 
                                                        nombre: '{{ $usuario->nombre }}', 
                                                        apellidoPa: '{{ $usuario->apellidoPa }}', 
                                                        apellidoMa: '{{ $usuario->apellidoMa }}', 
                                                        telefono: '{{ $usuario->telefono }}', 
                                                        rol: '{{ $usuario->rol }}'
                                                    }
                                                )">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            {{-- CONEXIÓN DELETE --}}
                                            <a href="#" class="btn btn-danger btn-sm" 
                                                title="Eliminar"
                                                onclick="mostrarAlertaEliminar(event, '{{ $usuario->correo }}', '{{ route('gestionusuario.destroy', ['gestionusuario' => $usuario->correo]) }}')">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr> 
                                    @endforeach
                                    @if (empty($usuarios) || count($usuarios) == 0)
                                    <tr>
                                        <td colspan="5" class="text-muted fst-italic py-3">No hay usuarios registrados en el sistema.</td>
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

    <!-- 2. FOOTER (Opcional, pero añade profesionalismo) -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <small>&copy; 2024 CETIS 17 | Administración de Plataforma. | DGETI.</small>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Variables globales para guardar el estado inicial del formulario
        // NOTA: Estas rutas deben estar definidas en Laravel para que esto funcione.
        const defaultAction = "{{ route('gestionusuario.store') }}"; 
        const defaultMethod = "POST";
        const correoPattern = '^[a-zA-Z0-9._%+-]+@cetis17\.edu\.mx$';

        /**
        * Función para iniciar el modo de edición y pre-cargar los datos del usuario.
        */
        function iniciarEdicion(event, updateUrl, userData) {
            event.preventDefault();
            
            // 1. Llenar el formulario con los datos del usuario
            document.getElementById('correo').value = userData.correo;
            document.getElementById('nombre').value = userData.nombre;
            document.getElementById('apellidoPa').value = userData.apellidoPa;
            document.getElementById('apellidoMa').value = userData.apellidoMa;
            document.getElementById('telefono').value = userData.telefono;
            document.getElementById('rol').value = userData.rol;
            
            // 2. Deshabilitar campos clave para la edición
            document.getElementById('correo').setAttribute('readonly', 'readonly');
            document.getElementById('correo').removeAttribute('pattern'); 
            document.getElementById('pass').value = ''; 
            
            // 3. Cambiar la acción y el método del formulario para la actualización (PUT/PATCH)
            document.getElementById('registroForm').action = updateUrl;
            document.getElementById('methodField').value = 'PUT';
            
            // 4. Actualizar textos de botones e interfaz
            document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil-square me-1"></i> Edición de Usuario';
            document.getElementById('submitBtn').innerHTML = '<i class="bi bi-arrow-up-circle-fill me-1"></i> Actualizar Datos';
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
            
            // 3. Restaurar campos
            document.getElementById('correo').removeAttribute('readonly');
            document.getElementById('correo').setAttribute('pattern', correoPattern); // Restaurar patrón de correo
            
            // 4. Restaurar botones e interfaz
            document.getElementById('formTitle').innerHTML = '<i class="bi bi-person-plus-fill me-1"></i> Registro de Usuario';
            document.getElementById('submitBtn').innerHTML = '<i class="bi bi-send-fill me-1"></i> Registrar';
            document.getElementById('cancelarEdicionBtn').classList.add('d-none'); // Ocultar botón de cancelar
        }

        // FUNCIÓN: Simula el envío DELETE para Laravel usando un formulario oculto.
        function mostrarAlertaEliminar(event, correo, deleteUrl) {
            event.preventDefault(); // Detiene el evento de clic por defecto
            Swal.fire({
                title: '¿Estás seguro?',
                text: `Se eliminará al usuario: ${correo}. ¡Esta acción es irreversible!`,
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
                    form.action = deleteUrl; // Usa la URL de la ruta 'gestionusuario.destroy'
                    
                    // 1. Añadir el token CSRF (obligatorio en Laravel)
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    // Usa la función Blade para obtener el token.
                    // ATENCIÓN: El valor del token es un placeholder en HTML estático
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