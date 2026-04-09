<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Usuarios | CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { --cetis-primary: #8C001A; --cetis-light: #f8f9fa; }
        body { font-family: 'Inter', sans-serif; background-color: var(--cetis-light); }
        .navbar-cetis { background-color: var(--cetis-primary); }
        .btn-cetis-primary { background-color: var(--cetis-primary); border-color: var(--cetis-primary); color: white; }
        .btn-cetis-primary:hover { background-color: #A61E34; color: white; }
        .card-header-cetis { background-color: var(--cetis-primary) !important; color: white; font-weight: 600; }
        .main-content { padding-top: 80px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-cetis fixed-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-gear-fill me-2"></i> Panel de Administración</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-people-fill me-1"></i> Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="/principal"><i class="bi bi-box-arrow-right me-1"></i> Inicio</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="main-content container mt-4 mb-5">
        <h2 class="text-center mb-5 fw-bolder" style="color: var(--cetis-primary);">
            <i class="bi bi-person-gear me-2"></i> Gestión de Usuarios
        </h2>

        <div class="row">
            <div class="col-12 col-lg-5 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-header card-header-cetis">
                        <span id="formTitle"><i class="bi bi-person-plus-fill me-1"></i> Registro de Usuario</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('gestionusuario.store') }}" id="registroForm" class="needs-validation" novalidate>
                            @csrf 
                            <input type="hidden" name="_method" value="POST" id="methodField">

                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Apellido Paterno</label>
                                    <input type="text" name="apellidoPa" id="apellidoPa" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Apellido Materno</label>
                                    <input type="text" name="apellidoMa" id="apellidoMa" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico (Regla 4: No modificable en edición)</label>
                                <input type="email" name="correo" id="correo" class="form-control" required pattern="^[a-zA-Z0-9._%+-]+@cetis17\.edu\.mx$">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" name="telefono" id="telefono" class="form-control" required pattern="\d{10}" maxlength="10">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rol</label>
                                <select name="rol" id="rol" class="form-select" required>
                                    <option value="" disabled selected>Seleccione un rol</option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Asesor">Asesor</option>
                                    <option value="Estudiante">Estudiante</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="contrasennia" id="pass" class="form-control" placeholder="Mínimo 8 caracteres">
                            </div>
                            <div class="text-end mt-4">
                                <button type="button" class="btn btn-secondary d-none" id="cancelarEdicionBtn" onclick="cancelarEdicion()">Cancelar</button>
                                <button type="submit" class="btn btn-cetis-primary" id="submitBtn">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-7">
                <div class="card shadow-lg border-0">
                    <div class="card-header card-header-cetis"><i class="bi bi-table me-1"></i> Usuarios del Sistema</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle m-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Rol</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usuarios as $usuario)
                                    @php
                                        $esMismoUsuario = Auth::user()->correo === $usuario->correo;
                                        $esOtroAdmin = ($usuario->rol === 'Administrador' && !$esMismoUsuario);
                                    @endphp
                                    <tr>
                                        <td>{{ $usuario->nombre }} {{ $usuario->apellidoPa }}</td>
                                        <td><small>{{ $usuario->correo }}</small></td>
                                        <td><span class="badge {{ $usuario->rol == 'Administrador' ? 'bg-danger' : 'bg-primary' }}">{{ $usuario->rol }}</span></td>
                                        <td class="text-nowrap">
                                            @if(!$esOtroAdmin)
                                            <button class="btn btn-warning btn-sm text-white" onclick="iniciarEdicion(event, '{{ route('gestionusuario.update', $usuario->correo) }}', {{ json_encode($usuario) }})">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            @else
                                            <button class="btn btn-outline-secondary btn-sm" onclick="alertaProteccion('editar')">
                                                <i class="bi bi-lock-fill"></i>
                                            </button>
                                            @endif

                                            @if(!$esMismoUsuario && $usuario->rol !== 'Administrador')
                                            <button class="btn btn-danger btn-sm" onclick="mostrarAlertaEliminar(event, '{{ $usuario->correo }}', '{{ route('gestionusuario.destroy', $usuario->correo) }}')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                            @else
                                            <button class="btn btn-outline-secondary btn-sm" onclick="alertaProteccion('eliminar', '{{ $esMismoUsuario }}')">
                                                <i class="bi bi-shield-lock-fill"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // REGLA 6: SweetAlerts Afinadas
        function alertaProteccion(accion, esMismo = false) {
            let config = {
                icon: 'error',
                confirmButtonColor: '#8C001A',
                title: 'Acción Restringida'
            };

            if (accion === 'editar') {
                config.text = 'No puedes modificar los datos de otro administrador por seguridad.';
            } else {
                config.text = esMismo ? 'No puedes eliminar tu propia cuenta mientras estás en sesión.' : 'Las cuentas de administradores están protegidas y no pueden ser eliminadas.';
                config.icon = 'warning';
            }
            Swal.fire(config);
        }

        function iniciarEdicion(event, updateUrl, user) {
            event.preventDefault();
            document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil-square"></i> Editando a: ' + user.nombre;
            document.getElementById('correo').value = user.correo;
            document.getElementById('correo').setAttribute('readonly', true); // REGLA 4
            document.getElementById('nombre').value = user.nombre;
            document.getElementById('apellidoPa').value = user.apellidoPa;
            document.getElementById('apellidoMa').value = user.apellidoMa;
            document.getElementById('telefono').value = user.telefono;
            document.getElementById('rol').value = user.rol;
            
            document.getElementById('registroForm').action = updateUrl;
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('submitBtn').innerText = 'Actualizar Datos';
            document.getElementById('cancelarEdicionBtn').classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function cancelarEdicion() {
            document.getElementById('registroForm').reset();
            document.getElementById('correo').removeAttribute('readonly');
            document.getElementById('methodField').value = 'POST';
            document.getElementById('submitBtn').innerText = 'Registrar';
            document.getElementById('cancelarEdicionBtn').classList.add('d-none');
            document.getElementById('formTitle').innerHTML = '<i class="bi bi-person-plus-fill"></i> Registro de Usuario';
        }

        function mostrarAlertaEliminar(event, correo, url) {
            Swal.fire({
                title: '¿Confirmar eliminación?',
                text: "Se borrará permanentemente a: " + correo,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const f = document.createElement('form');
                    f.method='POST'; f.action=url;
                    f.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(f);
                    f.submit();
                }
            });
        }

        // Alerta de éxito/error al cargar la página si hay sesión
        @if(session('mensaje'))
            Swal.fire({
                icon: '{{ session("sessionInsertado") == "true" || session("sessionEliminado") == "true" ? "success" : "error" }}',
                title: 'Información',
                text: '{{ session("mensaje") }}',
                confirmButtonColor: '#8C001A'
            });
        @endif
    </script>
</body>
</html>