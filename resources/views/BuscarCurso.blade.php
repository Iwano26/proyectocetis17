@extends('layouts.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('/css/curso.css') }}">
@endpush

@section('content')
<div class="container-fluid mt-4 px-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="titulo-seccion-cetis m-0" style="font-size: 2.4rem;">Búsqueda de Cursos</h2>
        
        @if(Auth::user()->rol !== 'Estudiante')
            <a href="{{ route('cursos.create') }}" class="btn btn-danger fw-bold shadow-sm" style="background-color: #8C001A; border: none; font-size: 0.85rem; letter-spacing: 0.5px;">
                <i class="bi bi-plus-circle me-2"></i>CREAR CURSO
            </a>
        @endif
    </div>
    
    <form action="{{ route('cursos.index') }}" method="GET">
        <div class="mb-3">
            <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control form-control-lg shadow-sm input-busqueda" placeholder="Buscar por nombre o materia..." style="font-size: 1rem; border-radius: 10px;">
        </div>

        <div class="seccion-filtros shadow-sm p-4 rounded bg-white mb-4" style="border: 1px solid rgba(0,0,0,0.05);">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label small fw-bold text-secondary">Día de la semana:</label>
                    <select name="dia" class="form-select" style="border-radius: 8px;">
                        <option value="">-- Seleccionar Día --</option>
                        @foreach(['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'] as $dia)
                            <option value="{{ $dia }}" {{ request('dia') == $dia ? 'selected' : '' }}>{{ $dia }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label small fw-bold text-secondary">Estado:</label>
                    <select name="estado" class="form-select" style="border-radius: 8px;">
                        <option value="">-- Todos --</option>
                        <option value="ACTIVO" {{ request('estado') == 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                        <option value="INACTIVO" {{ request('estado') == 'INACTIVO' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-dark w-100 fw-bold py-2 shadow-sm btn-filtrar" style="background-color: #1a1a2e; border: none; border-radius: 8px; font-size: 0.9rem; letter-spacing: 0.5px;">APLICAR FILTROS</button>
                </div>
            </div>
        </div>
    </form>

    <div id="contenedor-cursos">
        @forelse($cursos as $curso)
            <div class="tarjeta-curso shadow-sm bg-white p-4 mb-3 rounded" style="border-left: 5px solid #8C001A; transition: transform 0.2s;">
                <div class="row align-items-center">
                    <div class="col-md-5 d-flex align-items-center">
                        <div class="circulo-rojo me-3 d-flex align-items-center justify-content-center rounded-circle" style="background-color: #8C001A; color: white; width: 45px; height: 45px; min-width: 45px;">
                            <i class="bi bi-book" style="font-size: 1.2rem;"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-uppercase m-0" style="font-size: 1.35rem; letter-spacing: -0.3px;">{{ $curso->nombre_curso }}</h4>
                            <p class="text-muted small mb-1 fw-medium"><i class="bi bi-tags-fill me-1 text-danger"></i>{{ $curso->materia }}</p>
                            <p class="small text-secondary mb-0">
                                {{ Str::limit($curso->descripcion ?? 'Sin descripción disponible.', 100) }}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4 border-start border-end my-3 my-md-0 px-4">
                        <h6 class="fw-bold small text-muted text-uppercase mb-2 text-horario-label" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-clock-history me-1 text-danger"></i> Horarios Asignados:
                        </h6>
                        <div class="row g-2">
                            @forelse($curso->horarios as $horario)
                                <div class="col-12">
                                    <div class="p-2 border rounded bg-light d-flex justify-content-between align-items-center" style="font-size: 0.85rem;">
                                        <span class="fw-bold text-dark"><i class="bi bi-calendar-event text-danger me-1"></i> {{ $horario->dia_semana }}</span>
                                        <span class="badge bg-white text-dark border fw-semibold shadow-sm">
                                            {{ date('g:i A', strtotime($horario->hora_inicio)) }} - {{ date('g:i A', strtotime($horario->hora_fin)) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted small italic m-0">No hay horarios asignados</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-md-3 text-md-end d-flex flex-column justify-content-between h-100">
                        <div class="mb-3">
                            @if(Auth::user()->rol !== 'Estudiante')
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input" type="checkbox" role="switch" id="switch-{{ $curso->id_curso }}" 
                                        {{ $curso->estado == 'ACTIVO' ? 'checked' : '' }} style="cursor: pointer; width: 2.5em; height: 1.25em;">
                                    <label class="form-check-label small fw-bold ms-2 text-uppercase" for="switch-{{ $curso->id_curso }}" style="font-size: 0.8rem;">
                                        {{ $curso->estado }}
                                    </label>
                                </div>
                            @else
                                <span class="badge {{ $curso->estado == 'ACTIVO' ? 'bg-success' : 'bg-secondary' }} px-3 py-2 rounded-pill text-uppercase text-estado-badge" style="font-size: 0.75rem;">{{ $curso->estado }}</span>
                            @endif
                        </div>

                        <div>
                            <div class="btn-group w-100 shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                @if(Auth::user()->rol === 'Administrador' || $curso->correo_persona === Auth::user()->correo)
                                    <form id="delete-form-{{ $curso->id_curso }}" action="{{ route('cursos.destroy', $curso->id_curso) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmarEliminacion({{ $curso->id_curso }})" class="btn btn-sm btn-danger px-3 btn-accion" style="border-radius: 0;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('cursos.edit', $curso->id_curso) }}" class="btn btn-outline-warning btn-sm fw-bold px-3 btn-accion" style="border-radius: 0;">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @endif

                                <a href="{{ route('cursos.show', $curso->id_curso) }}" class="btn btn-outline-primary btn-sm fw-bold d-flex align-items-center justify-content-center px-3 btn-accion" style="font-size: 0.8rem; border-radius: 0;">VER</a>

                                @if(Auth::user()->rol === 'Estudiante')
                                    @if(in_array($curso->id_curso, $misInscripciones))
                                        <a href="{{ route('cursos.show', $curso->id_curso) }}" class="btn btn-success btn-sm fw-bold d-flex align-items-center justify-content-center px-3 btn-accion" style="font-size: 0.8rem; border-radius: 0;">
                                            <i class="bi bi-box-arrow-in-right me-1"></i> ENTRAR
                                        </a>
                                    @else
                                        <form action="{{ route('cursos.inscribir', $curso->id_curso) }}" method="POST" style="display: contents;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm fw-bold px-3 btn-accion" style="background-color: #8C001A; border: none; font-size: 0.8rem; border-radius: 0;" {{ ($curso->estado != 'ACTIVO') ? 'disabled' : '' }}>
                                                <i class="bi bi-person-plus-fill me-1"></i> UNIRSE
                                            </button>
                                        </form>
                                    @endif
                                @elseif(Auth::user()->rol === 'Asesor' || Auth::user()->rol === 'Administrador')
                                    <a href="{{ route('cursos.show', $curso->id_curso) }}" class="btn btn-danger btn-sm fw-bold d-flex align-items-center justify-content-center px-3 btn-accion" style="background-color: #8C001A; border: none; font-size: 0.8rem; border-radius: 0;" {{ ($curso->estado != 'ACTIVO') ? 'disabled' : '' }}>
                                        ENTRAR
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-warning text-center shadow-sm rounded-3 alert-vacio">
                <i class="bi bi-exclamation-circle me-2"></i> No se encontraron cursos activos o registrados que coincidan.
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Eliminar curso?',
        text: "Esta acción borrará los horarios e inscripciones vinculadas. No se puede deshacer.",
        icon: 'warning',
        iconColor: '#8C001A',
        showCancelButton: true,
        confirmButtonColor: '#8C001A',
        cancelButtonColor: '#1a1a2e',
        confirmButtonText: 'Sí, eliminar todo',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>
@endsection