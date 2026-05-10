<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro - {{ $curso->nombre_curso }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { 
            --cetis-rojo: #8C001A; 
            --cetis-gris: #f8f9fa;
        }
        body { 
            background-color: #f4f4f4; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-cetis {
            background-color: var(--cetis-rojo);
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        /* Estilo de la navegación lateral */
        .nav-link {
            color: #555;
            font-weight: 600;
            margin-bottom: 10px;
            border-radius: 8px;
            padding: 12px 20px;
            background: white;
            transition: all 0.3s;
            border: 1px solid transparent;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .nav-link:hover {
            background-color: #eee;
            color: var(--cetis-rojo);
        }
        .nav-link.active {
            background-color: var(--cetis-rojo) !important;
            color: white !important;
        }
        /* Tarjetas de preguntas */
        .card-pregunta {
            border: none;
            border-radius: 15px;
            transition: transform 0.2s;
        }
        .card-pregunta:hover {
            transform: scale(1.01);
        }
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
    </style>
</head>
<body>

<nav class="navbar navbar-cetis mb-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 text-white text-uppercase">Plataforma Académica CETIS 17</span>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row">
        <!-- BARRA LATERAL DE NAVEGACIÓN -->
        <div class="col-md-3">
            <div class="sticky-top" style="top: 20px;">
                <a href="{{ route('cursos.show', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-info-circle me-2"></i> INFORMACIÓN
                </a>
                
                <a href="{{ route('curso.eventos', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-list-task me-2"></i> ACTIVIDADES
                </a>

                {{-- Enlace del Foro con clase Active --}}
                <a href="{{ route('foro.index', $curso->id_curso) }}" class="nav-link shadow-sm active">
                    <i class="bi bi-chat-dots me-2"></i> FORO
                </a>
                 @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Asesor')
                        <a href="#" class="nav-link shadow-sm border-danger">
                            <i class="bi bi-file-earmark-text me-2 text-danger"></i> REPORTES
                        </a>
                    @endif
                <hr>
                <a href="{{ route('principal') }}" class="btn btn-outline-secondary w-100 fw-bold">
                    <i class="bi bi-arrow-left"></i> VOLVER AL MENÚ
                </a>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="col-md-9">
            <!-- Banner del Curso -->
            <div class="card mb-4 shadow-sm border-0" style="border-left: 8px solid var(--cetis-rojo) !important; border-radius: 12px;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="fw-bold text-dark mb-1">{{ $foro->nombre_foro }}</h1>
                        <p class="text-muted mb-0"><i class="bi bi-book me-1"></i> Curso: {{ $curso->nombre_curso }}</p>
                    </div>
                    <button class="btn btn-cetis shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPregunta">
                        <i class="bi bi-plus-circle me-2"></i> HACER UNA PREGUNTA
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Listado de Preguntas -->
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
                                            <h6 class="mb-0 fw-bold">{{ $pregunta->autor->nombre ?? $pregunta->correo_persona }}</h6>
                                            <small class="text-muted">Publicado el {{ \Carbon\Carbon::parse($pregunta->fecha_pregunta)->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                                        <i class="bi bi-chat-right-text me-1"></i> {{ $pregunta->respuestas_count }} respuestas
                                    </span>
                                </div>
                                
                                <h5 class="card-title fw-bold text-dark mb-3">{{ $pregunta->texto_pregunta }}</h5>
                                
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('foro.show', [$curso->id_curso, $pregunta->id_pregunta_foro]) }}" class="btn btn-outline-danger btn-sm fw-bold px-4 rounded-pill">
                                        Ver conversación <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5 bg-white rounded shadow-sm">
                            <i class="bi bi-chat-square-dots text-light display-1"></i>
                            <h4 class="mt-3 text-muted">Aún no hay preguntas en este foro</h4>
                            <p class="text-muted">¡Sé el primero en plantear una duda!</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA NUEVA PREGUNTA -->
<div class="modal fade" id="modalPregunta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('pregunta.store', $curso->id_curso) }}" method="POST" class="modal-content border-0">
            @csrf
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Nueva Consulta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">¿Cuál es tu duda?</label>
                    <textarea name="texto_pregunta" class="form-control border-0 bg-light" rows="4" placeholder="Escribe aquí tu pregunta detalladamente..." required style="resize: none;"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                <button type="submit" class="btn btn-cetis fw-bold px-4">PUBLICAR PREGUNTA</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>