@extends('layouts.app')
@section('content')

<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-light border fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
        <div>
            <h4 class="fw-bold mb-0">{{ $cuestionario->nombre_cuestionario }}</h4>
            <small class="text-muted">
                <i class="bi bi-calendar me-1"></i>
                {{ $cuestionario->configuracion
                    ? \Carbon\Carbon::parse($cuestionario->configuracion->fecha_examen)->format('d/m/Y')
                    : 'Sin fecha' }}
                &nbsp;·&nbsp;
                <span class="badge 
                    @if(($cuestionario->configuracion->estado ?? '') === 'ACTIVO') bg-success
                    @elseif(($cuestionario->configuracion->estado ?? '') === 'CERRADO') bg-dark
                    @else bg-warning text-dark @endif">
                    {{ $cuestionario->configuracion->estado ?? 'PENDIENTE' }}
                </span>
            </small>
        </div>
    </div>

    {{-- Estadísticas rápidas --}}
    @php
        $totalAlumnos = $intentos->count();
        $todasCalifs = $intentos->flatten()->where('completado', 1)->pluck('calificacion')->filter();
        $promedio = $todasCalifs->count() > 0 ? round($todasCalifs->avg(), 2) : null;
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-danger">{{ $totalAlumnos }}</div>
                <small class="text-muted text-uppercase fw-bold">Alumnos</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-dark">{{ $intentos->flatten()->where('completado', 1)->count() }}</div>
                <small class="text-muted text-uppercase fw-bold">Intentos Completados</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-success">{{ $promedio ?? '—' }}</div>
                <small class="text-muted text-uppercase fw-bold">Promedio General</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-primary">{{ $cuestionario->configuracion->oportunidades ?? 1 }}</div>
                <small class="text-muted text-uppercase fw-bold">Intentos Permitidos</small>
            </div>
        </div>
    </div>

    {{-- Tabla de resultados --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-dark text-white py-3">
            <h6 class="mb-0 fw-bold">
                <i class="bi bi-people-fill me-2"></i> Resultados por Alumno
            </h6>
        </div>
        <div class="card-body p-0">
            @if($intentos->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted"></i>
                    <p class="text-muted mt-2">Ningún alumno ha realizado el examen aún.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th class="ps-4">Alumno</th>
                                <th>Correo</th>
                                <th class="text-center">Intentos</th>
                                <th class="text-center">Mejor Calificación</th>
                                <th class="text-center">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($intentos as $correo => $intentosAlumno)
                                @php
                                    $primero = $intentosAlumno->first();
                                    $mejorCalif = $intentosAlumno->where('completado', 1)
                                                    ->max('calificacion');
                                @endphp
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        {{ $primero->nombre }} {{ $primero->apellidoPa }} {{ $primero->apellidoMa }}
                                    </td>
                                    <td class="text-muted" style="font-size:0.85rem;">{{ $correo }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center flex-wrap gap-1">
                                            @foreach($intentosAlumno as $intento)
                                                <span class="badge 
                                                    @if(!$intento->completado) bg-secondary
                                                    @elseif($intento->calificacion >= 6) bg-success
                                                    @else bg-danger @endif">
                                                    #{{ $intento->numero_intento }}
                                                    @if($intento->completado)
                                                        — {{ $intento->calificacion ?? '?' }}
                                                    @else
                                                        — En curso
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($mejorCalif !== null)
                                            <span class="fw-bold fs-5 {{ $mejorCalif >= 6 ? 'text-success' : 'text-danger' }}">
                                                {{ $mejorCalif }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1 flex-wrap">
                                            @foreach($intentosAlumno->where('completado', 1) as $intento)
                                                <a href="{{ route('examenes.verIntento', $intento->id_intento) }}"
                                                   class="btn btn-sm btn-outline-dark fw-bold">
                                                    <i class="bi bi-eye me-1"></i> Intento {{ $intento->numero_intento }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection