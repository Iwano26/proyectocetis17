@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i> Editar Asesoría</h5>
                    <span class="badge bg-danger text-uppercase">CETIS 17</span>
                </div>
                <div class="card-body p-4">

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('asesorias.update', $asesoria->id_evento) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Campo oculto para redirigir al curso correcto al guardar --}}
                        <input type="hidden" name="id_curso" value="{{ $asesoria->id_curso }}">

                        <div class="mb-4">
                            <label for="nombre_evento" class="form-label fw-bold text-secondary">
                                Tema o Título de la Asesoría
                            </label>
                            <input type="text" 
                                class="form-control form-control-lg border-2 @error('nombre_evento') is-invalid @enderror" 
                                id="nombre_evento" 
                                name="nombre_evento" 
                                required 
                                value="{{ old('nombre_evento', $asesoria->nombre_evento) }}">
                            @error('nombre_evento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="lugar" class="form-label fw-bold text-secondary">
                                Lugar / Salón o Enlace Virtual
                            </label>
                            <input type="text" 
                                class="form-control border-2 @error('lugar') is-invalid @enderror" 
                                id="lugar" 
                                name="lugar" 
                                required 
                                value="{{ old('lugar', $asesoria->lugar) }}">
                            @error('lugar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="fecha_asesoria" class="form-label fw-bold text-secondary">
                                    Fecha de la Cita
                                </label>
                                <input type="date" 
                                    class="form-control border-2 @error('fecha_asesoria') is-invalid @enderror" 
                                    id="fecha_asesoria" 
                                    name="fecha_asesoria" 
                                    required 
                                    value="{{ old('fecha_asesoria', $asesoria->fecha_asesoria) }}">
                                @error('fecha_asesoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="hora_inicio" class="form-label fw-bold text-secondary">
                                    Hora de Inicio
                                </label>
                                <input type="time" 
                                    class="form-control border-2 @error('hora_inicio') is-invalid @enderror" 
                                    id="hora_inicio" 
                                    name="hora_inicio" 
                                    required 
                                    value="{{ old('hora_inicio', $asesoria->hora_inicio) }}">
                                @error('hora_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="hora_fin" class="form-label fw-bold text-secondary">
                                    Hora de Fin
                                </label>
                                <input type="time" 
                                    class="form-control border-2 @error('hora_fin') is-invalid @enderror" 
                                    id="hora_fin" 
                                    name="hora_fin" 
                                    required 
                                    value="{{ old('hora_fin', $asesoria->hora_fin) }}">
                                @error('hora_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="estado" class="form-label fw-bold text-secondary">
                                    Estado de la Asesoría
                                </label>
                                <select class="form-select border-2 @error('estado') is-invalid @enderror" 
                                    id="estado" name="estado" required>
                                    @foreach(['DISPONIBLE', 'EN_CURSO', 'TERMINADA', 'CANCELADA'] as $opcion)
                                        <option value="{{ $opcion }}" 
                                            {{ old('estado', $asesoria->estado) == $opcion ? 'selected' : '' }}>
                                            {{ $opcion }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ url()->previous() }}" class="btn btn-light border fw-bold px-4">
                                Volver al Curso
                            </a>
                            <button type="submit" class="btn btn-danger fw-bold px-4 shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

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