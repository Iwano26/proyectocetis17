<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca | CETIS 17</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/biblio.css') }}">
</head>
<body>

    <button id="menu-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
        <i class="bi bi-list"></i>
    </button>
    
    <x-sidebar />

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

    <div class="container-fluid mt-4 px-4">
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

        <form action="{{ route('biblioteca.index') }}" method="GET">
            <div class="mb-3">
                <div class="input-group shadow-sm">
                    <input type="text" name="buscar" class="form-control form-control-lg" 
                           placeholder="Buscar documentos..." 
                           value="{{ request('buscar') }}">
                    <button class="btn btn-danger px-4" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <div class="seccion-filtros shadow-sm">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label class="form-label small fw-bold">Filtrar por Fecha:</label>
                        <select name="orden" class="form-select">
                            <option value="reciente" {{ request('orden') == 'reciente' ? 'selected' : '' }}>Más recientes</option>
                            <option value="antiguo" {{ request('orden') == 'antiguo' ? 'selected' : '' }}>Más antiguos</option>
                        </select>
                    </div>

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

                    <div class="col-md-4 mb-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark w-100 fw-bold">APLICAR FILTROS</button>
                            <a href="{{ route('biblioteca.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

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
                            {{-- Se eliminó la línea de autor para evitar errores SQL --}}
                        </div>
                    </div>
                    <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
                        <a href="{{ asset('documentos/' . $archivo->ruta_archivo) }}" target="_blank" class="btn btn-info btn-sm text-white mx-1">
                            <i class="bi bi-eye"></i> VER
                        </a>
                        <a href="{{ asset('documentos/' . $archivo->ruta_archivo) }}" download="{{ $archivo->nombre_doc }}" class="btn btn-primary btn-sm mx-1">
                            <i class="bi bi-download"></i> DESCARGAR
                        </a>
                        <a href="{{ route('biblioteca.edit', $archivo->id_biblioteca) }}" class="btn btn-warning btn-sm text-white mx-1">
                            <i class="bi bi-pencil"></i> MODIFICAR
                        </a>
                        <form action="{{ route('biblioteca.eliminar', $archivo->id_biblioteca) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mx-1" onclick="return confirm('¿Estás seguro?')">
                                <i class="bi bi-trash"></i> ELIMINAR
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-archive text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">No hay archivos registrados.</p>
            </div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>