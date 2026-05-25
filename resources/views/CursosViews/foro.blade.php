@extends('layouts.app')

@section('content')

<style>
    :root {
        --cetis-rojo: #8C001A;
        --cetis-rojo-light: #fff0f2;
    }

    /* ── Cabecera del curso ── */
    .tarjeta-curso-interna {
        background-color: white;
        border: 2px solid var(--cetis-rojo);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
    }

    /* ── Menú lateral ── */
    .opciones-curso .nav-link {
        color: #333;
        font-weight: 700;
        border: 1px solid #dee2e6;
        margin-bottom: 8px;
        border-radius: 8px;
        padding: 15px;
        text-align: left;
        background-color: white;
        display: block;
        text-decoration: none;
        transition: all 0.2s;
    }
    .opciones-curso .nav-link:hover {
        background-color: var(--cetis-rojo-light);
        color: var(--cetis-rojo);
    }
    .opciones-curso .nav-link.active {
        background-color: var(--cetis-rojo) !important;
        color: white !important;
        border-color: var(--cetis-rojo) !important;
    }
    .opciones-curso .nav-link.active i {
        color: white !important;
    }

    /* ── Tarjeta de pregunta ── */
    .tarjeta-pregunta-foro {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        border-left: 5px solid var(--cetis-rojo);
        background: white;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .tarjeta-pregunta-foro:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(140, 0, 26, 0.12) !important;
    }

    /* Icono circular */
    .foro-icono {
        width: 50px;
        height: 50px;
        min-width: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8C001A, #c0002a);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 3px 10px rgba(140, 0, 26, 0.3);
    }

    /* Badge de respuestas */
    .badge-respuestas {
        background: linear-gradient(135deg, #8C001A, #c0002a);
        color: white;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 2px 8px rgba(140, 0, 26, 0.25);
    }

    /* Botón Ver */
    .btn-ver-foro {
        background-color: white;
        border: 2px solid var(--cetis-rojo);
        color: var(--cetis-rojo);
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 7px 18px;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-block;
    }
    .btn-ver-foro:hover {
        background-color: var(--cetis-rojo);
        color: white;
    }

    /* Botón flecha circular */
    .btn-detalle-circular {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: var(--cetis-rojo-light);
        border: 1px solid #f5c6cb;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-detalle-circular:hover {
        background-color: var(--cetis-rojo);
    }
    .btn-detalle-circular:hover i {
        color: white !important;
    }

    /* Estado vacío */
    .foro-vacio {
        border: 2px dashed #dee2e6;
        border-radius: 16px;
        background: white;
    }
</style>

<div class="container-fluid mt-0 px-4">

    {{-- ── CABECERA DEL CURSO ── --}}
    <div class="tarjeta-curso-interna shadow-sm bg-white mb-4 mt-4">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h2 class="fw-bold m-0 text-uppercase">{{ $curso->nombre_curso }}</h2>
                <p class="text-muted mb-0">Impartido por: <strong>{{ $curso->nombre_asesor }}</strong></p>
                <small class="text-secondary">
                    <i class="bi bi-book me-1 text-danger"></i> Materia: {{ $curso->materia }}
                </small>
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
                                <button type="submit" class="btn btn-dark fw-bold px-4"
                                        {{ $curso->estado != 'ACTIVO' ? 'disabled' : '' }}>
                                    <i class="bi bi-door-open-fill me-2"></i> INGRESAR AL CURSO
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- ── MENÚ LATERAL ── --}}
        <div class="col-md-3">
            <div class="nav flex-column opciones-curso">
                <a href="{{ route('cursos.show', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-info-circle me-2 text-danger"></i> INFORMACIÓN
                </a>
                <a href="{{ route('curso.eventos', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-list-task me-2 text-danger"></i> ACTIVIDADES
                </a>
                <a href="{{ route('foro.index', $curso->id_curso) }}" class="nav-link shadow-sm active">
                    <i class="bi bi-chat-dots me-2"></i> FORO
                </a>
                @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Asesor')
                    <a href="{{ route('reportes.index', $curso->id_curso) }}" class="nav-link shadow-sm border-danger">
                        <i class="bi bi-file-earmark-text me-2 text-danger"></i> REPORTES
                    </a>
                @endif
            </div>
        </div>

        {{-- ── CONTENIDO DEL FORO ── --}}
        <div class="col-md-9">

            {{-- Encabezado --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold m-0" style="color: #212529; font-size: 1.4rem;">
                        <i class="bi bi-chat-dots me-2" style="color: var(--cetis-rojo);"></i>Foro de Consultas
                    </h4>
                    <small class="text-muted" style="font-size: 0.82rem;">
                        {{ $preguntas->count() }} {{ $preguntas->count() == 1 ? 'consulta publicada' : 'consultas publicadas' }}
                    </small>
                </div>
                <button class="btn fw-bold shadow-sm px-4 py-2 text-white"
                        data-bs-toggle="modal" data-bs-target="#modalPregunta"
                        style="background: linear-gradient(135deg, #8C001A, #c0002a); border: none; border-radius: 10px; font-size: 0.9rem;">
                    <i class="bi bi-plus-circle me-2"></i> CREAR CONSULTA
                </button>
            </div>

            {{-- ── LISTADO DE PREGUNTAS ── --}}
            @forelse($preguntas as $pregunta)
                <div class="tarjeta-pregunta-foro shadow-sm p-3 mb-3">
                    <div class="row align-items-center g-0">

                        {{-- Icono + texto --}}
                        <div class="col-md-7 d-flex align-items-center pe-3">
                            <div class="foro-icono me-3 flex-shrink-0">
                                <i class="bi bi-chat-left-text text-white" style="font-size: 1.1rem;"></i>
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="fw-bold text-uppercase mb-1 text-truncate"
                                    style="color: #212529; font-size: 0.95rem;"
                                    title="{{ $pregunta->texto_pregunta }}">
                                    {{ $pregunta->texto_pregunta }}
                                </h6>
                                <div class="d-flex align-items-center flex-wrap gap-2" style="font-size: 0.8rem; color: #6c757d;">
                                    <span><i class="bi bi-person-fill me-1 text-danger"></i>{{ trim(($pregunta->autor->nombre ?? '') . ' ' . ($pregunta->autor->apellidoPa ?? '') . ' ' . ($pregunta->autor->apellidoMa ?? '')) ?: 'Usuario' }}</span>
                                    <span>·</span>
                                    <span><i class="bi bi-calendar3 me-1 text-danger"></i>{{ \Carbon\Carbon::parse($pregunta->fecha_pregunta)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Contador de respuestas --}}
                        <div class="col-md-3 text-center py-2 mt-2 mt-md-0" style="border-left: 1px dashed #dee2e6;">
                            <span class="d-block text-uppercase mb-2"
                                  style="font-size: 0.62rem; font-weight: 800; letter-spacing: 1px; color: #adb5bd;">
                                Respuestas
                            </span>
                            <span class="badge-respuestas">
                                <i class="bi bi-chat-square-text"></i>
                                {{ $pregunta->respuestas_count ?? 0 }}
                            </span>
                        </div>

                        {{-- Acciones --}}
                        <div class="col-md-2 d-flex align-items-center justify-content-md-end gap-2 mt-2 mt-md-0 ps-md-3">
                            <a href="{{ route('foro.show', [$curso->id_curso, $pregunta->id_pregunta_foro]) }}"
                               class="btn-ver-foro">Ver</a>
                            <a href="{{ route('foro.show', [$curso->id_curso, $pregunta->id_pregunta_foro]) }}"
                               class="btn-detalle-circular">
                                <i class="bi bi-chevron-right text-danger fw-bold" style="font-size: 0.8rem;"></i>
                            </a>
                        </div>

                    </div>
                </div>
            @empty
                <div class="foro-vacio text-center py-5">
                    <div class="mb-3" style="font-size: 3rem; opacity: 0.25; color: var(--cetis-rojo);">
                        <i class="bi bi-chat-square-dots"></i>
                    </div>
                    <p class="fw-bold mb-1" style="color: #555;">Aún no hay consultas publicadas</p>
                    <p class="text-muted mb-3" style="font-size: 0.88rem;">¡Sé el primero en hacer una pregunta!</p>
                    <button class="btn fw-bold px-4 text-white shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#modalPregunta"
                            style="background: linear-gradient(135deg, #8C001A, #c0002a); border: none; border-radius: 8px;">
                        <i class="bi bi-plus-circle me-2"></i> Crear primera consulta
                    </button>
                </div>
            @endforelse

        </div>{{-- /col-md-9 --}}
    </div>{{-- /row --}}
</div>{{-- /container-fluid --}}


{{-- ── MODAL: NUEVA CONSULTA ── --}}
<div class="modal fade" id="modalPregunta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header text-white py-3" style="background: linear-gradient(135deg, #1a1a1a, #333);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-pencil-square me-2 text-danger"></i> Nueva Consulta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pregunta.store', $curso->id_curso) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <label class="form-label fw-bold text-secondary mb-2" style="font-size: 0.9rem;">
                        <i class="bi bi-question-circle me-1 text-danger"></i> ¿Cuál es tu duda?
                    </label>
                    <textarea name="texto_pregunta" class="form-control" rows="4"
                              placeholder="Escribe tu pregunta con el mayor detalle posible..."
                              required
                              style="border: 2px solid #dee2e6; border-radius: 10px; font-size: 0.95rem; resize: none;"></textarea>
                    <small class="text-muted mt-1 d-block" style="font-size: 0.78rem;">
                        <i class="bi bi-info-circle me-1"></i> Tu pregunta será visible para todos en el curso.
                    </small>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light border fw-bold btn-sm px-3"
                            data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn fw-bold btn-sm px-4 text-white shadow-sm"
                            style="background: linear-gradient(135deg, #8C001A, #c0002a); border: none; border-radius: 8px;">
                        <i class="bi bi-send me-1"></i> PUBLICAR
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ── SWEETALERT ── --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
</script>

@endsection