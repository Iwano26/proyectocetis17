@extends('layouts.app')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-file-earmark-check me-2"></i> {{ $cuestionario->nombre_cuestionario }}
                    </h5>
                </div>
                <div class="card-body p-4">

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    {{-- Info del examen --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="bg-light rounded p-3 text-center border">
                                <small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.7rem;">Fecha</small>
                                <span class="fw-bold">
                                    {{ $config ? \Carbon\Carbon::parse($config->fecha_examen)->format('d/m/Y') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-3 text-center border">
                                <small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.7rem;">Horario</small>
                                <span class="fw-bold">
                                    {{ $config ? \Carbon\Carbon::parse($config->hora_inicio)->format('h:i A') : 'N/A' }}
                                    —
                                    {{ $config ? \Carbon\Carbon::parse($config->hora_fin)->format('h:i A') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-3 text-center border">
                                <small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.7rem;">Preguntas</small>
                                <span class="fw-bold">{{ $cuestionario->preguntas->count() }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-3 text-center border">
                                <small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.7rem;">Oportunidades</small>
                                <span class="fw-bold">
                                    {{ $intentosHechos->count() }} / {{ $config->oportunidades ?? 1 }} usadas
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Estado del examen --}}
                    @php
                        $estado = $config->estado ?? 'PENDIENTE';
                    @endphp

                    @if($estado === 'PENDIENTE')
                        <div class="alert alert-warning d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history fs-5"></i>
                            <div>
                                <strong>El examen aún no ha comenzado.</strong><br>
                                <small>Estará disponible el {{ \Carbon\Carbon::parse($config->fecha_examen)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($config->hora_inicio)->format('h:i A') }}</small>
                            </div>
                        </div>
                    @elseif($estado === 'CERRADO')
                        <div class="alert alert-secondary d-flex align-items-center gap-2">
                            <i class="bi bi-lock-fill fs-5"></i>
                            <div><strong>Este examen ya está cerrado.</strong></div>
                        </div>
                    @endif

                    {{-- Intentos anteriores --}}
                    @if($intentosHechos->isNotEmpty())
                        <div class="mb-4">
                            <p class="fw-bold text-secondary mb-2" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px;">
                                Tus intentos anteriores
                            </p>
                            @foreach($intentosHechos as $intento)
                                <div class="d-flex align-items-center justify-content-between p-2 rounded border mb-2 bg-light">
                                    <span class="fw-semibold">Intento #{{ $intento->numero_intento }}</span>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($intento->completado)
                                            <span class="fw-bold {{ ($intento->calificacion ?? 0) >= 6 ? 'text-success' : 'text-danger' }}">
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
                            @endforeach
                        </div>
                    @endif

                    {{-- Botón iniciar --}}
                    @if($puedeIntentar)
                        <div class="alert alert-light border mb-3" style="font-size:0.85rem;">
                            <i class="bi bi-info-circle text-danger me-2"></i>
                            Una vez que inicies el examen, el tiempo corre. Responde todas las preguntas antes de que termine el horario.
                        </div>
                        <form action="{{ route('examen.iniciar', $cuestionario->id_cuestionario) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger fw-bold w-100 py-3 shadow-sm">
                                <i class="bi bi-play-circle-fill me-2"></i>
                                Iniciar Intento #{{ $intentosHechos->count() + 1 }}
                            </button>
                        </form>
                    @elseif($intentosHechos->count() >= ($config->oportunidades ?? 1))
                        <div class="alert alert-danger text-center fw-bold mb-0">
                            <i class="bi bi-x-circle me-2"></i> Ya usaste todas tus oportunidades.
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>

@endsection