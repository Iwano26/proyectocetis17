@extends('layouts.app')
@section('content')

<style>
    :root { --cetis-rojo: #8C001A; }
    .curso-item {
        padding: 12px 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        border: 1.5px solid transparent;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .curso-item:hover { background: #fff0f2; border-color: #8C001A; color: #8C001A; }
    .curso-item.activo { background: #8C001A; color: white; border-color: #8C001A; }
    .solicitud-card {
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 12px;
        background: white;
        transition: box-shadow 0.2s;
    }
    .solicitud-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
</style>

<div class="container-fluid px-4 py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('principal') }}" class="btn btn-light border fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
            <div>
                <h4 class="fw-bold mb-0">
                    <i class="bi bi-envelope-fill text-danger me-2"></i> Bandeja de Solicitudes
                </h4>
                <small class="text-muted">Solicitudes de asesoría de tus alumnos</small>
            </div>
        </div>
        @if($totalPendientes > 0)
            <span class="badge bg-danger px-3 py-2 fs-6">
                <i class="bi bi-clock-fill me-1"></i>
                {{ $totalPendientes }} pendiente{{ $totalPendientes != 1 ? 's' : '' }}
            </span>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Sidebar cursos --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-journal-bookmark me-2"></i> Mis Cursos
                    </h6>
                </div>
                <div class="card-body p-2">
                    <div class="curso-item activo mb-1" data-curso="todos" onclick="filtrarCurso('todos', this)">
                        <i class="bi bi-grid-fill me-2"></i> Todos los cursos
                        <span class="badge bg-danger ms-1">{{ $solicitudes->count() }}</span>
                    </div>
                    @foreach($misCursos as $curso)
                        @php
                            $countCurso = $solicitudes->where('id_curso', $curso->id_curso)->count();
                            $pendCurso  = $solicitudes->where('id_curso', $curso->id_curso)->where('estado', 'PENDIENTE')->count();
                        @endphp
                        <div class="curso-item mb-1"
                             data-curso="{{ $curso->id_curso }}"
                             onclick="filtrarCurso('{{ $curso->id_curso }}', this)">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ Str::limit($curso->nombre_curso, 22) }}</span>
                                @if($pendCurso > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $pendCurso }}</span>
                                @endif
                            </div>
                            <small style="font-size:0.72rem; opacity:0.7;">{{ $curso->materia }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Solicitudes --}}
        <div class="col-md-9">

            @if($solicitudes->isEmpty())
                <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                    <i class="bi bi-envelope-open display-4 text-muted"></i>
                    <p class="text-muted mt-3 fw-semibold">No hay solicitudes aún.</p>
                </div>
            @else

                {{-- Filtros de estado --}}
                <div class="d-flex gap-2 mb-3 flex-wrap">
                    <button class="btn btn-sm btn-dark fw-bold filtro-estado activo-estado"
                            onclick="filtrarEstado('todos', this)">
                        Todas <span class="badge bg-secondary ms-1">{{ $solicitudes->count() }}</span>
                    </button>
                    <button class="btn btn-sm btn-outline-warning fw-bold filtro-estado"
                            onclick="filtrarEstado('PENDIENTE', this)">
                        Pendientes
                        <span class="badge bg-warning text-dark ms-1">{{ $solicitudes->where('estado','PENDIENTE')->count() }}</span>
                    </button>
                    <button class="btn btn-sm btn-outline-success fw-bold filtro-estado"
                            onclick="filtrarEstado('ACEPTADA', this)">
                        Aceptadas
                        <span class="badge bg-success ms-1">{{ $solicitudes->where('estado','ACEPTADA')->count() }}</span>
                    </button>
                    <button class="btn btn-sm btn-outline-danger fw-bold filtro-estado"
                            onclick="filtrarEstado('RECHAZADA', this)">
                        Rechazadas
                        <span class="badge bg-danger ms-1">{{ $solicitudes->where('estado','RECHAZADA')->count() }}</span>
                    </button>
                </div>

                <div id="listaSolicitudes">
                    @foreach($solicitudes as $sol)
                        <div class="solicitud-card"
                             data-curso="{{ $sol->id_curso }}"
                             data-estado="{{ $sol->estado }}">

                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

                                {{-- Info alumno --}}
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                         style="width:46px; height:46px; background:#f8f9fa; border:2px solid #dee2e6; font-weight:800; color:#8C001A; font-size:1.1rem;">
                                        {{ strtoupper(substr($sol->nombre, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">
                                            {{ $sol->nombre }} {{ $sol->apellidoPa }} {{ $sol->apellidoMa ?? '' }}
                                        </div>
                                        <small class="text-muted">{{ $sol->correo_alumno }}</small>
                                        <div class="mt-1">
                                            <span class="badge bg-light text-dark border" style="font-size:0.72rem;">
                                                <i class="bi bi-journal-bookmark me-1 text-danger"></i>
                                                {{ $sol->nombre_curso }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Estado --}}
                                <div class="text-end">
                                    @php
                                        $badgeSol = match($sol->estado) {
                                            'PENDIENTE'  => 'bg-warning text-dark',
                                            'ACEPTADA'   => 'bg-success',
                                            'RECHAZADA'  => 'bg-danger',
                                            default      => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeSol }} px-3 py-2">
                                        {{ $sol->estado }}
                                    </span>
                                    <small class="d-block text-muted mt-1" style="font-size:0.75rem;">
                                        {{ \Carbon\Carbon::parse($sol->fecha_solicitud)->format('d/m/Y H:i') }}
                                    </small>
                                </div>

                            </div>

                            {{-- Motivo --}}
                            <div class="bg-light rounded p-3 my-3 border">
                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size:0.7rem;">
                                    Motivo de la solicitud
                                </small>
                                <p class="mb-0" style="font-size:0.9rem;">{{ $sol->motivo }}</p>
                            </div>

                            {{-- Horario sugerido --}}
                            <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                                <div class="d-flex align-items-center gap-2 bg-light border rounded px-3 py-2">
                                    <i class="bi bi-calendar-week text-danger"></i>
                                    <span class="fw-semibold" style="font-size:0.85rem;">
                                        {{ $sol->dia_sugerido }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2 bg-light border rounded px-3 py-2">
                                    <i class="bi bi-clock text-danger"></i>
                                    <span class="fw-semibold" style="font-size:0.85rem;">
                                        {{ \Carbon\Carbon::parse($sol->hora_sugerida)->format('h:i A') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Acciones --}}
                            @if($sol->estado === 'PENDIENTE')
                                <div class="d-flex gap-2">
                                    <form action="{{ route('solicitud.aceptar', $sol->id_solicitud) }}" method="POST" class="form-accion-sol">
                                        @csrf
                                        <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm">
                                            <i class="bi bi-check-lg me-1"></i> Aceptar
                                        </button>
                                    </form>
                                    <form action="{{ route('solicitud.rechazar', $sol->id_solicitud) }}" method="POST" class="form-rechazar-sol">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger fw-bold px-4">
                                            <i class="bi bi-x-lg me-1"></i> Rechazar
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>

            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// SweetAlert sesión
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: @json(session('success')),
        confirmButtonColor: '#8C001A',
        timer: 3000,
        timerProgressBar: true,
    });
@endif

// Filtrar por curso
function filtrarCurso(idCurso, el) {
    document.querySelectorAll('.curso-item').forEach(i => i.classList.remove('activo'));
    el.classList.add('activo');

    document.querySelectorAll('.solicitud-card').forEach(card => {
        if (idCurso === 'todos' || card.dataset.curso === idCurso) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

// Filtrar por estado
let estadoActual = 'todos';
let cursoActual  = 'todos';

function filtrarEstado(estado, el) {
    document.querySelectorAll('.filtro-estado').forEach(b => b.classList.remove('activo-estado', 'btn-dark', 'btn-warning', 'btn-success', 'btn-danger'));
    el.classList.add('activo-estado');
    estadoActual = estado;
    aplicarFiltros();
}

function aplicarFiltros() {
    document.querySelectorAll('.solicitud-card').forEach(card => {
        const matchEstado = estadoActual === 'todos' || card.dataset.estado === estadoActual;
        const matchCurso  = cursoActual === 'todos'  || card.dataset.curso  === cursoActual;
        card.style.display = (matchEstado && matchCurso) ? '' : 'none';
    });
}

// Confirmar rechazar
document.querySelectorAll('.form-rechazar-sol').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Rechazar solicitud?',
            text: 'El alumno será notificado.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#8C001A',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'Cancelar',
        }).then(r => { if (r.isConfirmed) form.submit(); });
    });
});

// Confirmar aceptar
document.querySelectorAll('.form-accion-sol').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Aceptar solicitud?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, aceptar',
            cancelButtonText: 'Cancelar',
        }).then(r => { if (r.isConfirmed) form.submit(); });
    });
});
</script>

@endsection