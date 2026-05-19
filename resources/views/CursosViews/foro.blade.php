@extends('layouts.app')

@section('content')

<style>
    :root { 
        --cetis-rojo: #8C001A; 
        --cetis-primary: #8C001A;
    }
    body { 
        background-color: #f8f9fa; 
    }

    /* CABECERA CON ESTILO INSTITUCIONAL IDÉNTICO */
    .tarjeta-curso-interna {
        background-color: white; 
        border: 2px solid var(--cetis-rojo);
        border-radius: 12px; 
        padding: 20px; 
        margin-bottom: 15px;
    }

    /* MENÚ LATERAL CON LAS MISMAS PROPIEDADES */
    .opciones-curso .nav-link {
        color: #333;
        font-weight: 700;
        border: 1px solid #dee2e6;
        margin-bottom: 8px;
        border-radius: 8px;
        padding: 15px;
        text-align: left;
        background-color: white;
    }
    .opciones-curso .nav-link.active {
        background-color: var(--cetis-rojo);
        color: white;
        border-color: var(--cetis-rojo);
    }

    /* TARJETAS DE PREGUNTAS EN EL FORO */
    .card-pregunta {
        border: none;
        border-radius: 12px;
        background-color: white;
    }

    /* AVATAR DE USUARIOS */
    .avatar-foro {
        width: 45px;
        height: 45px;
        background-color: #e9ecef;
        color: var(--cetis-rojo);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
        border: 2px solid #dee2e6;
    }

    /* BOTÓN INSTITUCIONAL */
    .btn-cetis {
        background-color: var(--cetis-rojo);
        color: white;
        font-weight: bold;
        border-radius: 8px;
        padding: 10px 20px;
    }
    .btn-cetis:hover {
        background-color: #6d0014;
        color: white;
    }
</style>

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
                <span class="badge {{ $curso->estado == 'ACTIVO' ? 'bg-success' : 'bg-secondary' }} mb-2 shadow-sm">
                    ESTADO: {{ $curso->estado }}
                </span>
                <br>

                @if(Auth::user()->rol === 'Estudiante')
                    @if($yaInscrito)
                        {{-- ESTADO: YA UNIDO --}}
                        <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded border mt-2">
                            <form id="form-salir-curso" action="{{ route('cursos.salir', $curso->id_curso) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmarSalida()" class="btn btn-link text-danger fw-bold p-0 text-decoration-none small">
                                    <i class="bi bi-box-arrow-left"></i> SALIR DEL CURSO
                                </button>
                            </form>

                            <script>
                            function confirmarSalida() {
                                Swal.fire({
                                    title: '¿Estás seguro?',
                                    text: "Ya no estarás inscrito en este curso y podrías perder tu lugar.",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#8C001A', 
                                    cancelButtonColor: '#6c757d', 
                                    confirmButtonText: 'Sí, salir del curso',
                                    cancelButtonText: 'Cancelar',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.getElementById('form-salir-curso').submit();
                                    }
                                })
                            }
                            </script>

                            <span class="text-success fw-bold small">
                                <i class="bi bi-check-circle-fill"></i> YA TE UNISTE
                            </span>
                        </div>
                    @else
                        {{-- ACCIÓN: UNIRSE (Si no está inscrito) --}}
                        <form action="{{ route('cursos.inscribir', $curso->id_curso) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-dark fw-bold px-4" {{ $curso->estado != 'ACTIVO' ? 'disabled' : '' }}>
                                <i class="bi bi-person-plus-fill me-2"></i> UNIRSE AL CURSO
                            </button>
                        </form>
                    @endif
                @else
                    {{-- VISTA PARA ASESOR/ADMIN --}}
                    <button class="btn btn-dark fw-bold px-4" {{ $curso->estado != 'ACTIVO' ? 'disabled' : '' }}>
                        <i class="bi bi-door-open-fill me-2"></i> INGRESAR AL CURSO
                    </button>
                @endif
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
                <a href="{{ route('curso.eventos', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-list-task me-2"></i> ACTIVIDADES
                </a>
                <a href="{{ route('foro.index', $curso->id_curso) }}" class="nav-link active shadow-sm">
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

            {{-- Banner Interno del Foro --}}
            <div class="tarjeta-curso-interna shadow-sm bg-white mb-4">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">{{ $foro->nombre_foro }}</h4>
                        <p class="text-muted mb-0 small">
                            <i class="bi bi-chat-square-text me-1"></i> Foro de Consultas y Dudas
                        </p>
                    </div>
                    <button class="btn btn-cetis shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalPregunta">
                        <i class="bi bi-plus-circle"></i> HACER UNA PREGUNTA
                    </button>
                </div>
            </div>

            {{-- Listado de Preguntas --}}
            <div class="row">
                @forelse($preguntas as $pregunta)
                    <div class="col-12 mb-3">
                        <div class="card card-pregunta shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-foro">
                                            {{ strtoupper(substr($pregunta->autor->nombre ?? $pregunta->correo_persona, 0, 1)) }}
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 fw-bold">
                                                {{ $pregunta->autor->nombre ?? $pregunta->correo_persona }}
                                            </h6>
                                            <small class="text-muted">
                                                Publicado el {{ \Carbon\Carbon::parse($pregunta->fecha_pregunta)->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                                        <i class="bi bi-chat-right-text me-1"></i>
                                        {{ $pregunta->respuestas_count }} respuestas
                                    </span>
                                </div>

                                <h5 class="card-title fw-bold text-dark mb-3">
                                    {{ $pregunta->texto_pregunta }}
                                </h5>

                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('foro.show', [$curso->id_curso, $pregunta->id_pregunta_foro]) }}"
                                       class="btn btn-outline-danger btn-sm fw-bold px-4 rounded-pill">
                                        Ver conversación <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5 bg-white rounded shadow-sm border">
                            <i class="bi bi-chat-square-dots text-muted display-2 d-block mb-3"></i>
                            <h4 class="mt-3 text-muted fw-bold">Aún no hay preguntas en este foro</h4>
                            <p class="text-muted">¡Sé el primero en plantear una duda!</p>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>

{{-- MODAL NUEVA PREGUNTA --}}
<div class="modal fade" id="modalPregunta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('pregunta.store', $curso->id_curso) }}" method="POST" class="modal-content border-0">
            @csrf
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2"></i> Nueva Consulta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">¿Cuál es tu duda?</label>
                    <textarea name="texto_pregunta"
                        class="form-control border-0 bg-light"
                        rows="4"
                        placeholder="Escribe aquí tu pregunta detalladamente..."
                        required
                        style="resize: none;"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">
                    CANCELAR
                </button>
                <button type="submit" class="btn btn-cetis fw-bold px-4">
                    PUBLICAR PREGUNTA
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Alertas del Servidor mapeadas con SweetAlert2 --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Logrado!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ session('error') }}",
    });
</script>
@endif

@endsection