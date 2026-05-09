<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CETIS 17 | Curso Base de Datos</title>
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

        #menu-toggle-btn {
            position: fixed; top: 15px; left: 20px; z-index: 1040; 
            background-color: white; color: var(--cetis-rojo);
            border: 2px solid var(--cetis-rojo); padding: 5px 12px;
            border-radius: 8px; font-size: 1.5rem; cursor: pointer;
            transition: all 0.3s;
        }
        #menu-toggle-btn:hover { background-color: var(--cetis-primary); color: white; }

        .navbar {
            background-color: white !important; border-bottom: 1px solid #dee2e6;
            min-height: 70px; padding-left: 80px; 
        }
        .navbar-brand { color: var(--cetis-rojo) !important; font-weight: bold; }

        .offcanvas-header { background-color: var(--cetis-primary); color: white; }
        .offcanvas-header .btn-close { filter: invert(1); }
        .offcanvas-body .nav-link { color: #333; font-weight: 500; padding: 12px 15px; border-radius: 8px; transition: all 0.2s; }
        .offcanvas-body .nav-link:hover, .offcanvas-body .nav-link.active { background-color: var(--cetis-primary); color: white !important; }

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
        }
        .opciones-curso .nav-link.active {
            background-color: var(--cetis-rojo);
            color: white;
            border-color: var(--cetis-rojo);
        }

        .circulo-rojo-mini {
            width: 45px; height: 45px; background-color: var(--cetis-rojo);
            border-radius: 50%; display: flex; align-items: center; 
            justify-content: center; color: white; margin-bottom: 15px;
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

        @extends('layouts.app') {{-- Esto conecta con el esqueleto que hicimos arriba --}}

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
                    <span class="badge {{ $curso->estado == 'ACTIVO' ? 'bg-success' : 'bg-secondary' }} mb-2 shadow-sm">
                        ESTADO: {{ $curso->estado }}
                    </span>
                    <br>
                    {{-- Botón único de acción --}}
                    <button class="btn btn-dark fw-bold px-4" {{ $curso->estado != 'ACTIVO' ? 'disabled' : '' }}>
                        <i class="bi bi-door-open-fill me-2"></i> INGRESAR AL CURSO
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Menú Lateral --}}
            <div class="col-md-3">
                <div class="nav flex-column opciones-curso">
                    {{-- Estas opciones las ven todos (Alumno, Asesor, Admin) --}}
                    <a href="#" class="nav-link shadow-sm">
                        <i class="bi bi-info-circle me-2"></i> INFORMACIÓN
                    </a>
                    <a href="#" class="nav-link active shadow-sm">
                        <i class="bi bi-list-task me-2"></i> ACTIVIDADES
                    </a>
                    <a href="#" class="nav-link shadow-sm">
                        <i class="bi bi-chat-dots me-2"></i> FORO
                    </a>

                    {{-- Solo visible para Administrador O Asesor --}}
                    @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Asesor')
                        <a href="#" class="nav-link shadow-sm border-danger">
                            <i class="bi bi-file-earmark-text me-2 text-danger"></i> REPORTES
                        </a>
                    @endif
                </div>
            </div>

            {{-- Contenido Principal --}}
            <div class="col-md-9">
                <h4 class="fw-bold mb-3">Actividades del Curso</h4>
                
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

            </div>
        </div>
    </div>
    @endsection

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>