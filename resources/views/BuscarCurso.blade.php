@extends('layouts.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('/css/curso.css') }}">
@endpush

@section('content')
<div class="container-fluid mt-4 px-5">

    {{-- Título y botón crear --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="titulo-seccion-cetis m-0" style="font-size: 2.4rem;">Búsqueda de Cursos</h2>
        @if(Auth::user()->rol !== 'Estudiante')
            <a href="{{ route('cursos.create') }}" class="btn btn-danger fw-bold shadow-sm"
               style="background-color: #8C001A; border: none; font-size: 0.85rem; letter-spacing: 0.5px;">
                <i class="bi bi-plus-circle me-2"></i>CREAR CURSO
            </a>
        @endif
    </div>

    {{-- Filtros --}}
    {{-- Filtros corregidos con horarios escolares en BuscarCurso.blade.php --}}
    <form action="{{ route('cursos.index') }}" method="GET">
        <div class="row g-2 mb-4 bg-white p-4 rounded shadow-sm" style="border: 1px solid rgba(0,0,0,0.05);">
            {{-- Nombre --}}
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Buscar por nombre:</label>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                    class="form-control" placeholder="Ej: Álgebra, Programación...">
            </div>
            {{-- Materia --}}
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Filtrar por materia:</label>
                <input type="text" name="materia" value="{{ request('materia') }}"
                    class="form-control" placeholder="Ej: Matemáticas...">
            </div>
            {{-- Día --}}
            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">Día de la semana:</label>
                <select name="dia" class="form-select">
                    <option value="">-- Todos --</option>
                    @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes'] as $dia)
                        <option value="{{ $dia }}" {{ request('dia') == $dia ? 'selected' : '' }}>
                            {{ $dia }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Horario Escolar --}}
            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">Horario/Turno:</label>
                <select name="bloque_horario" class="form-select">
                    <option value="">-- Todos --</option>
                    <option value="matutino" {{ request('bloque_horario') == 'matutino' ? 'selected' : '' }}>Matutino (6 AM - 12 PM)</option>
                    <option value="vespertino" {{ request('bloque_horario') == 'vespertino' ? 'selected' : '' }}>Vespertino (12 PM - 8 PM)</option>
                </select>
            </div>
            {{-- Botón --}}
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-dark w-100 fw-bold py-2 shadow-sm"
                        style="background-color: #1a1a2e; border: none; border-radius: 8px;">
                    <i class="bi bi-search me-1"></i> Buscar
                </button>
            </div>
        </div>
    </form>
    {{-- ── MIS CURSOS ──────────────────────────────────────────────────────── --}}
    @if($misCursos->isNotEmpty())
        <div class="d-flex align-items-center gap-2 mb-3 mt-2">
            <span style="width:5px; height:24px; background:#8C001A; border-radius:3px; display:inline-block;"></span>
            <h5 class="fw-bold mb-0 text-dark">
                @if(Auth::user()->rol === 'Estudiante')
                    <i class="bi bi-bookmark-fill text-danger me-2"></i> Mis Cursos Inscritos
                @else
                    <i class="bi bi-person-badge-fill text-danger me-2"></i> Mis Cursos
                @endif
            </h5>
            <span class="badge bg-danger rounded-pill">{{ $misCursos->count() }}</span>
        </div>

        @foreach($misCursos as $curso)
            @include('partials.tarjeta-curso', ['curso' => $curso, 'misInscripciones' => $misInscripciones])
        @endforeach

        @if($otrosCursos->isNotEmpty())
            <div class="d-flex align-items-center gap-2 mb-3 mt-4">
                <span style="width:5px; height:24px; background:#1a1a2e; border-radius:3px; display:inline-block;"></span>
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-globe me-2"></i> Otros Cursos Disponibles
                </h5>
                <span class="badge bg-secondary rounded-pill">{{ $otrosCursos->count() }}</span>
            </div>
        @endif
    @endif

    {{-- ── OTROS CURSOS ────────────────────────────────────────────────────── --}}
    @forelse($otrosCursos as $curso)
        @include('partials.tarjeta-curso', ['curso' => $curso, 'misInscripciones' => $misInscripciones])
    @empty
        @if($misCursos->isEmpty())
            <div class="alert alert-warning text-center shadow-sm rounded-3">
                <i class="bi bi-exclamation-circle me-2"></i>
                No se encontraron cursos que coincidan con tu búsqueda.
            </div>
        @endif
    @endforelse

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Eliminar curso?',
        text: "Esta acción borrará los horarios e inscripciones vinculadas.",
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
    });
}
</script>

<script>
// Reabrir modal si hubo error de clave
@foreach($misCursos->merge($otrosCursos) as $curso)
    @if(session('error_clave_' . $curso->id_curso))
        var modalClave{{ $curso->id_curso }} = new bootstrap.Modal(
            document.getElementById('modalClave{{ $curso->id_curso }}')
        );
        modalClave{{ $curso->id_curso }}.show();
    @endif
@endforeach
</script>
@endsection