<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discusión - {{ $curso->nombre_curso }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --cetis-rojo: #8C001A; }
        body { background-color: #f4f4f4; font-family: 'Segoe UI', sans-serif; }
        .navbar-cetis { background-color: var(--cetis-rojo); color: white; }
        .nav-link { color: #555; font-weight: 600; margin-bottom: 10px; border-radius: 8px; padding: 12px 20px; background: white; text-decoration: none; display: flex; align-items: center; }
        .nav-link.active { background-color: var(--cetis-rojo) !important; color: white !important; }
        .card-principal { border: none; border-radius: 15px; border-left: 8px solid var(--cetis-rojo) !important; }
        .avatar-foro { width: 45px; height: 45px; background-color: #e9ecef; color: var(--cetis-rojo); display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; }
        .respuesta-card { border: none; border-radius: 12px; margin-left: 2rem; border-left: 4px solid #dee2e6; }
    </style>
</head>
<body>

<nav class="navbar navbar-cetis mb-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 text-white">PLATAFORMA ACADÉMICA CETIS 17</span>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row">
        <!-- BARRA LATERAL -->
        <div class="col-md-3">
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
                        <a href="reportes.index" class="nav-link shadow-sm border-danger">
                            <i class="bi bi-file-earmark-text me-2 text-danger"></i> REPORTES
                        </a>
                    @endif
        </div>

        <!-- CONTENIDO -->
        <div class="col-md-9">
            <!-- LA PREGUNTA ORIGINAL -->
            <div class="card card-principal shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-foro">
                            {{ strtoupper(substr($pregunta->autor->nombre ?? $pregunta->correo_persona, 0, 1)) }}
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold">{{ $pregunta->autor->nombre ?? $pregunta->correo_persona }}</h6>
                            <small class="text-muted">Preguntó el {{ \Carbon\Carbon::parse($pregunta->fecha_pregunta)->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark">{{ $pregunta->texto_pregunta }}</h3>
                </div>
            </div>

            <h5 class="fw-bold mb-3"><i class="bi bi-chat-left-text me-2"></i>Respuestas</h5>

            <!-- LISTA DE RESPUESTAS -->
            @forelse($pregunta->respuestas as $respuesta)
                <div class="card respuesta-card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-foro" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                {{ strtoupper(substr($respuesta->autor->nombre ?? $respuesta->correo_persona, 0, 1)) }}
                            </div>
                            <div class="ms-2">
                                <span class="fw-bold small">{{ $respuesta->autor->nombre ?? $respuesta->correo_persona }}</span>
                                <small class="text-muted ms-2" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($respuesta->fecha_respuesta)->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        <p class="mb-0 text-dark">{{ $respuesta->texto_respuesta }}</p>
                    </div>
                </div>
            @empty
                <div class="alert alert-light border shadow-sm text-center py-4 respuesta-card">
                    <p class="mb-0 text-muted">Aún no hay respuestas. ¡Sé el primero en ayudar!</p>
                </div>
            @endforelse

            <!-- FORMULARIO PARA RESPONDER -->
            <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <form action="{{ route('respuesta.store', [$curso->id_curso, $pregunta->id_pregunta_foro]) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tu Respuesta</label>
                            <textarea name="texto_respuesta" class="form-control bg-light border-0" rows="3" placeholder="Escribe tu comentario aquí..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-dark fw-bold px-4">ENVIAR RESPUESTA</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>