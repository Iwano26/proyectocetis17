@extends('layouts.app')

@section('content')

<style>


    :root { 
        --cetis-rojo: #8C001A !important; 
        --cetis-primary: #8C001A !important;
    }
    

    body { 
        background-color: #f8f9fa !important; 
    }

    /* CABECERA CON ESTILO INSTITUCIONAL IDÉNTICO */
    .tarjeta-curso-interna {
        background-color: white !important; 
        border: 2px solid #8C001A !important;
        border-radius: 12px !important; 
        padding: 20px !important; 
        margin-bottom: 24px !important;
    }

    /* MENÚ LATERAL UNIFICADO Y CORREGIDO */
    .opciones-curso .nav-link {
        color: #333 !important;
        font-weight: 700 !important;
        border: 1px solid #dee2e6 !important;
        margin-bottom: 8px !important;
        border-radius: 8px !important;
        padding: 15px !important;
        text-align: left !important;
        background-color: white !important;
        display: block !important;
        text-decoration: none !important;
        transition: all 0.2s !important;
    }
    .opciones-curso .nav-link:hover {
        background-color: #f1f3f5 !important;
        color: #8C001A !important;
    }
    .opciones-curso .nav-link.active {
        background-color: #8C001A !important;
        color: white !important;
        border-color: #8C001A !important;
    }

    /* AVATARES DE LA TABLA */
    .avatar-estudiante {
        width: 35px !important;
        height: 35px !important;
        background-color: #e9ecef !important;
        color: #8C001A !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important;
        font-weight: bold !important;
        border: 2px solid #dee2e6 !important;
        font-size: 0.85rem !important;
    }

    /* BOTÓN INSTITUCIONAL */
    .btn-cetis {
        background-color: #8C001A !important;
        color: white !important;
        font-weight: bold !important;
        border-radius: 8px !important;
        padding: 10px 20px !important;
        border: none !important;
        transition: background-color 0.2s !important;
    }
    .btn-cetis:hover {
        background-color: #6d0014 !important;
        color: white !important;
    }

    /* REFUERZO DE TABLAS */
    .table th {
        font-weight: 700 !important;
        color: #6c757d !important;
    }
    .table td {
        color: #212529 !important;
    }
</style>

<div class="container-fluid mt-4 px-4">
        
    {{-- CABECERA DEL CURSO --}}
    <div class="tarjeta-curso-interna shadow-sm bg-white">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h2 class="fw-bold m-0 text-uppercase text-dark" style="letter-spacing: 0.5px;">{{ $curso->nombre_curso }}</h2>
                <p class="text-muted mb-1 mt-1">Impartido por: <strong>{{ $curso->correo_persona }}</strong></p>
                <small class="text-secondary"><i class="bi bi-book me-1"></i> Materia: {{ $curso->materia }}</small>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <span class="badge bg-success mb-2 shadow-sm px-3 py-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    ESTADO: ACTIVO
                </span>
            </div>
        </div>
    </div>

    {{-- ZONA DE CONTENIDO Y MENÚ --}}
    <div class="row">
        
        {{-- BARRA LATERAL --}}
        <div class="col-md-3 mb-4">
            <div class="nav flex-column opciones-curso">
                <a href="{{ route('cursos.show', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-info-circle me-2"></i> INFORMACIÓN
                </a>
                <a href="{{ route('curso.eventos', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-list-task me-2"></i> ACTIVIDADES
                </a>
                <a href="{{ route('foro.index', $curso->id_curso) }}" class="nav-link shadow-sm">
                    <i class="bi bi-chat-dots me-2"></i> FORO
                </a>
                @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Asesor')
                    <a href="{{ route('reportes.index', $curso->id_curso) }}" class="nav-link shadow-sm active">
                        <i class="bi bi-file-earmark-text me-2"></i> REPORTES
                    </a>
                @endif
            </div>
        </div>

        {{-- LISTADO DE REPORTES --}}
        <div class="col-md-9">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #dee2e6;">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-people text-secondary me-2"></i>Listado de Estudiantes
                    </h5>
                    <a href="{{ route('reportes.pdf', $curso->id_curso) }}" class="btn btn-cetis btn-sm shadow-sm px-3">
                        <i class="bi bi-filetype-pdf me-2"></i>DESCARGAR REPORTE MENSUAL
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Nombre del Estudiante</th>
                                    <th class="py-3">Correo Electrónico</th>
                                    <th class="py-3">Grupo</th>
                                    <th class="text-center py-3 pe-4">Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($estudiantes as $inscrito)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-estudiante me-3">
                                                {{ strtoupper(substr($inscrito->estudiante->nombre ?? 'E', 0, 1)) }}
                                            </div>
                                            <span class="fw-semibold text-dark">{{ $inscrito->estudiante->nombre ?? 'Estudiante' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $inscrito->correo_estudiante }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1.5" style="border-radius: 6px; font-weight: 600;">
                                            {{ $inscrito->estudiante->grupo ?? 'S/G' }}
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" title="Editar Bitácora">
                                            <i class="bi bi-pencil-square fs-5"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-people display-4 text-light d-block mb-3"></i>
                                        No hay estudiantes inscritos en este curso de asesoría.
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
</div>

@endsection