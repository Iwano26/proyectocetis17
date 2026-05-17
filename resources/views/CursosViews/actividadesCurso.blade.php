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
                    <div class="card mb-3 shadow-sm border-0 border-start border-4 border-danger">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                {{-- Cambiamos el nombre de la columna a 'nombre_evento' según tu base de datos --}}
                                <h6 class="fw-bold mb-1">{{ $evento->nombre_evento }}</h6>
                                
                                {{-- Mostramos una pequeña etiqueta dependiendo del tipo de evento --}}
                                <span class="badge bg-secondary mb-2 text-uppercase" style="font-size: 0.75rem;">
                                    {{ $evento->tipo }}
                                </span>
                                
                                <span class="badge bg-light text-dark border ms-2">
                                    <i class="bi bi-calendar-event me-1"></i> Publicado: {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}
                                </span>
                            </div>
                            {{-- Botón para ir a ver el detalle de la asesoría o contestar el examen --}}
                            <a href="/evento/{{ $evento->id_evento }}" class="btn btn-light btn-sm rounded-circle shadow-sm">
                                <i class="bi bi-chevron-right text-danger fw-bold"></i>
                            </a>
                        </div>
                    </div>
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