@extends('layouts.app')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('agenda') }}" class="btn btn-light border fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
                <div>
                    <h4 class="fw-bold mb-0">Mis Solicitudes</h4>
                    <small class="text-muted">Historial de solicitudes enviadas</small>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($solicitudes->isEmpty())
                <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                    <i class="bi bi-envelope-open display-4 text-muted"></i>
                    <p class="text-muted mt-3 fw-semibold">Aún no has enviado ninguna solicitud.</p>
                    <a href="{{ route('solicitud.create') }}" class="btn btn-danger fw-bold mt-2">
                        <i class="bi bi-send-fill me-2"></i> Enviar Solicitud
                    </a>
                </div>
            @else
                @foreach($solicitudes as $sol)
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-body p-4">

                            <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $sol->nombre_curso }}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($sol->fecha_solicitud)->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                @php
                                    $badge = match($sol->estado) {
                                        'PENDIENTE'  => 'bg-warning text-dark',
                                        'ACEPTADA'   => 'bg-success',
                                        'RECHAZADA'  => 'bg-danger',
                                        default      => 'bg-secondary'
                                    };
                                    $icon = match($sol->estado) {
                                        'PENDIENTE'  => 'bi-hourglass-split',
                                        'ACEPTADA'   => 'bi-check-circle-fill',
                                        'RECHAZADA'  => 'bi-x-circle-fill',
                                        default      => 'bi-question-circle'
                                    };
                                @endphp
                                <span class="badge {{ $badge }} px-3 py-2 fs-6">
                                    <i class="bi {{ $icon }} me-1"></i> {{ $sol->estado }}
                                </span>
                            </div>

                            <div class="bg-light rounded p-3 border mb-3">
                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size:0.7rem;">
                                    Mi solicitud
                                </small>
                                <p class="mb-0" style="font-size:0.9rem;">{{ $sol->motivo }}</p>
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    <i class="bi bi-calendar-week text-danger me-1"></i>
                                    {{ $sol->dia_sugerido }}
                                </span>
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    <i class="bi bi-clock text-danger me-1"></i>
                                    {{ \Carbon\Carbon::parse($sol->hora_sugerida)->format('h:i A') }}
                                </span>
                            </div>

                            @if($sol->estado === 'ACEPTADA')
                                <div class="alert alert-success mt-3 mb-0 py-2" style="font-size:0.85rem;">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Tu solicitud fue <strong>aceptada</strong>. Prepárate para tu asesoría.
                                </div>
                            @elseif($sol->estado === 'RECHAZADA')
                                <div class="alert alert-danger mt-3 mb-0 py-2" style="font-size:0.85rem;">
                                    <i class="bi bi-x-circle-fill me-2"></i>
                                    Tu solicitud fue <strong>rechazada</strong>. Puedes enviar una nueva.
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
</div>

@endsection