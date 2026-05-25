@extends('layouts.app')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-pencil-square me-2"></i> Editar Examen
                    </h5>
                    <span class="badge bg-danger">CETIS 17</span>
                </div>
                <div class="card-body p-4">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('examenes.actualizarConfig', $cuestionario->id_cuestionario) }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Título --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Título del Examen</label>
                            <input type="text"
                                name="nombre_cuestionario"
                                class="form-control form-control-lg border-2"
                                value="{{ $cuestionario->nombre_cuestionario }}" required>
                        </div>

                        <hr class="opacity-25">
                        <p class="fw-bold text-secondary mb-3" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px;">
                            Configuración del Examen
                        </p>

                        {{-- Fecha --}}
                        <div class="row">
                        {{-- Fecha de Inicio --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-secondary">Fecha de Inicio</label>
                            <input type="date"
                                name="fecha_examen"
                                class="form-control border-2 @error('fecha_examen') is-invalid @enderror"
                                value="{{ old('fecha_examen', $config->fecha_examen) }}" required>
                            @error('fecha_examen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Fecha de Cierre --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-secondary">Fecha de Cierre</label>
                            <input type="date"
                                name="fecha_cierre"
                                class="form-control border-2 @error('fecha_cierre') is-invalid @enderror"
                                value="{{ old('fecha_cierre', $config->fecha_cierre) }}" required>
                            @error('fecha_cierre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                        {{-- Horas --}}
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold text-secondary">Hora Inicio</label>
                                <input type="time"
                                    id="hora_inicio"
                                    name="hora_inicio"
                                    class="form-control border-2 @error('hora_inicio') is-invalid @enderror"
                                    value="{{ old('hora_inicio', $config->hora_inicio) }}" required>
                                @error('hora_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold text-secondary">Hora Fin</label>
                                <input type="time"
                                    id="hora_fin"
                                    name="hora_fin"
                                    class="form-control border-2 @error('hora_fin') is-invalid @enderror"
                                    value="{{ old('hora_fin', $config->hora_fin) }}" required>
                                @error('hora_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Oportunidades --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Intentos Permitidos</label>
                            <select name="oportunidades" class="form-select border-2" required>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('oportunidades', $config->oportunidades) == $i ? 'selected' : '' }}>
                                        {{ $i }} intento{{ $i > 1 ? 's' : '' }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Estado --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Estado</label>
                            <select name="estado" class="form-select border-2" required>
                                @foreach(['PENDIENTE', 'ACTIVO', 'CERRADO'] as $est)
                                    <option value="{{ $est }}"
                                        {{ old('estado', $config->estado) === $est ? 'selected' : '' }}>
                                        {{ $est }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="opacity-25">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="/curso/{{ $idCurso }}/eventos" class="btn btn-light border fw-bold px-4">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-danger fw-bold px-5 shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function esInvalido() {
    const fInicio = document.querySelector('input[name="fecha_examen"]').value;
    const fCierre = document.querySelector('input[name="fecha_cierre"]').value;
    const hInicio = document.getElementById('hora_inicio').value;
    const hFin = document.getElementById('hora_fin').value;

    if (!fInicio || !fCierre || !hInicio || !hFin) return false;

    // Convertimos todo a un formato comparable (timestamp)
    const inicioCompleto = new Date(fInicio + 'T' + hInicio);
    const cierreCompleto = new Date(fCierre + 'T' + hFin);

    // Si el cierre es antes o igual al inicio, es inválido
    return cierreCompleto <= inicioCompleto;
}

// Validar al enviar el formulario
document.getElementById('formExamen').addEventListener('submit', function(e) {
    // 1. Validar preguntas (esto ya lo tenías)
    const preguntas = document.querySelectorAll('.pregunta-card');
    if (preguntas.length === 0) {
        e.preventDefault();
        alert('Debes agregar al menos una pregunta.');
        return;
    }

    // 2. Validar fechas y horas combinadas
    if (esInvalido()) {
        e.preventDefault();
        alert('Error: La fecha/hora de cierre debe ser posterior a la de inicio.');
        document.getElementById('hora_fin').focus();
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#8C001A',
        confirmButtonText: 'Aceptar',
        timer: 3000,
        timerProgressBar: true,
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        confirmButtonColor: '#8C001A',
        confirmButtonText: 'Aceptar',
    });
@endif
</script>

@endsection