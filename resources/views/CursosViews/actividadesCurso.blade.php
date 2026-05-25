<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CETIS 17 | {{ $curso->nombre_curso }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --cetis-rojo: #8C001A; 
            --cetis-primary: #8C001A;
        }
        body { 
            background-color: #f8f9fa; 
            font-family: 'Inter', sans-serif; 
        }

        .tarjeta-curso-interna {
            background-color: white; 
            border: 2px solid var(--cetis-rojo);
            border-radius: 12px; 
            padding: 20px; 
            margin-bottom: 15px;
        }

        .opciones-curso .nav-link {
            color: #333;
            font-weight: 700;
            border: 1px solid #dee2e6;
            margin-bottom: 8px;
            border-radius: 8px;
            padding: 15px;
            text-align: left;
            background-color: white;
            transition: all 0.2s;
        }

        /* Esta es la clase que hace que se vea rojo */
        .opciones-curso .nav-link.active {
            background-color: var(--cetis-rojo) !important;
            color: white !important;
            border-color: var(--cetis-rojo) !important;
        }

        .formato-horario {
            display: inline-block;
            background-color: #eee;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <script>
    // =============================================
    // SWEETALERT: Mensajes de sesión (éxito/error)
    // =============================================
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Listo!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#8C001A',
            confirmButtonText: 'Aceptar',
            timer: 3000,
            timerProgressBar: true,
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#8C001A',
            confirmButtonText: 'Aceptar',
        });
    @endif

    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Información',
            text: '{{ session('info') }}',
            confirmButtonColor: '#8C001A',
            confirmButtonText: 'Aceptar',
        });
    @endif

    // =============================================
    // SWEETALERT: Confirmar eliminar actividad
    // =============================================
    document.querySelectorAll('.form-eliminar-evento').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar actividad?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8C001A',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash3-fill me-1"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // =============================================
    // SWEETALERT: Confirmar salir del curso
    // =============================================
    document.querySelectorAll('.form-salir-curso').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Salir del curso?',
                text: 'Perderás tu inscripción y deberás volver a unirte.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8C001A',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // =============================================
    // SWEETALERT: Confirmar cancelar asistencia
    // =============================================
    document.querySelectorAll('.form-cancelar-asistencia').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Cancelar asistencia?',
                text: 'Se eliminará tu registro en esta asesoría.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8C001A',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    </script>
    @extends('layouts.app')

    @section('content')
    <div class="container-fluid mt-0 px-4">
        
        {{-- CABECERA DEL CURSO --}}
        <div class="tarjeta-curso-interna shadow-sm bg-white mb-4 mt-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h2 class="fw-bold m-0 text-uppercase">{{ $curso->nombre_curso }}</h2>
                    <p class="text-muted mb-0">Impartido por: <strong>{{ $curso->nombre_asesor }}</strong></p>
                    <small class="text-secondary"><i class="bi bi-book me-1"></i> Materia: {{ $curso->materia }}</small>
                </div>
                
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <div class="d-flex flex-column align-items-md-end">
                        <span class="badge {{ $curso->estado == 'ACTIVO' ? 'bg-success' : 'bg-secondary' }} mb-2 shadow-sm">
                            ESTADO: {{ $curso->estado }}
                        </span>

                        @if(Auth::user()->rol === 'Estudiante')
                            @if($yaInscrito)
                                <form action="{{ route('cursos.salir', $curso->id_curso) }}" method="POST" class="d-inline form-salir-curso">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger fw-bold px-4">
                                        <i class="bi bi-box-arrow-left me-2"></i> SALIR DEL CURSO
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('cursos.inscribir', $curso->id_curso) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-dark fw-bold px-4" {{ $curso->estado != 'ACTIVO' ? 'disabled' : '' }}>
                                        <i class="bi bi-door-open-fill me-2"></i> INGRESAR AL CURSO
                                    </button>
                                </form>
                            @endif
                        @else
                            
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Menú Lateral --}}
            <div class="col-md-3">
                <div class="nav flex-column opciones-curso">
                   <a href="{{ route('cursos.show', $curso->id_curso) }}" class="nav-link shadow-sm">
                        <i class="bi bi-info-circle me-2"></i> INFORMACIÓN
                    </a>
                    {{-- Aquí agregamos la clase 'active' --}}
                    <a href="{{ route('curso.eventos', $curso->id_curso) }}" class="nav-link shadow-sm active">
                        <i class="bi bi-list-task me-2"></i> ACTIVIDADES
                    </a>
                   <a href="{{ route('foro.index', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-chat-dots me-2"></i> FORO
                    </a>
                

                    @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Asesor')
                        <a href="{{ route('reportes.index', $curso->id_curso) }}" class="nav-link shadow-sm border-danger">
                            <i class="bi bi-file-earmark-text me-2 text-danger"></i> REPORTES
                        </a>
                    @endif
                </div>
            </div>

            {{-- Contenido Principal --}}
            <div class="col-md-9">
                {{-- Contenedor con título y botón alineados a los extremos --}}
                {{-- Contenedor con título y botón alineados a los extremos --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">Actividades del Curso</h4>
                    
                    {{-- Candado estricto por Roles: Solo Administrador o Asesor pueden crear --}}
                    @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Asesor')
                        <div class="d-flex gap-2">
                            {{-- Botón Directo para la Asesoría --}}
                            <a href="{{ route('asesorias.create', $curso->id_curso) }}" class="btn btn-danger fw-bold shadow-sm">
                                <i class="bi bi-calendar-event-fill me-1"></i> + Crear Asesoría
                            </a>
                            {{-- Botón para el Examen (Si quieres agregarlo después) --}}
                            <a href="{{ route('examenes.create', $curso->id_curso) }}" class="btn btn-danger fw-bold shadow-sm">
                                <i class="bi bi-file-earmark-check me-1"></i> + Crear Examen
                            </a>
                        </div>
                    @endif
                </div>
                
                {{-- Tarjeta de Descripción --}}
                <div class="tarjeta-curso-interna shadow-sm mb-4">
                    <h6 class="fw-bold text-danger text-uppercase">Sobre esta asesoría</h6>
                    <p class="text-muted">{{ $curso->descripcion ?? 'Sin descripción disponible.' }}</p>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <small class="d-block text-muted text-uppercase">Inicio</small>
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</span>
                        </div>
                        <div class="col-6">
                            <small class="d-block text-muted text-uppercase">Finalización</small>
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

            {{-- Listado de Actividades --}}
                @forelse($eventos as $evento)

                @php
                    $borde = match($evento->ases_estado ?? 'DISPONIBLE') {
                        'CANCELADA' => 'border-secondary',
                        'TERMINADA' => 'border-dark',
                        default     => 'border-danger',
                    };

                    if ($evento->tipo === 'cuestionario') {
                        $borde = match($evento->ex_estado ?? 'PENDIENTE') {
                            'ACTIVO'  => 'border-success',
                            'CERRADO' => 'border-dark',
                            default   => 'border-warning'
                        };
                    }

                    $badgeColor = match($evento->ases_estado ?? 'DISPONIBLE') {
                        'DISPONIBLE' => 'bg-success',
                        'EN_CURSO'   => 'bg-warning text-dark',
                        'TERMINADA'  => 'bg-dark',
                        default      => 'bg-secondary'
                    };

                    $exBadge = match($evento->ex_estado ?? 'PENDIENTE') {
                        'ACTIVO'  => 'bg-success',
                        'CERRADO' => 'bg-dark',
                        default   => 'bg-warning text-dark'
                    };
                @endphp

                <div class="card mb-3 shadow-sm border-0 border-start border-4 {{ $borde }}"
                    style="border-radius: 12px; overflow: hidden;">
                    <div class="card-body py-3 px-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                            {{-- LADO IZQUIERDO --}}
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                    @if($evento->tipo === 'asesoria')
                                        <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded fw-bold text-white"
                                            style="background:#8C001A; font-size:0.72rem; letter-spacing:0.5px;">
                                            <i class="bi bi-person-video3"></i> ASESORÍA
                                        </span>
                                        <span class="badge {{ $badgeColor }} text-uppercase" style="font-size:0.72rem;">
                                            {{ $evento->ases_estado ?? 'DISPONIBLE' }}
                                        </span>
                                    @endif
                                    @if($evento->tipo === 'cuestionario')
                                        <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded fw-bold text-white"
                                            style="background:#1a3a5c; font-size:0.72rem; letter-spacing:0.5px;">
                                            <i class="bi bi-file-earmark-check"></i> EXAMEN
                                        </span>
                                        <span class="badge {{ $exBadge }} text-uppercase" style="font-size:0.72rem;">
                                            {{ $evento->ex_estado ?? 'PENDIENTE' }}
                                        </span>
                                    @endif
                                </div>

                                <h5 class="fw-bold mb-1 text-dark" style="font-size:1.1rem; line-height:1.3;">
                                    {{ $evento->nombre_evento }}
                                </h5>

                                <div class="d-flex flex-wrap gap-2 align-items-center mt-1">
                                    <span style="font-size:0.8rem; color:#888;">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        Publicado: {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}
                                    </span>
                                    @if($evento->tipo === 'asesoria' && $evento->ases_lugar)
                                        <span class="badge bg-light text-dark border" style="font-size:0.78rem;">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                            {{ $evento->ases_lugar }}
                                        </span>
                                    @endif
                                    @if($evento->tipo === 'asesoria' && $evento->ases_fecha)
                                        <span class="badge bg-light text-dark border" style="font-size:0.78rem;">
                                            <i class="bi bi-clock me-1 text-danger"></i>
                                            {{ \Carbon\Carbon::parse($evento->ases_fecha)->format('d/m/Y') }}
                                            {{ \Carbon\Carbon::parse($evento->ases_inicio)->format('h:i A') }} —
                                            {{ \Carbon\Carbon::parse($evento->ases_fin)->format('h:i A') }}
                                        </span>
                                    @endif
                                    @if($evento->tipo === 'cuestionario' && $evento->ex_fecha)
                                        <div class="card-text mt-2 p-2 bg-light border rounded" style="font-size: 0.85rem;">
                                            <div class="row">
                                                <div class="col-12 mb-1">
                                                    <strong>Fecha Inicio:</strong> {{ \Carbon\Carbon::parse($evento->ex_fecha)->format('d/m/Y') }} 
                                                    &nbsp; <strong>Hora:</strong> {{ \Carbon\Carbon::parse($evento->ex_inicio)->format('H:i') }}
                                                </div>
                                                <div class="col-12">
                                                    <strong>Fecha Cierre:</strong> {{ \Carbon\Carbon::parse($evento->ex_fecha_cierre)->format('d/m/Y') }}
                                                    &nbsp; <strong>Hora:</strong> {{ \Carbon\Carbon::parse($evento->ex_fin)->format('H:i') }}
                                                </div>
                                            </div>
                                            <div class="mt-2 text-primary fw-bold">
                                                <i class="bi bi-arrow-repeat"></i> {{ $evento->ex_oportunidades ?? 1 }} intento(s)
                                            </div>
                                        </div>
                                    @endif
                                    {{-- Borra temporalmente tu código y pon esto para ver qué hay --}}
                                </div>
                            </div>

                            {{-- CONTADOR ASISTENCIAS --}}
                            @if($evento->tipo === 'asesoria')
                                <div class="text-center px-4 border-start border-end d-none d-md-block">
                                    <span class="d-block text-muted fw-bold text-uppercase mb-1"
                                        style="font-size:0.65rem; letter-spacing:0.5px;">Asistencias</span>
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold shadow-sm"
                                        style="font-size:1rem;">
                                        <i class="bi bi-people-fill me-1"></i>{{ $evento->total_asistentes ?? 0 }}
                                    </span>
                                </div>
                            @endif

                            {{-- BOTONES --}}
                            <div class="d-flex align-items-center gap-2 flex-wrap">

                                @if(Auth::user()->rol === 'Estudiante')

                                    @if($evento->tipo === 'asesoria')
                                        @if(($evento->ya_inscrito ?? 0) > 0)
                                            <form action="{{ route('asesorias.cancelar', $evento->id_evento) }}"
                                                method="POST" class="m-0 form-cancelar-asistencia">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-success fw-bold px-3"
                                                    style="font-size:0.85rem;"
                                                    @if(in_array($evento->ases_estado ?? '', ['TERMINADA','CANCELADA','EN_CURSO'])) disabled @endif>
                                                    <i class="bi bi-check-circle-fill me-1"></i> Registrado
                                                </button>
                                                @if(($evento->ya_inscrito ?? 0) > 0 
                                                    && ($evento->ases_requiere_evidencia ?? 0) == 1 
                                                    && in_array($evento->ases_estado ?? '', ['EN_CURSO', 'TERMINADA']))
                                                    @php
                                                        $yaSubioEvidencia = DB::table('evidencia_asesoria')
                                                            ->where('id_asesoria', $evento->id_asesoria)
                                                            ->where('correo_alumno', Auth::user()->correo)
                                                            ->exists();
                                                    @endphp
                                                    @if(!$yaSubioEvidencia)
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger fw-bold px-3"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEvidencia{{ $evento->id_asesoria }}">
                                                            <i class="bi bi-file-earmark-arrow-up me-1"></i> Subir Evidencia
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-success fw-bold px-3" disabled>
                                                            <i class="bi bi-file-earmark-check me-1"></i> Evidencia enviada
                                                        </button>
                                                    @endif
                                                @endif
                                            </form>
                                        @else
                                            <form action="{{ route('asesorias.unirse', $evento->id_evento) }}"
                                                method="POST" class="m-0">
                                                @csrf
                                                @php
                                                    $desactivar = in_array($evento->ases_estado ?? '', ['TERMINADA','CANCELADA','EN_CURSO'])
                                                            || ($evento->bloquear_inscripcion ?? false);
                                                @endphp
                                                <button type="submit"
                                                    class="btn fw-bold px-3 {{ $desactivar ? 'btn-secondary' : 'btn-outline-danger' }}"
                                                    style="font-size:0.85rem;"
                                                    @if($desactivar) disabled @endif
                                                    title="{{ ($evento->bloquear_inscripcion ?? false) ? 'Inscripción cerrada: menos de 1 hora sin asistentes' : '' }}">
                                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                                    {{ ($evento->bloquear_inscripcion ?? false) ? 'Cerrado' : 'Unirse' }}
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    @if($evento->tipo === 'cuestionario')
                                        @if(($evento->ex_estado ?? '') === 'ACTIVO')
                                            <a href="{{ route('examen.inicio', $evento->id_cuestionario) }}"
                                            class="btn btn-danger fw-bold px-3" style="font-size:0.85rem;">
                                                <i class="bi bi-pencil-square me-1"></i> Iniciar Examen
                                            </a>
                                        @elseif(($evento->ex_estado ?? '') === 'PENDIENTE')
                                            <button class="btn btn-warning fw-bold px-3" style="font-size:0.85rem;" disabled>
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $evento->ex_fecha ? \Carbon\Carbon::parse($evento->ex_fecha)->format('d/m/Y') : '' }}
                                                {{ $evento->ex_inicio ? \Carbon\Carbon::parse($evento->ex_inicio)->format('h:i A') : '' }}
                                            </button>
                                        @elseif(($evento->ex_estado ?? '') === 'CERRADO')
                                            <a href="{{ route('examen.misResultados', $evento->id_cuestionario) }}"
                                            class="btn btn-outline-dark fw-bold px-3" style="font-size:0.85rem;">
                                                <i class="bi bi-bar-chart-fill me-1"></i> Mis Resultados
                                            </a>
                                        @endif
                                    @endif

                                @elseif(Auth::user()->rol === 'Asesor' || Auth::user()->rol === 'Administrador')

                                    @if($evento->tipo === 'asesoria')
                                        <button type="button"
                                            class="btn btn-dark fw-bold px-3"
                                            style="font-size:0.85rem;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalLista{{ $evento->id_evento }}">
                                            <i class="bi bi-card-checklist me-1"></i>
                                            <span class="d-none d-md-inline">Lista</span>
                                        </button>
                                        <a href="/evento/{{ $evento->id_evento }}/editar-asesoria"
                                        class="btn btn-outline-primary fw-bold px-2"
                                        style="font-size:0.85rem;" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @if(($evento->ases_requiere_evidencia ?? 0) == 1)
                                            <a href="{{ route('evidencia.ver', $evento->id_asesoria) }}"
                                            class="btn btn-sm btn-outline-danger fw-bold px-2 shadow-sm"
                                            title="Ver Evidencias">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                                <span class="d-none d-md-inline ms-1">Evidencias</span>
                                            </a>
                                        @endif
                                    @endif

                                    @if($evento->tipo === 'cuestionario' && $evento->id_cuestionario)
                                        <a href="{{ route('examenes.resultados', $evento->id_cuestionario) }}"
                                        class="btn btn-dark fw-bold px-3" style="font-size:0.85rem;">
                                            <i class="bi bi-bar-chart-fill me-1"></i>
                                            <span class="d-none d-md-inline">Resultados</span>
                                        </a>
                                        <a href="{{ route('examenes.editarConfig', $evento->id_cuestionario) }}"
                                        class="btn btn-outline-primary fw-bold px-2"
                                        style="font-size:0.85rem;" title="Editar Examen">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif

                                    <form action="/evento/{{ $evento->id_evento }}/eliminar"
                                        method="POST" class="m-0 form-eliminar-evento">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger fw-bold px-2"
                                                style="font-size:0.85rem;" title="Eliminar">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>

                                @endif

                                <button type="button"
                                    class="btn btn-light rounded-circle shadow-sm"
                                    style="width:38px; height:38px; padding:0;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalAsesoria{{ $evento->id_evento }}">
                                    <i class="bi bi-chevron-right text-danger fw-bold"></i>
                                </button>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- ======= MODAL DETALLES ======= --}}
                <div class="modal fade" id="modalAsesoria{{ $evento->id_evento }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-dark text-white py-3">
                                <h5 class="modal-title fw-bold">
                                    <i class="bi bi-info-circle-fill text-danger me-2"></i> Detalles
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <small class="text-muted text-uppercase fw-bold d-block" style="font-size:0.72rem;">
                                        {{ $evento->tipo === 'asesoria' ? 'Tema de la Asesoría' : 'Título del Examen' }}
                                    </small>
                                    <h5 class="fw-bold text-dark mb-0">{{ $evento->nombre_evento }}</h5>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <small class="text-muted text-uppercase fw-bold d-block" style="font-size:0.72rem;">Tipo</small>
                                        @if($evento->tipo === 'asesoria')
                                            <span class="badge text-white fw-bold mt-1" style="background:#8C001A;">
                                                <i class="bi bi-person-video3 me-1"></i> Asesoría
                                            </span>
                                        @else
                                            <span class="badge text-white fw-bold mt-1" style="background:#1a3a5c;">
                                                <i class="bi bi-file-earmark-check me-1"></i> Examen
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted text-uppercase fw-bold d-block" style="font-size:0.72rem;">Estado</small>
                                        @if($evento->tipo === 'asesoria')
                                            <span class="badge {{ $badgeColor }} text-uppercase mt-1">
                                                {{ $evento->ases_estado ?? 'DISPONIBLE' }}
                                            </span>
                                        @else
                                            <span class="badge {{ $exBadge }} text-uppercase mt-1">
                                                {{ $evento->ex_estado ?? 'PENDIENTE' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <hr class="opacity-25">
                                @if($evento->tipo === 'asesoria')
                                    <div class="mb-3">
                                        <small class="text-muted text-uppercase fw-bold d-block" style="font-size:0.72rem;">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Lugar / Salón
                                        </small>
                                        <p class="fw-semibold mb-0 bg-light p-2 rounded border mt-1">
                                            {{ $evento->ases_lugar ?? 'No especificado' }}
                                        </p>
                                    </div>
                                    <div class="row text-center bg-light rounded py-3 g-0 border">
                                        <div class="col-4 border-end">
                                            <small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.68rem;">Fecha</small>
                                            <span class="fw-bold text-dark">
                                                {{ $evento->ases_fecha ? \Carbon\Carbon::parse($evento->ases_fecha)->format('d/m/Y') : 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="col-4 border-end">
                                            <small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.68rem;">Inicio</small>
                                            <span class="fw-bold text-success">
                                                {{ $evento->ases_inicio ? \Carbon\Carbon::parse($evento->ases_inicio)->format('h:i A') : 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="col-4">
                                            <small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.68rem;">Fin</small>
                                            <span class="fw-bold text-danger">
                                                {{ $evento->ases_fin ? \Carbon\Carbon::parse($evento->ases_fin)->format('h:i A') : 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                @if($evento->tipo === 'cuestionario')
                                {{-- Fila 1: Fechas --}}
                                <div class="row text-center bg-light rounded py-2 g-0 border mb-2">
                                    <div class="col-6 border-end">
                                        <small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.6rem;">Inicio</small>
                                        <span class="fw-bold text-dark" style="font-size: 0.85rem;">
                                            {{ $evento->ex_fecha ? \Carbon\Carbon::parse($evento->ex_fecha)->format('d/m/Y') : 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="col-6">
                                        <small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.6rem;">Cierre</small>
                                        <span class="fw-bold text-dark" style="font-size: 0.85rem;">
                                            {{ $evento->ex_fecha_cierre ? \Carbon\Carbon::parse($evento->ex_fecha_cierre)->format('d/m/Y') : 'N/A' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Fila 2: Horarios --}}
                                <div class="row text-center bg-light rounded py-2 g-0 border mb-3">
                                    <div class="col-6 border-end">
                                        <small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.6rem;">Hora Inicio</small>
                                        <span class="fw-bold text-success" style="font-size: 0.85rem;">
                                            {{ $evento->ex_inicio ? \Carbon\Carbon::parse($evento->ex_inicio)->format('h:i A') : 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="col-6">
                                        <small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.6rem;">Hora Fin</small>
                                        <span class="fw-bold text-danger" style="font-size: 0.85rem;">
                                            {{ $evento->ex_fin ? \Carbon\Carbon::parse($evento->ex_fin)->format('h:i A') : 'N/A' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Intentos --}}
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-arrow-repeat text-muted"></i>
                                    <span class="text-muted" style="font-size:0.85rem;">
                                        Intentos permitidos: <strong>{{ $evento->ex_oportunidades ?? 1 }}</strong>
                                    </span>
                                </div>
                            @endif
                                <hr class="opacity-25">
                                <div class="text-muted" style="font-size:0.75rem;">
                                    <i class="bi bi-clock me-1"></i>
                                    Registrado el {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}
                                </div>
                            </div>
                            <div class="modal-footer bg-light py-2">
                                <button type="button" class="btn btn-secondary btn-sm fw-bold px-3"
                                        data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ======= MODALES LISTA (solo asesoría) ======= --}}
                @if($evento->tipo === 'asesoria')
                    <div class="modal fade" id="modalLista{{ $evento->id_evento }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-dark text-white py-3">
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-card-checklist text-danger me-2"></i>
                                        Lista — {{ $evento->nombre_evento }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    @php
                                        $asistentes = DB::table('asistencia_asesoria')
                                            ->join('persona', 'asistencia_asesoria.correo_persona', '=', 'persona.correo')
                                            ->where('asistencia_asesoria.id_asesoria', $evento->id_asesoria)
                                            ->select(
                                                'persona.nombre', 'persona.apellidoPa', 'persona.apellidoMa',
                                                'persona.correo', 'asistencia_asesoria.asistio',
                                                'asistencia_asesoria.id_asistencia'
                                            )
                                            ->get();

                                        $correosEnLista = $asistentes->pluck('correo')->toArray();

                                        $alumnosDisponibles = DB::table('inscripcion')
                                            ->join('persona', 'inscripcion.correo_estudiante', '=', 'persona.correo')
                                            ->where('inscripcion.id_curso', $curso->id_curso)
                                            ->whereNotIn('inscripcion.correo_estudiante', $correosEnLista)
                                            ->select('persona.nombre', 'persona.apellidoPa', 'persona.apellidoMa', 'persona.correo')
                                            ->get();
                                    @endphp

                                    @if($alumnosDisponibles->isNotEmpty())
                                        <div class="d-flex justify-content-end mb-3">
                                            <button type="button" class="btn btn-sm btn-danger fw-bold shadow-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalAgregarAlumno{{ $evento->id_evento }}">
                                                <i class="bi bi-person-plus-fill me-1"></i> Agregar Alumno
                                            </button>
                                        </div>
                                    @endif

                                    @if($asistentes->isEmpty())
                                        <div class="text-center py-4">
                                            <i class="bi bi-people display-4 text-muted"></i>
                                            <p class="text-muted mt-2">Ningún estudiante se ha registrado aún.</p>
                                        </div>
                                    @else
                                        <p class="text-muted mb-3">
                                            <i class="bi bi-people-fill text-danger me-1"></i>
                                            <strong>{{ $asistentes->count() }}</strong> estudiante(s)
                                        </p>
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nombre</th>
                                                        <th>Correo</th>
                                                        <th class="text-center">Estado</th>
                                                        <th class="text-center">Acción</th>  {{-- ← agrega esto --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($asistentes as $i => $asistente)
                                                        <tr>
                                                            <td class="text-muted fw-bold">{{ $i + 1 }}</td>
                                                            <td class="fw-semibold">
                                                                {{ $asistente->nombre }} {{ $asistente->apellidoPa }} {{ $asistente->apellidoMa }}
                                                            </td>
                                                            <td class="text-muted" style="font-size:0.85rem;">{{ $asistente->correo }}</td>
                                                            <td class="text-center">
                                                                <span class="badge
                                                                    @if($asistente->asistio == 'ASISTIO') bg-success
                                                                    @elseif($asistente->asistio == 'FALTO') bg-danger
                                                                    @else bg-warning text-dark @endif">
                                                                    {{ $asistente->asistio }}
                                                                </span>
                                                            </td>
                                                            {{-- ← agrega esta columna --}}
                                                            <td class="text-center">
                                                                <form action="{{ route('asesorias.quitarAsistente', $asistente->id_asistencia) }}"
                                                                    method="POST" class="form-quitar-asistente m-0">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-2"
                                                                            title="Quitar de la lista">
                                                                        <i class="bi bi-person-x-fill"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer bg-light py-2">
                                    <button type="button" class="btn btn-secondary btn-sm fw-bold px-3"
                                            data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal agregar alumno --}}
                    <div class="modal fade" id="modalAgregarAlumno{{ $evento->id_evento }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-dark text-white py-3">
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-person-plus-fill text-danger me-2"></i> Agregar Alumno
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <form action="{{ route('asesorias.agregarManualmente', $evento->id_asesoria) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-secondary">Selecciona el Alumno</label>
                                            <select name="correo_persona" class="form-select border-2" required>
                                                <option value="" disabled selected>-- Elige un alumno --</option>
                                                @foreach($alumnosDisponibles as $alumno)
                                                    <option value="{{ $alumno->correo }}">
                                                        {{ $alumno->nombre }} {{ $alumno->apellidoPa }} {{ $alumno->apellidoMa }}
                                                        — {{ $alumno->correo }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-light border fw-bold px-3"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger fw-bold px-4 shadow-sm">
                                                <i class="bi bi-person-check-fill me-1"></i> Agregar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(($evento->ases_requiere_evidencia ?? 0) == 1 && $evento->id_asesoria)
                    <div class="modal fade" id="modalEvidencia{{ $evento->id_asesoria }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-dark text-white py-3">
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-file-earmark-arrow-up text-danger me-2"></i>
                                        Subir Evidencia
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <p class="text-muted mb-3" style="font-size:0.9rem;">
                                        <i class="bi bi-info-circle text-danger me-1"></i>
                                        Sube tu evidencia en formato <strong>PDF</strong>. Máximo <strong>5MB</strong>.
                                    </p>
                                    <form action="{{ route('evidencia.store', $evento->id_asesoria) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-secondary">
                                                Selecciona tu archivo PDF
                                            </label>
                                            <input type="file"
                                                name="archivo"
                                                class="form-control border-2"
                                                accept=".pdf"
                                                required>
                                            <small class="text-muted">Solo archivos .pdf — máximo 5MB</small>
                                        </div>
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-light border fw-bold"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger fw-bold px-4 shadow-sm">
                                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Subir
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @empty
                <div class="text-center py-5">
                    <i class="bi bi-folder2-open display-4 text-muted"></i>
                    <p class="text-muted mt-2">No hay actividades publicadas para este curso.</p>
                </div>
            @endforelse

            {{-- ======= SWEETALERT ======= --}}
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
            // Mensajes de sesión
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Listo!',
                    text: @json(session('success')),
                    confirmButtonColor: '#8C001A',
                    confirmButtonText: 'Aceptar',
                    timer: 3000,
                    timerProgressBar: true,
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json(session('error')),
                    confirmButtonColor: '#8C001A',
                    confirmButtonText: 'Aceptar',
                });
            @endif

            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: @json(session('info')),
                    confirmButtonColor: '#8C001A',
                    confirmButtonText: 'Aceptar',
                });
            @endif

            // Confirmar eliminar
            document.querySelectorAll('.form-eliminar-evento').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Eliminar actividad?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#8C001A',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="bi bi-trash3-fill me-1"></i> Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // Confirmar cancelar asistencia
            document.querySelectorAll('.form-cancelar-asistencia').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Cancelar asistencia?',
                        text: 'Se eliminará tu registro en esta asesoría.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#8C001A',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, cancelar',
                        cancelButtonText: 'No',
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // Confirmar salir del curso
            document.querySelectorAll('.form-salir-curso').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Salir del curso?',
                        text: 'Perderás tu inscripción y deberás volver a unirte.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#8C001A',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, salir',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // Confirmar quitar asistente
            document.querySelectorAll('.form-quitar-asistente').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Quitar alumno?',
                        text: 'Se eliminará de la lista de asistencia.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#8C001A',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, quitar',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
            </script>

            </div>
        </div>
    </div>
    @endsection

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>