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

    /* ── Tarjeta principal de la pregunta ── */
    .card-principal-foro {
        background: white;
        border: 1px solid #e9ecef;
        border-left: 7px solid var(--cetis-rojo);
        border-radius: 14px;
        overflow: hidden;
    }

    /* ── Avatar ── */
    .avatar-foro {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 800;
        font-size: 1rem;
        background: linear-gradient(135deg, #8C001A, #c0002a);
        color: white;
        box-shadow: 0 3px 10px rgba(140, 0, 26, 0.3);
        flex-shrink: 0;
    }

    /* ── Tarjeta de respuesta ── */
    .respuesta-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        background: white;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .respuesta-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 18px rgba(0,0,0,0.07) !important;
    }

    /* Avatar pequeño para respuestas */
    .avatar-respuesta {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 800;
        font-size: 0.78rem;
        background: linear-gradient(135deg, #343a40, #555);
        color: white;
        flex-shrink: 0;
        width: 36px;
        height: 36px;
    }

    /* ── Formulario de respuesta ── */
    .card-responder {
        border: 2px solid #e9ecef;
        border-radius: 14px;
        background: white;
    }
    .card-responder textarea:focus {
        border-color: var(--cetis-rojo) !important;
        box-shadow: 0 0 0 3px rgba(140, 0, 26, 0.1);
        outline: none;
    }

    /* ── Botón enviar ── */
    .btn-cetis {
        background: linear-gradient(135deg, #8C001A, #c0002a);
        color: white;
        font-weight: 700;
        border-radius: 8px;
        padding: 10px 24px;
        border: none;
        transition: opacity 0.2s;
    }
    .btn-cetis:hover {
        opacity: 0.9;
        color: white;
    }

    /* ── Estado vacío ── */
    .respuestas-vacio {
        border: 2px dashed #dee2e6;
        border-radius: 14px;
        background: white;
    }
</style>

<div class="container-fluid mt-4 px-4">

    {{-- ── CABECERA DEL CURSO ── --}}
    <div class="tarjeta-curso-interna shadow-sm bg-white mb-4">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h2 class="fw-bold m-0 text-uppercase">{{ $curso->nombre_curso }}</h2>
                <p class="text-muted mb-1 mt-1">Impartido por: <strong>{{ $curso->nombre_asesor }}</strong></p>
                <small class="text-secondary"><i class="bi bi-book me-1 text-danger"></i> Materia: {{ $curso->materia }}</small>
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
                    <a href="{{ route('reportes.index', $curso->id_curso) }}" class="nav-link shadow-sm">
                        <i class="bi bi-file-earmark-text me-2 text-danger"></i> REPORTES
                    </a>
                @endif
            </div>
        </div>

        {{-- ── CONTENIDO PRINCIPAL ── --}}
        <div class="col-md-9">

            {{-- ── PREGUNTA ORIGINAL ── --}}
            <div class="card-principal-foro shadow-sm mb-4">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">

                        {{-- Autor de la pregunta --}}
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-foro" style="width: 48px; height: 48px;">
                                {{ strtoupper(substr($pregunta->autor->nombre ?? $pregunta->correo_persona, 0, 1)) }}
                            </div>
                            <div>
                                <p class="fw-bold mb-0" style="color: #212529; font-size: 0.95rem;">
                                    {{ trim(($pregunta->autor->nombre ?? '') . ' ' . ($pregunta->autor->apellidoPa ?? '') . ' ' . ($pregunta->autor->apellidoMa ?? '')) ?: $pregunta->correo_persona }}
                                </p>
                                <small class="text-muted" style="font-size: 0.78rem;">
                                    <i class="bi bi-calendar3 me-1 text-danger"></i>
                                    {{ \Carbon\Carbon::parse($pregunta->fecha_pregunta)->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>

                        {{-- Botón volver --}}
                        <a href="{{ route('foro.index', $curso->id_curso) }}"
                           class="btn btn-sm fw-bold px-3 py-2"
                           style="border: 2px solid var(--cetis-rojo); color: var(--cetis-rojo); border-radius: 8px; background: white; text-decoration: none; font-size: 0.82rem;">
                            <i class="bi bi-arrow-left me-1"></i> Volver al foro
                        </a>
                    </div>

                    {{-- Texto de la pregunta --}}
                    <div class="ps-1">
                        <span class="badge mb-2 px-2 py-1" style="background: var(--cetis-rojo-light); color: var(--cetis-rojo); font-size: 0.72rem; font-weight: 700; border-radius: 6px; letter-spacing: 0.5px;">
                            <i class="bi bi-question-circle me-1"></i> CONSULTA
                        </span>
                        <h4 class="fw-bold text-dark mb-0" style="font-size: 1.25rem; line-height: 1.4;">
                            {{ $pregunta->texto_pregunta }}
                        </h4>
                    </div>
                </div>

                {{-- Pie de la tarjeta --}}
                <div class="px-4 py-2 d-flex align-items-center gap-2"
                     style="background: #fafafa; border-top: 1px solid #f0f0f0;">
                    <span class="badge-respuestas-sm">
                        <i class="bi bi-chat-square-text me-1"></i>
                        {{ $pregunta->respuestas->count() }} {{ $pregunta->respuestas->count() == 1 ? 'respuesta' : 'respuestas' }}
                    </span>
                </div>
            </div>

            {{-- ── SECCIÓN DE RESPUESTAS ── --}}
            <h6 class="fw-bold text-uppercase mb-3"
                style="color: #555; font-size: 0.78rem; letter-spacing: 1px;">
                <i class="bi bi-chat-left-text me-2" style="color: var(--cetis-rojo);"></i>
                Respuestas ({{ $pregunta->respuestas->count() }})
            </h6>

            @forelse($pregunta->respuestas as $respuesta)
                <div class="respuesta-card shadow-sm mb-3 p-3">
                    <div class="d-flex align-items-start gap-3">

                        <div class="avatar-respuesta">
                            {{ strtoupper(substr($respuesta->autor->nombre ?? $respuesta->correo_persona, 0, 1)) }}
                        </div>

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 mb-1">
                                <span class="fw-bold" style="font-size: 0.9rem; color: #212529;">
                                    {{ trim(($respuesta->autor->nombre ?? '') . ' ' . ($respuesta->autor->apellidoPa ?? '') . ' ' . ($respuesta->autor->apellidoMa ?? '')) ?: $respuesta->correo_persona }}
                                </span>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($respuesta->fecha_respuesta)->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <p class="mb-0 text-dark" style="font-size: 0.92rem; line-height: 1.6;">
                                {{ $respuesta->texto_respuesta }}
                            </p>
                        </div>

                    </div>
                </div>
            @empty
                <div class="respuestas-vacio text-center py-5 mb-4">
                    <div style="font-size: 2.8rem; opacity: 0.2; color: var(--cetis-rojo);">
                        <i class="bi bi-chat-square-dots"></i>
                    </div>
                    <p class="fw-bold mb-1 mt-3" style="color: #555;">Aún no hay respuestas</p>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">¡Sé el primero en aportar a esta consulta!</p>
                </div>
            @endforelse

            {{-- ── FORMULARIO PARA RESPONDER ── --}}
            <div class="card-responder shadow-sm mt-4 p-4">
                <h6 class="fw-bold mb-3" style="color: #212529; font-size: 0.95rem;">
                    <i class="bi bi-pencil-square me-2" style="color: var(--cetis-rojo);"></i>Tu Respuesta
                </h6>
                <form action="{{ route('respuesta.store', [$curso->id_curso, $pregunta->id_pregunta_foro]) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="texto_respuesta"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Escribe tu respuesta de manera clara y respetuosa..."
                                  required
                                  style="border: 2px solid #dee2e6; border-radius: 10px; font-size: 0.92rem; resize: none; transition: border-color 0.2s;"></textarea>
                        <small class="text-muted mt-1 d-block" style="font-size: 0.78rem;">
                            <i class="bi bi-info-circle me-1"></i> Tu respuesta será visible para todos en el curso.
                        </small>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-cetis shadow-sm px-4">
                            <i class="bi bi-send me-2"></i> ENVIAR RESPUESTA
                        </button>
                    </div>
                </form>
            </div>

        </div>{{-- /col-md-9 --}}
    </div>{{-- /row --}}
</div>{{-- /container-fluid --}}


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

<style>
    .badge-respuestas-sm {
        background: var(--cetis-rojo-light);
        color: var(--cetis-rojo);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.78rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
    }
</style>

@endsection