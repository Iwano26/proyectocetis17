@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

<style>
    :root { 
        --cetis-rojo: #8C001A !important; 
        --cetis-primary: #8C001A !important;
    }
    
    /* APLICAR LA FUENTE INTER A TODO EL CONTENIDO */
    *, body, h2, h3, h5, h6, p, span, small, a, button, textarea {
        font-family: 'Inter', sans-serif !important;
    }

    body { 
        background-color: #f8f9fa !important; 
    }

    /* CABECERA DEL CURSO */
    .tarjeta-curso-interna {
        background-color: white !important; 
        border: 2px solid #8C001A !important;
        border-radius: 12px !important; 
        padding: 20px !important; 
        margin-bottom: 15px !important;
    }

    /* MENÚ LATERAL INSTITUCIONAL COPIADO EXACTO */
    .opciones-curso .nav-link {
        color: #333 !important;
        font-weight: 700 !important;
        border: 1px solid #dee2e6 !important;
        margin-bottom: 8px !important;
        border-radius: 8px !important;
        padding: 15px !important;
        text-align: left !important;
        background-color: white !important;
        display: block !important;
        text-decoration: none !important;
        transition: all 0.2s !important;
    }
    .opciones-curso .nav-link.active {
        background-color: #8C001A !important;
        color: white !important;
        border-color: #8C001A !important;
    }

    /* AVATAR DE USUARIOS */
    .avatar-foro {
        background-color: #e9ecef !important;
        color: #8C001A !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important;
        font-weight: bold !important;
        border: 2px solid #dee2e6 !important;
        width: 45px !important;
        height: 45px !important;
    }

    /* TARJETA PRINCIPAL DEL FORO (Pregunta original con borde lateral rojo) */
    .card-principal-foro {
        background-color: white !important;
        border: 1px solid #dee2e6 !important;
        border-left: 8px solid #8C001A !important;
        border-radius: 12px !important;
    }

    /* RESPUESTAS */
    .respuesta-card {
        border: 1px solid #dee2e6 !important;
        border-radius: 12px !important;
        background-color: white !important;
    }

    /* BOTÓN CETIS */
    .btn-cetis {
        background-color: #8C001A !important;
        color: white !important;
        font-weight: bold !important;
        border-radius: 8px !important;
        padding: 10px 20px !important;
        border: none !important;
    }
    .btn-cetis:hover {
        background-color: #6d0014 !important;
    }
</style>

{{-- Se cambió mt-0 por mt-4 para corregir la separación superior --}}
<div class="container-fluid mt-4 px-4">
        
    {{-- CABECERA DEL CURSO ESTILIZADA --}}
    <div class="tarjeta-curso-interna shadow-sm bg-white mb-4">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h2 class="fw-bold m-0 text-uppercase text-dark" style="letter-spacing: 0.5px; font-family: 'Inter', sans-serif;">{{ $curso->nombre_curso }}</h2>
                <p class="text-muted mb-1 mt-1">Impartido por: <strong>{{ $curso->nombre_asesor }}</strong></p>
                <small class="text-secondary"><i class="bi bi-book me-1"></i> Materia: {{ $curso->materia }}</small>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <span class="badge {{ $curso->estado == 'ACTIVO' ? 'bg-success' : 'bg-secondary' }} mb-2 shadow-sm px-3 py-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    ESTADO: {{ $curso->estado }}
                </span>
                <br>

                @if(Auth::user()->rol === 'Estudiante')
                    @if($yaInscrito)
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
                        <form action="{{ route('cursos.inscribir', $curso->id_curso) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-dark fw-bold px-4 rounded-3 shadow-sm" {{ $curso->estado != 'ACTIVO' ? 'disabled' : '' }}>
                                <i class="bi bi-person-plus-fill me-2"></i> UNIRSE AL CURSO
                            </button>
                        </form>
                    @endif
                @else
                    <button class="btn btn-dark fw-bold px-4 rounded-3 shadow-sm" {{ $curso->estado != 'ACTIVO' ? 'disabled' : '' }}>
                        <i class="bi bi-door-open-fill me-2"></i> INGRESAR AL CURSO
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="row">

        {{-- MENÚ LATERAL CORREGIDO CON FONDOS BLANCOS Y BORDES --}}
        <div class="col-md-3">
            <div class="nav flex-column opciones-curso">
                <a href="{{ route('cursos.show', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-info-circle me-2"></i> INFORMACIÓN
                </a>
                <a href="{{ route('curso.eventos', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-list-task me-2"></i> ACTIVIDADES
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

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="col-md-9">

            {{-- LA PREGUNTA ORIGINAL CORREGIDA --}}
            <div class="card card-principal-foro mb-4 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <div class="avatar-foro">
                                {{ strtoupper(substr($pregunta->autor->nombre ?? $pregunta->correo_persona, 0, 1)) }}
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0 fw-bold text-dark" style="font-family: 'Inter', sans-serif;">
                                    {{ $pregunta->autor->nombre ?? $pregunta->correo_persona }}
                                </h6>
                                <small class="text-muted">
                                    Preguntó el {{ \Carbon\Carbon::parse($pregunta->fecha_pregunta)->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                        <a href="{{ route('foro.index', $curso->id_curso) }}" class="btn btn-outline-danger btn-sm fw-bold px-3 rounded-pill">
                            <i class="bi bi-arrow-left me-1"></i> Volver al foro
                        </a>
                    </div>
                    
                    <h3 class="fw-bold text-dark mt-2 mb-0" style="font-family: 'Inter', sans-serif;">{{ $pregunta->texto_pregunta }}</h3>
                </div>
            </div>

            {{-- SECCIÓN DE RESPUESTAS --}}
            <h5 class="fw-bold text-dark mb-3" style="font-family: 'Inter', sans-serif;">
                <i class="bi bi-chat-left-text me-2 text-secondary"></i>Respuestas
            </h5>

            <div class="row">
                @forelse($pregunta->respuestas as $respuesta)
                    <div class="col-12 mb-3">
                        <div class="card respuesta-card shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar-foro" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($respuesta->autor->nombre ?? $respuesta->correo_persona, 0, 1)) }}
                                    </div>
                                    <div class="ms-2">
                                        <span class="fw-bold text-dark small" style="font-family: 'Inter', sans-serif;">
                                            {{ $respuesta->autor->nombre ?? $respuesta->correo_persona }}
                                        </span>
                                        <br>
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            {{ \Carbon\Carbon::parse($respuesta->fecha_respuesta)->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                                <p class="mb-0 text-dark ps-1" style="font-size: 0.95rem; font-family: 'Inter', sans-serif;">
                                    {{ $respuesta->texto_respuesta }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5 bg-white rounded shadow-sm respuesta-card border">
                            <i class="bi bi-chat-square-dots text-muted display-2 d-block mb-3"></i>
                            <h5 class="mt-3 text-muted fw-bold">Aún no hay respuestas en esta discusión</h5>
                            <p class="text-muted small">¡Sé el primero en aportar a la duda de tu compañero!</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- FORMULARIO PARA RESPONDER --}}
            <div class="card border-0 shadow-sm mt-4 respuesta-card">
                <div class="card-body p-4">
                    <form action="{{ route('respuesta.store', [$curso->id_curso, $pregunta->id_pregunta_foro]) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark" style="font-family: 'Inter', sans-serif;">
                                <i class="bi bi-pencil me-1"></i> Tu Respuesta
                            </label>
                            <textarea name="texto_respuesta" 
                                      class="form-control bg-light border-0" 
                                      rows="3" 
                                      placeholder="Escribe tu comentario de manera clara y respetuosa..." 
                                      required 
                                      style="resize: none; border-radius: 8px; font-family: 'Inter', sans-serif;"></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-cetis shadow-sm px-4">
                                ENVIAR RESPUESTA <i class="bi bi-send ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: "{{ session('success') }}",
        timer: 3000,
        confirmButtonColor: '#8C001A',
        showConfirmButton: false
    });
</script>
@endif
@endsection