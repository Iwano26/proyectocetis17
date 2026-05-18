@extends('layouts.app')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-light border fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
                <div>
                    <h4 class="fw-bold mb-0">Mis Resultados</h4>
                    <small class="text-muted">{{ $cuestionario->nombre_cuestionario }}</small>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($intentos->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted"></i>
                    <p class="text-muted mt-2">Aún no has realizado este examen.</p>
                    <a href="{{ route('examen.inicio', $cuestionario->id_cuestionario) }}"
                       class="btn btn-danger fw-bold mt-2">
                        Ir al Examen
                    </a>
                </div>
            @else
                @php
                    $mejorCalif = $intentos->where('completado', 1)->max('calificacion');
                @endphp

                {{-- Resumen --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4 text-center">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size:0.7rem;">
                            Mejor Calificación
                        </small>
                        <div class="display-4 fw-bold {{ ($mejorCalif ?? 0) >= 6 ? 'text-success' : 'text-danger' }}">
                            {{ $mejorCalif ?? '—' }}
                        </div>
                        <small class="text-muted">
                            {{ $intentos->where('completado', 1)->count() }}
                            de {{ $cuestionario->configuracion->oportunidades ?? 1 }} intentos usados
                        </small>
                    </div>
                </div>

                {{-- Lista de intentos --}}
                @foreach($intentos as $intento)
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-bold">Intento #{{ $intento->numero_intento }}</span>
                                <small class="text-muted d-block">
                                    {{ \Carbon\Carbon::parse($intento->fecha_inicio)->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                @if($intento->completado)
                                    <span class="fw-bold fs-4 {{ ($intento->calificacion ?? 0) >= 6 ? 'text-success' : 'text-danger' }}">
                                        {{ $intento->calificacion ?? '—' }}
                                    </span>
                                    <a href="{{ route('examen.verMiIntento', $intento->id_intento) }}"
                                       class="btn btn-sm btn-outline-dark fw-bold">
                                        <i class="bi bi-eye me-1"></i> Ver
                                    </a>
                                @else
                                    <span class="badge bg-warning text-dark">En curso</span>
                                    <a href="{{ route('examen.responder', $intento->id_intento) }}"
                                       class="btn btn-sm btn-danger fw-bold">
                                        <i class="bi bi-play-fill me-1"></i> Continuar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Botón intentar de nuevo --}}
                @if($intentos->count() < ($cuestionario->configuracion->oportunidades ?? 1)
                    && ($cuestionario->configuracion->estado ?? '') === 'ACTIVO')
                    <div class="text-center mt-3">
                        <a href="{{ route('examen.inicio', $cuestionario->id_cuestionario) }}"
                           class="btn btn-danger fw-bold px-5">
                            <i class="bi bi-arrow-repeat me-2"></i> Intentar de Nuevo
                        </a>
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>

@endsection