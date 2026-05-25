@extends('layouts.app')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-light border fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
                <div>
                    <h4 class="fw-bold mb-0">Evidencias — {{ $asesoria->nombre_evento }}</h4>
                    <small class="text-muted">
                        <i class="bi bi-people-fill me-1"></i>
                        {{ $evidencias->count() }} evidencia(s) subida(s)
                    </small>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
            @endif

            @if($evidencias->isEmpty())
                <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                    <i class="bi bi-file-earmark-x display-4 text-muted"></i>
                    <p class="text-muted mt-3 fw-semibold">Ningún alumno ha subido evidencia aún.</p>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-dark text-white py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                            Lista de Evidencias
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background:#f8f9fa;">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Alumno</th>
                                    <th>Correo</th>
                                    <th>Fecha subida</th>
                                    <th class="text-center">Evidencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evidencias as $i => $ev)
                                    <tr>
                                        <td class="ps-4 text-muted fw-bold">{{ $i + 1 }}</td>
                                        <td class="fw-semibold">
                                            {{ $ev->nombre }} {{ $ev->apellidoPa }} {{ $ev->apellidoMa }}
                                        </td>
                                        <td class="text-muted" style="font-size:0.85rem;">
                                            {{ $ev->correo }}
                                        </td>
                                        <td class="text-muted" style="font-size:0.85rem;">
                                            {{ \Carbon\Carbon::parse($ev->fecha_subida)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ asset('storage/' . $ev->archivo) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-danger fw-bold px-3">
                                                <i class="bi bi-file-earmark-pdf me-1"></i> Ver PDF
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection