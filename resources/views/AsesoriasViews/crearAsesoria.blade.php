@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Tarjeta del Formulario --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-plus me-2"></i> Agendar Nueva Asesoría</h5>
                    <span class="badge bg-danger text-uppercase">CETIS 17</span>
                </div>
                <div class="card-body p-4">
                    
                    <form action="{{ route('asesorias.store', $id_curso) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="nombre_evento" class="form-label fw-bold text-secondary">Tema o Título de la Asesoría</label>
                            <input type="text" class="form-control form-control-lg border-2" id="nombre_evento" name="nombre_evento" required placeholder="Ej. Repaso de Derivadas Algebráicas">
                        </div>

                        <div class="mb-4">
                            <label for="lugar" class="form-label fw-bold text-secondary">Lugar / Salón o Enlace Virtual</label>
                            <input type="text" class="form-control border-2" id="lugar" name="lugar" required placeholder="Ej. Edificio B - Laboratorio de Cómputo / Link de Meet">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="fecha_asesoria" class="form-label fw-bold text-secondary">Fecha de la Cita</label>
                                <input type="date" class="form-control border-2" id="fecha_asesoria" name="fecha_asesoria" required value="{{ date('Y-m-b') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="hora_inicio" class="form-label fw-bold text-secondary">Hora de Inicio</label>
                                <input type="time" class="form-control border-2" id="hora_inicio" name="hora_inicio" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="hora_fin" class="form-label fw-bold text-secondary">Hora de Fin</label>
                                <input type="time" class="form-control border-2" id="hora_fin" name="hora_fin" required>
                            </div>
                            <div class="mb-4">
                                <label for="estado" class="form-label fw-bold text-secondary">Estado de la Asesoría</label>
                                <select class="form-select border-2" id="estado" name="estado" required>
                                    <option value="DISPONIBLE" selected>DISPONIBLE </option>
                                    <option value="EN_CURSO">EN CURSO</option>
                                    <option value="TERMINADA">TERMINADA</option>
                                    <option value="CANCELADA">CANCELADA</option>
                                </select>
                            </div>
                        </div>
                        {{-- Toggle Evidencia --}}
                        <div class="mb-4 p-3 bg-light rounded border">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <label class="form-label fw-bold text-secondary mb-0">
                                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                        Solicitar Evidencia
                                    </label>
                                    <small class="d-block text-muted mt-1">
                                        Los alumnos inscritos podrán subir un PDF como evidencia de asistencia.
                                    </small>
                                </div>
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" 
                                    id="toggleEvidencia" 
                                    name="requiere_evidencia" 
                                    value="1" 
                                    style="width:3em; height:1.5em; cursor:pointer; accent-color:#8C001A;"
                                    {{ (isset($asesoria) && $asesoria->requiere_evidencia == 1) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">

                        {{-- Botones de Acción --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('curso.eventos', $curso->id_curso) }}" class="btn btn-light border fw-bold px-4">Volver al Curso</a>
                            <button type="submit" class="btn btn-danger fw-bold px-4 shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Publicar y Agendar
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