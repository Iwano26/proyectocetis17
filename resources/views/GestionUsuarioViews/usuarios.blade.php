@extends('layouts.app')

@section('content')

{{-- 
    Cargamos las hojas de estilos institucionales si no se cargan automáticamente en el layout.
    Si ya están en layouts.app, puedes omitir estas dos líneas de <link>.
--}}
<link rel="stylesheet" href="{{ asset('css/menuiz.css') }}">
<link rel="stylesheet" href="{{ asset('css/home.css') }}">

<style>
    /* ========== OPTIMIZACIÓN DE ACCESIBILIDAD Y TAMAÑO DE LETRA (MAYORES DE 40 AÑOS) ========== */
    
    .section-gestion-usuarios {
        padding: 40px 0 80px;
        /* Aumentamos el tamaño de letra base de toda la sección */
        font-size: 1.1rem; 
    }
    
    .card-gestion {
        background: #ffffff;
        border: 1px solid rgba(140, 0, 26, 0.12);
        border-radius: 16px;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .card-header-cetis {
        background-color: var(--cetis-rojo, #8C001A) !important;
        color: white !important;
        font-family: 'Georgia', serif;
        font-weight: bold;
        font-size: 1.35rem; /* Título del formulario más grande */
        padding: 18px 24px;
        border-bottom: none;
    }

    /* Etiquetas de los campos del formulario (Nombre, Correo, Rol, etc.) */
    .form-label {
        font-weight: 700;
        font-size: 1.1rem; /* Texto de etiquetas grande y claro */
        color: var(--cetis-texto, #1a1a2e);
        margin-bottom: 8px;
        display: block;
    }

    /* Cajas de texto, inputs y selectores grandes y legibles */
    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid rgba(140, 0, 26, 0.25); /* Bordes más oscuros para mejor contraste */
        padding: 0.8rem 1rem; /* Más espacio interno para que no se amontone el texto */
        font-family: 'Inter', sans-serif;
        font-size: 1.1rem; /* Texto interno grande */
        color: #1a1a2e;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--cetis-rojo-claro, #b0001f);
        box-shadow: 0 0 0 0.25rem rgba(140, 0, 26, 0.15);
    }
    
    /* Placeholders con gris más oscuro para que se alcancen a leer perfectamente */
    .form-control::placeholder {
        color: #72777a;
        opacity: 1;
    }

    /* ========== ESTILOS DE LA TABLA DE USUARIOS CON LETRA GRANDE ========== */
    
    /* Encabezados de la tabla de usuarios */
    .table-usuarios th {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 1.05rem; /* Encabezados grandes */
        color: #2c3e50;
        padding: 16px 18px;
        background-color: #f8f9fa;
        border-bottom: 3px solid rgba(140, 0, 26, 0.15);
    }

    /* Celdas de la tabla con los datos del usuario */
    .table-usuarios td {
        padding: 18px 18px; /* Mayor separación por fila para descanso visual */
        font-family: 'Inter', sans-serif;
        font-size: 1.05rem; /* Texto de datos grande */
        color: #1a1a2e;
    }

    /* Nombre del Usuario destacado en la lista */
    .usuario-title {
        font-family: 'Georgia', serif;
        font-weight: bold;
        color: var(--cetis-rojo, #8C001A); /* Destacado institucional */
        font-size: 1.2rem;
        margin-bottom: 4px;
    }

    /* Textos secundarios (como el correo o teléfono abajo del nombre) */
    .text-muted-grande {
        color: #4a5568 !important; /* Gris oscuro de alto contraste */
        font-size: 0.95rem;
        font-weight: 500;
    }

    .badge-rol {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 0.9rem; /* Badges de roles legibles (ADMIN, DOCENTE, etc.) */
        padding: 6px 12px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    /* Botones de acción grandes (Editar/Eliminar) para evitar clics erróneos */
    .btn-action-grande {
        padding: 10px 14px; /* Más amplios para el dedo o el mouse */
        border-radius: 8px;
        font-size: 1.05rem;
        transition: transform 0.15s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-action-grande:hover {
        transform: scale(1.05);
    }
    
    /* Botón principal de registrar usuario */
    .btn-submit-grande {
        font-size: 1.15rem;
        font-weight: 700;
        padding: 12px 28px;
        border-radius: 8px;
    }

    /* ========== AJUSTE ESTRUCTURAL PARA EL OJITO DE LA CONTRASEÑA ========== */
    .password-group {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .password-wrapper {
        position: relative;
        width: 100%;
    }

    .password-wrapper i {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #555;
        font-size: 1.25rem;
        z-index: 10;
    }

    /* Estilo personalizado para el texto de error nativo si usas bootstrap feedback */
    .invalid-feedback-custom {
        color: #dc3545;
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: 5px;
        text-align: left;
    }
</style>

{{-- ========== CONTROLADOR DE ALERTAS FLOTANTES GLOBAL ========== --}}
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Operación Exitosa!',
                text: @json(session('success')),
                confirmButtonColor: '#8C001A'
            });
        });
    </script>
@endif

@if(session('mensaje'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let isActionOk = @json(session('sessionInsertado')) == "true" || @json(session('sessionEliminado')) == "true" || @json(session('sessionActualizado')) == "true";
            Swal.fire({
                icon: isActionOk ? 'success' : 'error',
                title: isActionOk ? '¡Completado!' : 'Atención',
                text: @json(session('mensaje')),
                confirmButtonColor: '#8C001A'
            });
        });
    </script>
@endif

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error de Validación',
                text: 'Por favor, revise que todos los campos cumplan con las reglas solicitadas.',
                confirmButtonColor: '#8C001A'
            });
        });
    </script>
@endif

<section class="section-gestion-usuarios">
    <div class="container">
        
        {{-- Encabezado de Página Estilo Institucional --}}
        <div class="text-center mb-5">
            <h2 class="section-titulo">
                <i class="bi bi-person-gear me-2" style="color: var(--cetis-rojo);"></i>Gestión de Usuarios
            </h2>
            <p class="section-subtitulo">Administra los accesos, roles y estados de las cuentas en el ecosistema académico</p>
        </div>

        <div class="row">
            {{-- Columna del Formulario --}}
            <div class="col-12 col-lg-5">
                <div class="card card-gestion">
                    <div class="card-header card-header-cetis">
                        <span id="formTitle"><i class="bi bi-person-plus-fill me-1"></i> Registro de Usuario</span>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('gestionusuario.store') }}" id="registroForm" class="needs-validation" novalidate>
                            @csrf 
                            <input type="hidden" name="_method" value="POST" id="methodField">

                            <div class="mb-3">
                                <label class="form-label">Nombre(s)</label>
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
                                <label class="form-label">Correo Electrónico institucional</label>
                                <input type="email" name="correo" id="correo" class="form-control" required pattern="^[a-zA-Z0-9._%+-]+@cetis17\.edu\.mx$">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" name="telefono" id="telefono" class="form-control" required pattern="\d{10}" maxlength="10">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Rol del Usuario</label>
                                <select name="rol" id="rol" class="form-select" required>
                                    <option value="" disabled selected>Seleccione un rol</option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Asesor">Asesor</option>
                                    <option value="Estudiante">Estudiante</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Contraseña de acceso</label>
                                <div class="password-group">
                                    <div class="password-wrapper">
                                        <input type="password" name="contrasennia" id="pass" class="form-control" 
                                            placeholder="Mínimo 8 caracteres" 
                                            minlength="8"
                                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$">
                                        <i class="bi bi-eye-slash" id="togglePass"></i>
                                    </div>
                                    <div class="invalid-feedback-custom d-none" id="errorPassMessage">
                                        La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-light d-none" id="cancelarEdicionBtn" onclick="cancelarEdicion()" style="border-radius: 8px; font-weight:600;">Cancelar</button>
                                <button type="submit" class="btn btn-cetis-primary px-4" id="submitBtn" style="padding: 10px 24px;">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            {{-- Columna de la Tabla --}}
            <div class="col-12 col-lg-7">
                <div class="card card-gestion">
                    <div class="card-header card-header-cetis">
                        <i class="bi bi-table me-2"></i> Usuarios del Sistema
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-usuarios align-middle m-0">
                                <thead>
                                    <tr>
                                        <th>Información del Usuario</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usuarios as $usuario)
                                    @php
                                        $esMismoUsuario = Auth::user()->correo === $usuario->correo;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="user-name-title">{{ $usuario->nombre }} {{ $usuario->apellidoPa }} {{ $usuario->apellidoMa }}</div>
                                            <small class="text-muted d-block mt-1" style="font-size: 0.8rem;"><i class="bi bi-envelope me-1"></i>{{ $usuario->correo }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-rol {{ $usuario->rol == 'Administrador' ? 'bg-danger' : ($usuario->rol == 'Asesor' ? 'bg-warning text-dark' : 'bg-primary') }}">
                                                {{ $usuario->rol }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(!$esMismoUsuario)
                                                <form action="{{ route('gestionusuario.toggleStatus', $usuario->correo) }}" method="POST" class="d-inline m-0">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-action {{ $usuario->activo == 1 ? 'btn-success' : 'btn-secondary' }} px-2 py-1" style="font-size: 0.75rem; font-weight: 600;">
                                                        <i class="bi {{ $usuario->activo == 1 ? 'bi-check-circle-fill' : 'bi-dash-circle-fill' }} me-1"></i>
                                                        {{ $usuario->activo == 1 ? 'Activo' : 'Inactivo' }}
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-success badge-rol px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Activo</span>
                                            @endif
                                        </td>
                                        <td class="text-center" style="width: 140px;">
                                        <div class="d-flex justify-content-center gap-2">
                                            
                                            {{-- Editar --}}
                                            <button class="btn btn-warning btn-sm btn-action-grande text-white" 
                                                    title="Editar Usuario"
                                                    onclick="iniciarEdicion(event, '{{ route('gestionusuario.update', $usuario->correo) }}', {{ json_encode($usuario) }})">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>                                           

                                        </div>
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
</section>

<script>
    function alertaProteccion() {
        Swal.fire({
            icon: 'warning',
            title: 'Operación Inválida',
            text: 'Por medidas de seguridad, no está permitido remover de forma autónoma su propia cuenta de administrador durante la sesión actual.',
            confirmButtonColor: '#8C001A'
        });
    }

    function iniciarEdicion(event, updateUrl, user) {
        event.preventDefault();
        document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil-square"></i> Modificar Usuario: ' + user.nombre;
        document.getElementById('correo').value = user.correo;
        document.getElementById('correo').setAttribute('readonly', true);
        document.getElementById('nombre').value = user.nombre;
        document.getElementById('apellidoPa').value = user.apellidoPa;
        document.getElementById('apellidoMa').value = user.apellidoMa;
        document.getElementById('telefono').value = user.telefono;
        document.getElementById('rol').value = user.rol;
        
        // Al editar la contraseña pasa a ser opcional, limpiamos advertencias previas
        document.getElementById('pass').value = '';
        document.getElementById('pass').removeAttribute('required');
        document.getElementById('errorPassMessage').classList.add('d-none');
        
        document.getElementById('registroForm').action = updateUrl;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('submitBtn').innerText = 'Guardar Cambios';
        document.getElementById('cancelarEdicionBtn').classList.remove('d-none');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function cancelarEdicion() {
        document.getElementById('registroForm').reset();
        document.getElementById('correo').removeAttribute('readonly');
        document.getElementById('pass').setAttribute('required', true);
        document.getElementById('errorPassMessage').classList.add('d-none');
        document.getElementById('methodField').value = 'POST';
        document.getElementById('submitBtn').innerText = 'Registrar';
        document.getElementById('cancelarEdicionBtn').classList.add('d-none');
        document.getElementById('formTitle').innerHTML = '<i class="bi bi-person-plus-fill"></i> Registro de Usuario';
    }

    function mostrarAlertaEliminar(event, correo, url) {
        Swal.fire({
            title: '¿Confirmar eliminación permanente?',
            text: "Esta acción removerá definitivamente al usuario " + correo + " de la base de datos institucional.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Confirmar',
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

    // Funcionalidad del ojo para mostrar/ocultar contraseña
    document.getElementById('togglePass').addEventListener('click', function() {
        const inputPass = document.getElementById('pass');
        if (inputPass.type === "password") {
            inputPass.type = "text";
            this.classList.remove("bi-eye-slash");
            this.classList.add("bi-eye");
        } else {
            inputPass.type = "password";
            this.classList.remove("bi-eye");
            this.classList.add("bi-eye-slash");
        }
    });

    // Validación interactiva de la expresión regular en tiempo real
    document.getElementById('registroForm').addEventListener('submit', function(e) {
        const passInput = document.getElementById('pass');
        const errorContainer = document.getElementById('errorPassMessage');
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        
        // Si el campo tiene datos o es obligatorio (Registro nuevo) evaluamos la expresión regular
        if (passInput.value.length > 0 || passInput.hasAttribute('required')) {
            if (!regex.test(passInput.value)) {
                e.preventDefault();
                errorContainer.classList.remove('d-none');
                passInput.focus();
                return false;
            }
        }
        errorContainer.classList.add('d-none');
    });
</script>

@endsection