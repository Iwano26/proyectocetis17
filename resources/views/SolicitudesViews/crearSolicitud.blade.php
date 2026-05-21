@extends('layouts.app')
@section('content')

<style>
    :root { --cetis-rojo: #8C001A; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('agenda') }}" class="btn btn-light border fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
                <div>
                    <h4 class="fw-bold mb-0">Solicitar Asesoría</h4>
                    <small class="text-muted">Envía una solicitud a tu asesor</small>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-send-fill text-danger me-2"></i> Nueva Solicitud
                    </h5>
                    <span class="badge bg-danger">CETIS 17</span>
                </div>
                <div class="card-body p-4">

                    @if($cursosInscritos->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-journal-x display-4 text-muted"></i>
                            <p class="text-muted mt-3">No estás inscrito en ningún curso activo.</p>
                            <a href="{{ route('cursos.index') }}" class="btn btn-danger fw-bold mt-2">
                                Buscar Cursos
                            </a>
                        </div>
                    @else
                        <form action="{{ route('solicitud.store') }}" method="POST" id="formSolicitud">
                            @csrf

                            {{-- Curso --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary">
                                    Curso <span class="text-danger">*</span>
                                </label>
                                <select name="id_curso" id="selectCurso"
                                    class="form-select border-2 @error('id_curso') is-invalid @enderror"
                                    required>
                                    <option value="">-- Selecciona un curso --</option>
                                    @foreach($cursosInscritos as $curso)
                                        <option value="{{ $curso->id_curso }}"
                                            {{ old('id_curso') == $curso->id_curso ? 'selected' : '' }}>
                                            {{ $curso->nombre_curso }} — {{ $curso->materia }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_curso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Motivo --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary">
                                    ¿En qué necesitas asesoría? <span class="text-danger">*</span>
                                </label>
                                <textarea name="motivo" id="motivo"
                                    class="form-control border-2 @error('motivo') is-invalid @enderror"
                                    rows="4"
                                    maxlength="500"
                                    placeholder="Describe brevemente el tema o duda que necesitas resolver..."
                                    required>{{ old('motivo') }}</textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    @error('motivo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted ms-auto">
                                        <span id="contadorMotivo">0</span>/500
                                    </small>
                                </div>
                            </div>

                            {{-- Horario sugerido --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary">
                                    Horario Sugerido <span class="text-danger">*</span>
                                </label>
                                <small class="d-block text-muted mb-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Selecciona primero un curso para ver sus horarios disponibles.
                                </small>

                                {{-- Loading --}}
                                <div id="loadingHorarios" style="display:none;">
                                    <div class="d-flex align-items-center gap-2 text-muted">
                                        <div class="spinner-border spinner-border-sm text-danger"></div>
                                        <span>Cargando horarios...</span>
                                    </div>
                                </div>

                                {{-- Sin horarios --}}
                                <div id="sinHorarios" class="alert alert-light border" style="display:none;">
                                    <i class="bi bi-calendar-x me-2 text-muted"></i>
                                    Este curso no tiene horarios registrados.
                                </div>

                                {{-- Horarios como tarjetas seleccionables --}}
                                <div id="contenedorHorarios" class="row g-2"></div>
                                <input type="hidden" name="id_horario" id="inputHorario"
                                    value="{{ old('id_horario') }}">
                                @error('id_horario')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="opacity-25">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('agenda') }}" class="btn btn-light border fw-bold px-4">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-danger fw-bold px-5 shadow-sm"
                                        id="btnEnviar" disabled>
                                    <i class="bi bi-send-fill me-2"></i> Enviar Solicitud
                                </button>
                            </div>

                        </form>
                    @endif
                </div>
            </div>

            {{-- Mis solicitudes anteriores --}}
            <div class="text-center mt-3">
                <a href="{{ route('solicitud.mis') }}" class="text-muted small">
                    <i class="bi bi-clock-history me-1"></i> Ver mis solicitudes anteriores
                </a>
            </div>

        </div>
    </div>
</div>

<script>
const selectCurso    = document.getElementById('selectCurso');
const contenedor     = document.getElementById('contenedorHorarios');
const inputHorario   = document.getElementById('inputHorario');
const loadingDiv     = document.getElementById('loadingHorarios');
const sinHorariosDiv = document.getElementById('sinHorarios');
const btnEnviar      = document.getElementById('btnEnviar');

// Contador de caracteres
document.getElementById('motivo').addEventListener('input', function() {
    document.getElementById('contadorMotivo').textContent = this.value.length;
});

// Cargar horarios al cambiar curso
selectCurso.addEventListener('change', function() {
    const idCurso = this.value;
    contenedor.innerHTML = '';
    inputHorario.value = '';
    btnEnviar.disabled = true;
    sinHorariosDiv.style.display = 'none';

    if (!idCurso) return;

    loadingDiv.style.display = 'block';

    fetch(`/solicitud/horarios/${idCurso}`)
        .then(r => r.json())
        .then(horarios => {
            loadingDiv.style.display = 'none';

            if (horarios.length === 0) {
                sinHorariosDiv.style.display = 'block';
                return;
            }

            horarios.forEach(h => {
                const col = document.createElement('div');
                col.className = 'col-md-6';

                const inicio = h.hora_inicio.substring(0, 5);
                const fin    = h.hora_fin.substring(0, 5);

                col.innerHTML = `
                    <div class="horario-opcion border rounded-3 p-3 d-flex align-items-center gap-3"
                         style="cursor:pointer; transition: all 0.2s;"
                         data-id="${h.id_horario}"
                         onclick="seleccionarHorario(this, '${h.id_horario}')">
                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                             style="width:42px; height:42px; background:#fff0f2; color:#8C001A;">
                            <i class="bi bi-calendar-week-fill"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">${h.dia_semana}</div>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>${inicio} — ${fin}
                            </small>
                        </div>
                    </div>`;

                contenedor.appendChild(col);
            });
        })
        .catch(() => {
            loadingDiv.style.display = 'none';
            sinHorariosDiv.style.display = 'block';
        });
});

function seleccionarHorario(el, id) {
    // Desmarcar todos
    document.querySelectorAll('.horario-opcion').forEach(o => {
        o.style.background = '';
        o.style.borderColor = '';
        o.style.color = '';
    });

    // Marcar seleccionado
    el.style.background = '#fff0f2';
    el.style.borderColor = '#8C001A';
    el.style.color = '#8C001A';

    inputHorario.value = id;
    btnEnviar.disabled = false;
}

// Si venía con old input, recargar horarios
@if(old('id_curso'))
    selectCurso.value = '{{ old('id_curso') }}';
    selectCurso.dispatchEvent(new Event('change'));
@endif
</script>

@endsection