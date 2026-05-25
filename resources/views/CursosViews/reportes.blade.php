@extends('layouts.app')

@section('content')

<style>
    :root {
        --cetis-rojo: #8C001A;
        --cetis-rojo-light: #fff0f2;
    }

    /* ── Cabecera del curso ── */
    .tarjeta-curso-interna {
        background-color: white;
        border: 2px solid var(--cetis-rojo);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
    }

    /* ── Menú lateral ── */
    .opciones-curso .nav-link {
        color: #333;
        font-weight: 700;
        border: 1px solid #dee2e6;
        margin-bottom: 8px;
        border-radius: 8px;
        padding: 15px;
        text-align: left;
        background-color: white;
        display: block;
        text-decoration: none;
        transition: all 0.2s;
    }
    .opciones-curso .nav-link:hover {
        background-color: var(--cetis-rojo-light);
        color: var(--cetis-rojo);
    }
    .opciones-curso .nav-link.active {
        background-color: var(--cetis-rojo) !important;
        color: white !important;
        border-color: var(--cetis-rojo) !important;
    }
    .opciones-curso .nav-link.active i {
        color: white !important;
    }

    /* ── Tabla ── */
    .tabla-reportes {
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    .tabla-reportes thead th {
        background-color: #f8f9fa;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.8px;
        color: #adb5bd;
        text-transform: uppercase;
        border-bottom: 1px solid #e9ecef;
        padding: 14px 16px;
    }
    .tabla-reportes tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.15s;
    }
    .tabla-reportes tbody tr:last-child {
        border-bottom: none;
    }
    .tabla-reportes tbody tr:hover {
        background-color: var(--cetis-rojo-light);
    }
    .tabla-reportes tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        color: #212529;
        font-size: 0.9rem;
    }

    /* ── Avatar estudiante ── */
    .avatar-estudiante {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8C001A, #c0002a);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.85rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(140, 0, 26, 0.25);
    }

    /* ── Botón PDF ── */
    .btn-cetis {
        background: linear-gradient(135deg, #8C001A, #c0002a);
        color: white;
        font-weight: 700;
        border-radius: 8px;
        border: none;
        transition: opacity 0.2s;
        font-size: 0.85rem;
    }
    .btn-cetis:hover {
        opacity: 0.9;
        color: white;
    }

    /* ── Encabezado de la card ── */
    .card-header-reportes {
        background: white;
        border-bottom: 2px solid #f0f0f0;
        padding: 18px 20px;
    }

    /* ── Badge grupo ── */
    .badge-grupo {
        background-color: #f1f3f5;
        color: #495057;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    /* ── Estado vacío ── */
    .tabla-vacio {
        padding: 60px 20px;
        text-align: center;
        color: #adb5bd;
    }
</style>

<div class="container-fluid mt-4 px-4">

    {{-- ── CABECERA DEL CURSO ── --}}
    <div class="tarjeta-curso-interna shadow-sm bg-white mb-4">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h2 class="fw-bold m-0 text-uppercase">{{ $curso->nombre_curso }}</h2>
                <p class="text-muted mb-1 mt-1">Impartido por: <strong>{{ $curso->nombre_asesor ?? $curso->correo_persona }}</strong></p>
                <small class="text-secondary">
                    <i class="bi bi-book me-1 text-danger"></i> Materia: {{ $curso->materia }}
                </small>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <span class="badge {{ $curso->estado == 'ACTIVO' ? 'bg-success' : 'bg-secondary' }} mb-2 shadow-sm">
                    ESTADO: {{ $curso->estado }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- ── MENÚ LATERAL ── --}}
        <div class="col-md-3 mb-4">
            <div class="nav flex-column opciones-curso">
                <a href="{{ route('cursos.show', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-info-circle me-2 text-danger"></i> INFORMACIÓN
                </a>
                <a href="{{ route('curso.eventos', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-list-task me-2 text-danger"></i> ACTIVIDADES
                </a>
                <a href="{{ route('foro.index', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-chat-dots me-2 text-danger"></i> FORO
                </a>
                @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Asesor')
                    <a href="{{ route('reportes.index', $curso->id_curso) }}" class="nav-link shadow-sm active">
                        <i class="bi bi-file-earmark-text me-2"></i> REPORTES
                    </a>
                @endif
            </div>
        </div>

        {{-- ── CONTENIDO REPORTES ── --}}
        <div class="col-md-9">
            <div class="bg-white shadow-sm tabla-reportes">

                {{-- Encabezado de la card --}}
                <div class="card-header-reportes d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0" style="color: #212529; font-size: 1.1rem;">
                            <i class="bi bi-people me-2" style="color: var(--cetis-rojo);"></i>Listado de Estudiantes
                        </h5>
                        <small class="text-muted" style="font-size: 0.8rem;">
                            {{ $estudiantes->count() }} {{ $estudiantes->count() == 1 ? 'estudiante inscrito' : 'estudiantes inscritos' }}
                        </small>
                    </div>
                    <a href="{{ route('reportes.pdf', $curso->id_curso) }}" class="btn btn-cetis shadow-sm px-4 py-2">
                        <i class="bi bi-filetype-pdf me-2"></i> DESCARGAR PDF
                    </a>
                </div>

                {{-- Tabla --}}
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Estudiante</th>
                                <th>Correo</th>
                                <th>Grupo</th>
                                <th class="text-center pe-4">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($estudiantes as $index => $inscrito)
                            <tr>
                                {{-- Número --}}
                                <td class="ps-4 text-muted fw-bold" style="font-size: 0.8rem; width: 50px;">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Nombre con avatar --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-estudiante">
                                            {{ strtoupper(substr($inscrito->estudiante->nombre ?? 'E', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="fw-bold mb-0" style="font-size: 0.9rem; color: #212529;">
                                                {{ $inscrito->estudiante->nombre ?? 'Estudiante' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Correo --}}
                                <td>
                                    <span class="text-muted" style="font-size: 0.85rem;">
                                        <i class="bi bi-envelope me-1 text-danger" style="font-size: 0.75rem;"></i>
                                        {{ $inscrito->correo_estudiante }}
                                    </span>
                                </td>

                                {{-- Grupo --}}
                                <td>
                                    <span class="badge-grupo">
                                        <i class="bi bi-diagram-3 me-1"></i>
                                        {{ $inscrito->estudiante->grupo ?? 'S/G' }}
                                    </span>
                                </td>

                                {{-- Acción --}}
                                <td class="text-center pe-4">
                                    <button class="btn btn-sm fw-bold px-3 py-1"
                                            title="Editar Bitácora"
                                            style="border: 2px solid var(--cetis-rojo); color: var(--cetis-rojo); border-radius: 8px; background: white; font-size: 0.78rem; transition: all 0.2s;"
                                            onmouseover="this.style.background='#8C001A'; this.style.color='white';"
                                            onmouseout="this.style.background='white'; this.style.color='#8C001A';">
                                        <i class="bi bi-pencil-square me-1"></i> Bitácora
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="tabla-vacio">
                                        <div style="font-size: 3rem; opacity: 0.15; color: var(--cetis-rojo);">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <p class="fw-bold mb-1 mt-3" style="color: #555;">Sin estudiantes inscritos</p>
                                        <p class="mb-0" style="font-size: 0.85rem;">Aún no hay estudiantes registrados en este curso.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection