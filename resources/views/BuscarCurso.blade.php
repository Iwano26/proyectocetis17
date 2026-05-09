<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos | CETIS 17</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/curso.css') }}">
</head>
<body>

    <x-sidebar />

    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistema de Asesorías</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    {{-- Validación de Rol para Administración --}}
                    @if(Auth::user()->rol === 'Administrador')
                        <li class="nav-item"><span class="nav-link fw-bold text-danger">ADMINISTRACIÓN:</span></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-bold" href="{{ route('cursos.index') }}">Cursos</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="/biblioteca">Biblioteca</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="/gestionusuario">Usuarios</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0">Búsqueda de Cursos</h2>
            
            {{-- Solo Admin/Asesor pueden crear cursos --}}
            @if(Auth::user()->rol !== 'Estudiante')
                <a href="{{ route('cursos.create') }}" class="btn btn-danger fw-bold shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i>CREAR CURSO
                </a>
            @endif
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
                            @foreach(['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'] as $dia)
                                <option value="{{ $dia }}" {{ request('dia') == $dia ? 'selected' : '' }}>{{ $dia }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label small fw-bold">Estado:</label>
                        <select name="estado" class="form-select">
                            <option value="">-- Todos --</option>
                            <option value="ACTIVO" {{ request('estado') == 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                            <option value="INACTIVO" {{ request('estado') == 'INACTIVO' ? 'selected' : '' }}>Inactivo</option>
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
                <div class="tarjeta-curso shadow-sm mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-5 d-flex">
                            <div class="circulo-rojo">
                                <i class="bi bi-book fs-4"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold text-uppercase">{{ $curso->nombre_curso }}</h4>
                                <p class="text-muted mb-2"><i class="bi bi-tags-fill me-1"></i>{{ $curso->materia }}</p>
                                <p class="small text-secondary mb-0">
                                    {{ Str::limit($curso->descripcion ?? 'Sin descripción disponible.', 100) }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4 border-start border-end">
                            <h6 class="fw-bold small text-muted text-uppercase mb-2">
                                <i class="bi bi-clock-history me-1"></i> Horarios:
                            </h6>
                            <div class="row g-2">
                                @forelse($curso->horarios as $horario)
                                    <div class="col-12">
                                        <div class="p-2 border rounded bg-light d-flex justify-content-between align-items-center">
                                            <span class="small fw-bold"><i class="bi bi-calendar-event text-danger me-1"></i> {{ $horario->dia_semana }}</span>
                                            <span class="badge bg-white text-dark border fw-normal">
                                                {{ date('g:i A', strtotime($horario->hora_inicio)) }} - {{ date('g:i A', strtotime($horario->hora_fin)) }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small italic m-0">No hay horarios asignados</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="col-md-3 text-md-end d-flex flex-column justify-content-between">
                            <div>
                                {{-- Switch de estado solo visible para Admins/Asesores --}}
                                @if(Auth::user()->rol !== 'Estudiante')
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input" type="checkbox" role="switch" id="switch-{{ $curso->id_curso }}" 
                                            {{ $curso->estado == 'ACTIVO' ? 'checked' : '' }} style="cursor: pointer; width: 2.5em; height: 1.25em;">
                                        <label class="form-check-label small fw-bold ms-2" for="switch-{{ $curso->id_curso }}">
                                            {{ $curso->estado }}
                                        </label>
                                    </div>
                                @else
                                    <span class="badge {{ $curso->estado == 'ACTIVO' ? 'bg-success' : 'bg-secondary' }}">{{ $curso->estado }}</span>
                                @endif
                            </div>

                            <div class="mt-3">
                                <div class="btn-group w-100 shadow-sm">

                                    {{-- LÓGICA DE SEGURIDAD PARA EDICIÓN Y BORRADO --}}
                                    @if(Auth::user()->rol === 'Administrador' || $curso->correo_persona === Auth::user()->correo)

                                        {{-- Botón Eliminar con SweetAlert2 --}}
                                        <form id="delete-form-{{ $curso->id_curso }}" action="{{ route('cursos.destroy', $curso->id_curso) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmarEliminacion({{ $curso->id_curso }})" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>



                                        {{-- Botón Editar --}}
                                        <a href="{{ route('cursos.edit', $curso->id_curso) }}" class="btn btn-outline-warning btn-sm fw-bold">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        
                                    @endif


                                    {{-- 2. Botón VER (General) --}}
                                   {{-- Botón VER (Siempre visible) --}}
                                    <a href="{{ route('cursos.show', $curso->id_curso) }}" class="btn btn-outline-primary btn-sm fw-bold">VER</a>

                                    {{-- Lógica para Estudiantes --}}
                                    @if(Auth::user()->rol === 'Estudiante')
                                        @if(in_array($curso->id_curso, $misInscripciones))
                                            {{-- CASO: YA INSCRITO --}}
                                            <a href="{{ route('cursos.show', $curso->id_curso) }}" class="btn-minimal-entrar fw-bold">
                                                <i class="bi bi-box-arrow-in-right me-1"></i> ENTRAR
                                            </a>
                                        @else
                                            {{-- CASO: NO INSCRITO -> Formulario para Unirse --}}
                                            <form action="{{ route('cursos.inscribir', $curso->id_curso) }}" method="POST" style="display: contents;">
                                                @csrf
                                                <button type="submit" class="btn-minimal-unirse fw-bold {{ ($curso->estado != 'ACTIVO') ? 'disabled' : '' }}">
                                                    <i class="bi bi-person-plus-fill me-1"></i> UNIRSE
                                                </button>
                                            </form>
                                        @endif

                                    {{-- Lógica para Asesores o Administradores (Solo una vez y con el ELSEIF correcto) --}}
                                    @elseif(Auth::user()->rol === 'Asesor' || Auth::user()->rol === 'Administrador')
                                        <a href="{{ route('cursos.show', $curso->id_curso) }}" class="btn-minimal-entrar fw-bold {{ ($curso->estado != 'ACTIVO') ? 'disabled' : '' }}">
                                            ENTRAR
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-warning text-center shadow-sm">
                    <i class="bi bi-exclamation-circle me-2"></i> No se encontraron cursos que coincidan con la búsqueda.
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    function confirmarEliminacion(id) {
        Swal.fire({
            title: '¿Eliminar curso?',
            text: "Esta acción borrará también los horarios e inscripciones. No se puede deshacer.",
            icon: 'danger',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar todo',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Buscamos el formulario específico por ID y lo enviamos
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
    </script>
</body>
</html>