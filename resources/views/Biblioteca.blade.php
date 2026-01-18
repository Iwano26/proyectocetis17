<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca | CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root { 
            --cetis-rojo: #8C001A; 
            --cetis-primary: #8C001A;
        }
        body { background-color: #f8f9fa; }

        /* Botón Menú Flotante */
        #menu-toggle-btn {
            position: fixed; top: 15px; left: 20px; z-index: 1040; 
            background-color: white; color: var(--cetis-rojo);
            border: 2px solid var(--cetis-rojo); padding: 5px 12px;
            border-radius: 8px; font-size: 1.5rem; cursor: pointer;
            transition: all 0.3s;
        }
        #menu-toggle-btn:hover { background-color: var(--cetis-primary); color: white; }

        /* Barra Superior */
        .navbar {
            background-color: white !important; border-bottom: 1px solid #dee2e6;
            min-height: 70px; padding-left: 80px; 
        }
        .navbar-brand { color: var(--cetis-rojo) !important; font-weight: bold; }

        /* Estilos Offcanvas Principal */
        .offcanvas-header { background-color: var(--cetis-primary); color: white; }
        .offcanvas-header .btn-close { filter: invert(1); }
        .offcanvas-body .nav-link { color: #333; font-weight: 500; padding: 12px 15px; border-radius: 8px; transition: all 0.2s; }
        .offcanvas-body .nav-link:hover, .offcanvas-body .nav-link.active { background-color: var(--cetis-primary); color: white !important; }

        /* Estilo de Tarjeta de Archivo */
        .tarjeta-archivo {
            background-color: white; border: 2px solid var(--cetis-rojo);
            border-radius: 12px; padding: 20px; margin-bottom: 15px;
        }
        
        .icono-archivo {
            width: 55px; height: 55px; background-color: #eee;
            border-radius: 8px; margin-right: 20px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: #8C001A;
        }

        .seccion-filtros { background-color: #eee; padding: 20px; border-radius: 8px; margin-bottom: 25px; }
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
                    <li class="nav-item"><a class="nav-link text-dark" href="/gestioncurso">Cursos</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="#">Biblioteca</a></li>
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
                <a class="nav-link" href="/buscarcurso"><i class="bi bi-journal-bookmark me-3"></i> Cursos</a>
                <a class="nav-link active" href="/biblioteca"><i class="bi bi-archive me-3"></i> Biblioteca</a>
            </div>
            <hr class="my-4">
            <a class="nav-link btn btn-outline-secondary mt-3 text-start" href="/login">
                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
            </a>
        </div>
    </div>

    <div class="container-fluid mt-4 px-4">
        {{-- Mensaje de Éxito --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold m-0">Biblioteca Virtual</h2>
            <div>
                <button class="btn btn-outline-danger fw-bold me-2">MIS ARCHIVOS</button>
                <a href="{{ route('biblioteca.create') }}" class="btn btn-danger fw-bold">
                    <i class="bi bi-cloud-upload me-1"></i> SUBIR ARCHIVO
                </a>
            </div>
        </div>
        

        {{-- FORMULARIO DE BÚSQUEDA Y FILTROS --}}
        <form action="{{ route('biblioteca.index') }}" method="GET">
            
            {{-- Buscador Principal --}}
            <div class="mb-3">
                <div class="input-group shadow-sm">
                    <input type="text" name="buscar" class="form-control form-control-lg" 
                        placeholder="Buscar documentos por nombre o autor..." 
                        value="{{ request('buscar') }}">
                    <button class="btn btn-danger px-4" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            {{-- Cuadro de Filtros --}}
            <div class="seccion-filtros shadow-sm">
                <div class="row align-items-end">
                    {{-- Filtro por Fecha --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label small fw-bold">Filtrar por Fecha:</label>
                        <select name="orden" class="form-select">
                            <option value="reciente" {{ request('orden') == 'reciente' ? 'selected' : '' }}>Más recientes</option>
                            <option value="antiguo" {{ request('orden') == 'antiguo' ? 'selected' : '' }}>Más antiguos</option>
                        </select>
                    </div>

                    {{-- Filtro por Materia --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label small fw-bold">Materia Relacionada:</label>
                        <select name="materia" class="form-select">
                            <option value="">-- Todas las Materias --</option>
                            <option value="Programación" {{ request('materia') == 'Programación' ? 'selected' : '' }}>Programación</option>
                            <option value="Soporte Técnico" {{ request('materia') == 'Soporte Técnico' ? 'selected' : '' }}>Soporte Técnico</option>
                            <option value="Ofimática" {{ request('materia') == 'Ofimática' ? 'selected' : '' }}>Ofimática</option>
                            <option value="Base de Datos" {{ request('materia') == 'Base de Datos' ? 'selected' : '' }}>Base de Datos</option>
                        </select>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="col-md-4 mb-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark w-100 fw-bold">APLICAR FILTROS</button>
                            {{-- Botón para limpiar filtros --}}
                            <a href="{{ route('biblioteca.index') }}" class="btn btn-outline-secondary" title="Limpiar filtros">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>



        {{-- INICIO DEL BUCLE DE ARCHIVOS REALES --}}
        @forelse($archivos as $archivo)
            <div class="tarjeta-archivo shadow-sm">
                <div class="row align-items-center">
                    <div class="col-lg-6 d-flex align-items-center">
                        <div class="icono-archivo">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">{{ $archivo->nombre_doc }}</h4>
                            <p class="mb-0 text-muted small">Subido el: <b>{{ $archivo->created_at->format('d/m/Y') }}</b></p>
                            <p class="mb-0 text-muted small">Materia: <b>{{ $archivo->materia }}</b></p>
                            <p class="mb-0 text-muted small">Por: <b>{{ $archivo->autor }}</b></p>
                        </div>
                    </div>
                    <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
                        {{-- Abrir PDF en pestaña nueva --}}
                        <a href="{{ asset('documentos/' . $archivo->ruta_archivo) }}" target="_blank" class="btn btn-info btn-sm text-white mx-1">
                            <i class="bi bi-eye"></i> VER
                        </a>
                        
                        {{-- Descargar PDF --}}
                        <a href="{{ asset('documentos/' . $archivo->ruta_archivo) }}" download="{{ $archivo->nombre_doc }}" class="btn btn-primary btn-sm mx-1">
                            <i class="bi bi-download"></i> DESCARGAR
                        </a>
                        
                        {{-- MODIFICAR: Enlace a la ruta de edición --}}
                        <a href="{{ route('biblioteca.edit', $archivo->id) }}" class="btn btn-warning btn-sm text-white mx-1">
                            <i class="bi bi-pencil"></i> MODIFICAR
                        </a>

                        {{-- ELIMINAR: Formulario para enviar petición DELETE --}}
                        <form action="{{ route('biblioteca.eliminar', $archivo->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mx-1" onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo?')">
                                <i class="bi bi-trash"></i> ELIMINAR
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-archive text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">No hay archivos registrados en la biblioteca todavía.</p>
            </div>
        @endforelse
        {{-- FIN DEL BUCLE --}}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>