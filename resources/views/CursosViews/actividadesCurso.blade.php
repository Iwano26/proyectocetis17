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

    @extends('layouts.app')

    @section('content')
    <div class="container-fluid mt-0 px-4">
        
        {{-- CABECERA DEL CURSO --}}
        <div class="tarjeta-curso-interna shadow-sm bg-white mb-4">
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
                                <form action="{{ route('cursos.salir', $curso->id_curso) }}" method="POST" class="d-inline">
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
                            <button class="btn btn-primary fw-bold px-4" disabled>
                                <i class="bi bi-person-badge me-2"></i> MODO GESTIÓN
                            </button>
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
                            {{-- <a href="{{ route('examenes.create', $curso->id_curso) }}" class="btn btn-danger fw-bold shadow-sm">
                                <i class="bi bi-file-earmark-check me-1"></i> + Crear Examen
                            </a> --}}
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
                    $badgeColor = match($evento->ases_estado ?? 'DISPONIBLE') {
                        'DISPONIBLE' => 'bg-success',
                        'EN_CURSO'   => 'bg-warning text-dark',
                        'TERMINADA'  => 'bg-dark',
                        default      => 'bg-secondary'
                    };
                @endphp

                <div class="card mb-3 shadow-sm border-0 border-start border-4 {{ $borde }}">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

                        {{-- Lado Izquierdo --}}
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h6 class="fw-bold mb-0 text-dark">{{ $evento->nombre_evento }}</h6>
                                @if($evento->tipo === 'asesoria')
                                    <span class="badge {{ $badgeColor }} text-uppercase" style="font-size: 0.7rem;">
                                        {{ $evento->ases_estado ?? 'DISPONIBLE' }}
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge bg-secondary text-uppercase" style="font-size: 0.75rem;">
                                    {{ $evento->tipo }}
                                </span>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Publicado: {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}
                                </span>
                                @if($evento->tipo === 'asesoria' && $evento->ases_lugar)
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        {{ $evento->ases_lugar }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Bloque Central: Contador --}}
                        @if($evento->tipo === 'asesoria')
                            <div class="text-center px-4 border-start border-end d-none d-sm-block">
                                <span class="d-block text-muted small text-uppercase fw-bold" style="font-size: 0.65rem;">
                                    Asistencias Confirmadas
                                </span>
                                <span class="badge bg-danger rounded-pill fs-6 px-3 py-1 fw-bold shadow-sm mt-1">
                                    <i class="bi bi-people-fill me-1"></i> {{ $evento->total_asistentes ?? 0 }}
                                </span>
                            </div>
                        @endif

                        {{-- Lado Derecho: Botones --}}
                        <div class="d-flex align-items-center gap-2">
                            @if(Auth::user()->rol === 'Estudiante')
                                @if($evento->tipo === 'asesoria')
                                    @if(($evento->ya_inscrito ?? 0) > 0)
                                        <form action="{{ route('asesorias.cancelar', $evento->id_evento) }}" method="POST" class="m-0"
                                            onsubmit="return confirm('¿Seguro que deseas cancelar tu asistencia?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-success fw-bold px-3"
                                                @if(in_array($evento->ases_estado ?? '', ['TERMINADA', 'CANCELADA', 'EN_CURSO'])) disabled @endif>
                                                <i class="bi bi-check-circle-fill me-1"></i> Registrado
                                            </button>
                                        </form>
                                    @else
                                        @if(($evento->ya_inscrito ?? 0) > 0)
                                            <form action="{{ route('asesorias.cancelar', $evento->id_evento) }}" method="POST" class="m-0"
                                                onsubmit="return confirm('¿Seguro que deseas cancelar tu asistencia?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-success fw-bold px-3"
                                                    @if(in_array($evento->ases_estado ?? '', ['TERMINADA', 'CANCELADA', 'EN_CURSO'])) disabled @endif>
                                                    <i class="bi bi-check-circle-fill me-1"></i> Registrado
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('asesorias.unirse', $evento->id_evento) }}" method="POST" class="m-0">
                                                @csrf
                                                @php
                                                    $desactivar = in_array($evento->ases_estado ?? '', ['TERMINADA', 'CANCELADA', 'EN_CURSO'])
                                                            || ($evento->bloquear_inscripcion ?? false);
                                                @endphp
                                                <button type="submit"
                                                    class="btn btn-sm fw-bold px-3 shadow-sm {{ $desactivar ? 'btn-secondary' : 'btn-outline-danger' }}"
                                                    @if($desactivar) disabled @endif
                                                    title="{{ ($evento->bloquear_inscripcion ?? false) ? 'Inscripción cerrada: menos de 1 hora para el inicio sin asistentes confirmados' : '' }}">
                                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                                    {{ ($evento->bloquear_inscripcion ?? false) ? 'Cerrado' : 'Unirse' }}
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @endif

                            @elseif(Auth::user()->rol === 'Asesor' || Auth::user()->rol === 'Administrador')
                                @if($evento->tipo === 'asesoria')
                                    <button type="button" 
                                        class="btn btn-sm btn-dark fw-bold px-2 shadow-sm" 
                                        title="Lista de Asistencia"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalLista{{ $evento->id_evento }}">
                                        <i class="bi bi-card-checklist"></i>
                                        <span class="d-none d-md-inline ms-1">Lista</span>
                                    </button>
                                    <a href="/evento/{{ $evento->id_evento }}/editar-asesoria"
                                    class="btn btn-sm btn-outline-primary fw-bold px-2 shadow-sm" title="Editar Asesoría">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @endif
                                <form action="/evento/{{ $evento->id_evento }}/eliminar" method="POST" class="m-0"
                                    onsubmit="return confirm('¿Seguro que deseas eliminar esta actividad?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-2 shadow-sm" title="Eliminar">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            @endif

                            <button type="button" class="btn btn-light btn-sm rounded-circle shadow-sm"
                                data-bs-toggle="modal" data-bs-target="#modalAsesoria{{ $evento->id_evento }}">
                                <i class="bi bi-chevron-right text-danger fw-bold"></i>
                            </button>
                        </div>

                    </div>
                </div>

                {{-- ======= MODAL ======= --}}
                <div class="modal fade" id="modalAsesoria{{ $evento->id_evento }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">

                            <div class="modal-header bg-dark text-white py-3">
                                <h5 class="modal-title fw-bold">
                                    <i class="bi bi-info-circle-fill text-danger me-2"></i> Detalles de la Actividad
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body p-4">

                                {{-- Título --}}
                                <div class="mb-3">
                                    <small class="text-muted text-uppercase d-block fw-bold" style="font-size: 0.75rem;">
                                        Tema de la Asesoría
                                    </small>
                                    <h5 class="fw-bold text-dark mb-0">{{ $evento->nombre_evento }}</h5>
                                </div>

                                {{-- Tipo y Estado --}}
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <small class="text-muted text-uppercase d-block fw-bold" style="font-size: 0.75rem;">
                                            Tipo
                                        </small>
                                        <span class="badge bg-secondary text-uppercase mt-1">{{ $evento->tipo }}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted text-uppercase d-block fw-bold" style="font-size: 0.75rem;">
                                            Estado
                                        </small>
                                        <span class="badge {{ $badgeColor }} text-uppercase mt-1">
                                            {{ $evento->ases_estado ?? 'DISPONIBLE' }}
                                        </span>
                                    </div>
                                </div>

                                <hr class="opacity-25">

                                @if($evento->tipo === 'asesoria')

                                    {{-- Lugar --}}
                                    <div class="mb-3">
                                        <small class="text-muted text-uppercase d-block fw-bold" style="font-size: 0.75rem;">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Lugar / Salón
                                        </small>
                                        <p class="text-dark fw-semibold mb-0 bg-light p-2 rounded border mt-1">
                                            {{ $evento->ases_lugar ?? 'No especificado' }}
                                        </p>
                                    </div>

                                    {{-- Fecha y Horas --}}
                                    <div class="row text-center bg-light rounded py-3 g-0 border">
                                        <div class="col-4 border-end">
                                            <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">
                                                Fecha
                                            </small>
                                            <span class="fw-bold text-dark" style="font-size: 0.95rem;">
                                                {{ $evento->ases_fecha
                                                    ? \Carbon\Carbon::parse($evento->ases_fecha)->format('d/m/Y')
                                                    : 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="col-4 border-end">
                                            <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">
                                                Inicio
                                            </small>
                                            <span class="fw-bold text-success" style="font-size: 0.95rem;">
                                                {{ $evento->ases_inicio
                                                    ? \Carbon\Carbon::parse($evento->ases_inicio)->format('h:i A')
                                                    : 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="col-4">
                                            <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">
                                                Fin
                                            </small>
                                            <span class="fw-bold text-danger" style="font-size: 0.95rem;">
                                                {{ $evento->ases_fin
                                                    ? \Carbon\Carbon::parse($evento->ases_fin)->format('h:i A')
                                                    : 'N/A' }}
                                            </span>
                                        </div>
                                    </div>

                                @endif

                                <hr class="opacity-25">

                                <div class="text-muted" style="font-size: 0.75rem;">
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


                {{-- ======= MODAL LISTA DE ASISTENCIA ======= --}}
                <div class="modal fade" id="modalLista{{ $evento->id_evento }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow">

                            <div class="modal-header bg-dark text-white py-3">
                                <h5 class="modal-title fw-bold">
                                    <i class="bi bi-card-checklist text-danger me-2"></i>
                                    Lista de Asistencia — {{ $evento->nombre_evento }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body p-4">

                                @php
                                    // Alumnos ya en la lista
                                    $asistentes = DB::table('asistencia_asesoria')
                                        ->join('persona', 'asistencia_asesoria.correo_persona', '=', 'persona.correo')
                                        ->where('asistencia_asesoria.id_asesoria', $evento->id_asesoria)
                                        ->select(
                                            'persona.nombre',
                                            'persona.apellidoPa',
                                            'persona.apellidoMa',
                                            'persona.correo',
                                            'asistencia_asesoria.asistio',
                                            'asistencia_asesoria.id_asistencia'
                                        )
                                        ->get();

                                    // Alumnos inscritos al curso que NO están ya en la lista
                                    $correosEnLista = $asistentes->pluck('correo')->toArray();

                                    $alumnosDisponibles = DB::table('inscripcion')
                                        ->join('persona', 'inscripcion.correo_estudiante', '=', 'persona.correo')
                                        ->where('inscripcion.id_curso', $curso->id_curso)
                                        ->whereNotIn('inscripcion.correo_estudiante', $correosEnLista)
                                        ->select(
                                            'persona.nombre',
                                            'persona.apellidoPa',
                                            'persona.apellidoMa',
                                            'persona.correo'
                                        )
                                        ->get();
                                @endphp

                                {{-- Botón agregar alumno --}}
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
                                        <strong>{{ $asistentes->count() }}</strong> estudiante(s) registrado(s)
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nombre Completo</th>
                                                    <th>Correo</th>
                                                    <th class="text-center">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($asistentes as $i => $asistente)
                                                    <tr>
                                                        <td class="text-muted fw-bold">{{ $i + 1 }}</td>
                                                        <td class="fw-semibold">
                                                            {{ $asistente->nombre }}
                                                            {{ $asistente->apellidoPa }}
                                                            {{ $asistente->apellidoMa }}
                                                        </td>
                                                        <td class="text-muted" style="font-size: 0.85rem;">
                                                            {{ $asistente->correo }}
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge
                                                                @if($asistente->asistio == 'ASISTIO') bg-success
                                                                @elseif($asistente->asistio == 'FALTO') bg-danger
                                                                @else bg-warning text-dark
                                                                @endif">
                                                                {{ $asistente->asistio }}
                                                            </span>
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
                {{-- ======= FIN MODAL LISTA ======= --}}

                {{-- ======= MODAL AGREGAR ALUMNO ======= --}}
                <div class="modal fade" id="modalAgregarAlumno{{ $evento->id_evento }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">

                            <div class="modal-header bg-dark text-white py-3">
                                <h5 class="modal-title fw-bold">
                                    <i class="bi bi-person-plus-fill text-danger me-2"></i> Agregar Alumno a Lista
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
                {{-- ======= FIN MODAL AGREGAR ALUMNO ======= --}}
                {{-- ======= FIN MODAL ======= --}}

            @empty
                <div class="text-center py-5">
                    <i class="bi bi-folder2-open display-4 text-muted"></i>
                    <p class="text-muted mt-2">No hay actividades publicadas para este curso.</p>
                </div>
            @endforelse

            </div>
        </div>
    </div>
    @endsection

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>