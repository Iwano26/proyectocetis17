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

                    @if(Auth::user()->rol === 'Estudiante')
                        @if($yaInscrito)
                            {{-- ESTADO: YA UNIDO --}}
                            <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded border mt-2">
                                {{-- Botón Salir (Izquierda) --}}
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
                                        confirmButtonColor: '#d33', // Rojo para confirmar
                                        cancelButtonColor: '#3085d6', // Azul para cancelar
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

                                {{-- Texto de Confirmación (Derecha) --}}
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
                    {{-- Estas opciones las ven todos (Alumno, Asesor, Admin) --}}
                 <a href="#" class="nav-link active shadow-sm">
            <i class="bi bi-info-circle me-2"></i> INFORMACIÓN
        </a>
        <a href="{{ route('curso.eventos', $curso->id_curso) }}" class="nav-link shadow-sm">
            <i class="bi bi-list-task me-2"></i> ACTIVIDADES
        </a>
        <a href="{{ route('foro.index', $curso->id_curso) }}" class="nav-link shadow-sm">
            <i class="bi bi-chat-dots me-2"></i> FORO
        </a>

        {{-- Solo visible para Administrador O Asesor --}}
        @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Asesor')
            <a href="{{ route('reportes.index', $curso->id_curso) }}" class="nav-link shadow-sm border-danger">
                <i class="bi bi-file-earmark-text me-2 text-danger"></i> REPORTES
            </a>
        @endif
                </div>
            </div>

            {{-- Contenido Principal --}}
            <div class="col-md-9">
                <h4 class="fw-bold mb-3">Detalles del Curso</h4>
                
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

                <h4 class="fw-bold mb-3">Horarios de Atención</h4>
                <div class="row">
                    @forelse($curso->horarios as $horario)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 bg-white border rounded-3 shadow-sm">
                                <div class="circulo-rojo-mini mb-0 me-3">
                                    <i class="bi bi-clock-history fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-uppercase">{{ $horario->dia_semana }}</h6>
                                    <span class="text-muted small">
                                        {{ date('g:i A', strtotime($horario->hora_inicio)) }} - {{ date('g:i A', strtotime($horario->hora_fin)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-light border">No hay sesiones programadas por el momento.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endsection

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Acceso Denegado',
                text: "{{ session('error') }}",
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    
</body>
</html>