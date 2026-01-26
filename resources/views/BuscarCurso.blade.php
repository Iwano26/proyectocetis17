<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos | CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root { 
            --cetis-rojo: #8C001A; 
            --cetis-primary: #8C001A;
        }
        body { background-color: #f8f9fa; }

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

        .seccion-filtros { background-color: #eee; padding: 20px; border-radius: 8px; margin-bottom: 25px; }
        .tarjeta-curso {
            background-color: white; border: 2px solid var(--cetis-rojo);
            border-radius: 12px; padding: 20px; margin-bottom: 15px;
        }
        .circulo-rojo {
            width: 55px; height: 55px; background-color: var(--cetis-rojo);
            border-radius: 50%; margin-right: 20px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; color: white;
        }
    </style>
</head>
<body>

    <button id="menu-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
        <i class="bi bi-list"></i>
    </button>
    
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistema de Asesorías</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><span class="nav-link fw-bold text-danger">ADMINISTRACIÓN:</span></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('cursos.index') }}">Cursos</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="/biblioteca">Biblioteca</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="/gestionusuario">Usuarios</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="sidebarMenuLabel">
                <i class="bi bi-person-fill me-2"></i> Menú de Usuario
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <p class="text-muted small mb-4">Módulos</p>
            <div class="nav flex-column nav-pills">
                <a class="nav-link" href="/principal"><i class="bi bi-calendar-event me-3"></i> Principal</a>
                <a class="nav-link" href="/agenda"><i class="bi bi-calendar-event me-3"></i> Agenda</a>
                <a class="nav-link active" href="{{ route('cursos.index') }}"><i class="bi bi-journal-bookmark me-3"></i> Cursos</a>
                <a class="nav-link" href="/biblioteca"><i class="bi bi-archive me-3"></i> Biblioteca</a>
            </div>
            <hr class="my-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link btn btn-outline-secondary mt-3 text-start w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    <div class="container-fluid mt-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0">Búsqueda de Cursos</h2>
            <a href="{{ route('cursos.create') }}" class="btn btn-danger fw-bold shadow-sm">
                <i class="bi bi-plus-circle me-2"></i>CREAR CURSO
            </a>
        </div>
        
        <form action="{{ route('cursos.index') }}" method="GET">
            <div class="mb-3">
                <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control form-control-lg shadow-sm" placeholder="Buscar por nombre o materia...">
            </div>

            <div class="seccion-filtros shadow-sm">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label class="form-label small fw-bold">Día de la semana:</label>
                        <select name="dia" class="form-select">
                            <option value="">-- Seleccionar Día --</option>
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miercoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label small fw-bold">Materia:</label>
                        <select name="materia" class="form-select">
                            <option value="">-- Seleccionar Materia --</option>
                            </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label small fw-bold">Estado:</label>
                        <select name="estado" class="form-select">
                            <option value="">-- Todos --</option>
                            <option value="ACTIVO">Activo</option>
                            <option value="INACTIVO">Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <button type="submit" class="btn btn-dark w-100 fw-bold">APLICAR FILTROS</button>
                    </div>
                </div>
            </div>
        </form>

        <div id="contenedor-cursos">
            @forelse($cursos as $curso)
                <div class="tarjeta-curso shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="circulo-rojo">
                                <i class="bi bi-book fs-4"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold">{{ $curso->nombre_curso }}</h4>
                                <p class="mb-1 text-muted">{{ $curso->materia }}</p>
                                
                                @php
                                    $claseEstado = ($curso->estado == 'ACTIVO') ? 'bg-success' : 'bg-secondary';
                                @endphp
                                <span class="badge {{ $claseEstado }} rounded-pill">
                                    ESTADO: {{ $curso->estado }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="col-md-2 text-center">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Horas Disp.</small>
                                <span class="fs-4 fw-bold text-dark">{{ $curso->horas_disponibles }}</span>
                            </div>
                        </div>

                  <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="/vercurso" class="btn btn-primary btn-sm mx-1">
                            VER CURSO
                            </a>


                            <a href="{{ route('cursos.edit', $curso->id_curso) }}" class="btn btn-warning btn-sm mx-1 text-white">
                                EDITAR
                            </a>

                            <a href="#" class="btn btn-success btn-sm mx-1 {{ ($curso->estado != 'ACTIVO') ? 'disabled' : '' }}">
                                UNIRSE
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-warning text-center">
                    No se encontraron cursos que coincidan con la búsqueda.
                </div>
            @endforelse
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>