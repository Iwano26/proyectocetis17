@extends('layouts.app')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('examen.misResultados', $cuestionario->id_cuestionario) }}"
                   class="btn btn-light border fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
                <div>
                    <h4 class="fw-bold mb-0">{{ $cuestionario->nombre_cuestionario }}</h4>
                    <small class="text-muted">
                        Intento #{{ $intento->numero_intento }} &nbsp;·&nbsp;
                        <span class="fw-bold {{ ($intento->calificacion ?? 0) >= 6 ? 'text-success' : 'text-danger' }}">
                            Calificación: {{ $intento->calificacion ?? 'Pendiente de revisión' }}
                        </span>
                    </small>
                </div>
            </div>

            @foreach($intento->respuestas as $i => $respuesta)
                @php $pregunta = $respuesta->pregunta; @endphp

                <div class="card border-0 shadow-sm rounded-3 mb-3"
                     style="border-left: 4px solid
                        {{ $respuesta->es_correcta === null ? '#fbbf24' :
                           ($respuesta->es_correcta ? '#16a34a' : '#dc2626') }} !important;">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="text-muted fw-bold" style="font-size:0.8rem;">
                                PREGUNTA {{ $i + 1 }}
                            </span>
                            @if($respuesta->es_correcta === null)
                                <span class="badge bg-warning text-dark">Pendiente revisión</span>
                            @elseif($respuesta->es_correcta)
                                <span class="badge bg-success"><i class="bi bi-check-lg me-1"></i> Correcta</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-lg me-1"></i> Incorrecta</span>
                            @endif
                        </div>

                        <p class="fw-semibold mb-3">{{ $pregunta->texto_pregunta }}</p>

                        @if($pregunta->tipo === 'abierta')
                            <div class="bg-light rounded p-3 border">
                                <small class="text-muted d-block mb-1 fw-bold text-uppercase" style="font-size:0.7rem;">
                                    Tu respuesta
                                </small>
                                <p class="mb-0">{{ $respuesta->texto_respuesta ?? 'Sin respuesta' }}</p>
                            </div>

                        @elseif($pregunta->tipo === 'multiple_correcta')
                            @php
                                $opcionesSeleccionadas = $respuesta->opcionesMultiples->pluck('id_opcion')->toArray();
                            @endphp
                            @foreach($pregunta->opciones as $opcion)
                                @php $seleccionada = in_array($opcion->id_opcion, $opcionesSeleccionadas); @endphp
                                <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded
                                    @if($opcion->es_correcta && $seleccionada) bg-success bg-opacity-10 border border-success
                                    @elseif($opcion->es_correcta && !$seleccionada) bg-warning bg-opacity-10 border border-warning
                                    @elseif(!$opcion->es_correcta && $seleccionada) bg-danger bg-opacity-10 border border-danger
                                    @else bg-light @endif">
                                    <i class="bi {{ $seleccionada ? 'bi-check-square-fill' : 'bi-square' }}
                                        {{ $seleccionada ? 'text-success' : 'text-muted' }}"></i>
                                    <span>{{ $opcion->texto_opcion }}</span>
                                    @if($opcion->es_correcta)
                                        <span class="ms-auto badge bg-success" style="font-size:0.65rem;">Correcta</span>
                                    @endif
                                </div>
                            @endforeach

                        @else
                            @foreach($pregunta->opciones as $opcion)
                                @php $seleccionada = $respuesta->id_opcion == $opcion->id_opcion; @endphp
                                <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded
                                    @if($seleccionada && $opcion->es_correcta) bg-success bg-opacity-10 border border-success
                                    @elseif($seleccionada && !$opcion->es_correcta) bg-danger bg-opacity-10 border border-danger
                                    @elseif(!$seleccionada && $opcion->es_correcta) bg-warning bg-opacity-10 border border-warning
                                    @else bg-light @endif">
                                    <i class="bi {{ $seleccionada ? 'bi-record-circle-fill' : 'bi-circle' }}
                                        {{ $seleccionada ? ($opcion->es_correcta ? 'text-success' : 'text-danger') : 'text-muted' }}"></i>
                                    <span>{{ $opcion->texto_opcion }}</span>
                                    @if($opcion->es_correcta)
                                        <span class="ms-auto badge bg-success" style="font-size:0.65rem;">Correcta</span>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>

@endsection