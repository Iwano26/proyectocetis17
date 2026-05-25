@extends('layouts.app')
@section('content')

<style>
    :root { --cetis-rojo: #8C001A; }

    .pregunta-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 16px;
        position: relative;
        border-left: 4px solid var(--cetis-rojo);
    }

    .btn-agregar-opcion {
        background: none;
        border: 1px dashed #ccc;
        border-radius: 8px;
        padding: 8px 16px;
        color: #888;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
        text-align: left;
    }

    .btn-agregar-opcion:hover { border-color: var(--cetis-rojo); color: var(--cetis-rojo); }

    .opcion-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .opcion-row input[type="text"] {
        flex: 1;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.875rem;
    }

    .opcion-row input[type="text"]:focus {
        outline: none;
        border-color: var(--cetis-rojo);
    }

    .btn-eliminar-opcion {
        background: none;
        border: none;
        color: #ccc;
        cursor: pointer;
        font-size: 1rem;
        padding: 4px;
        transition: color 0.2s;
    }

    .btn-eliminar-opcion:hover { color: #e00; }

    .btn-eliminar-pregunta {
        position: absolute;
        top: 16px;
        right: 16px;
        background: none;
        border: 1px solid #eee;
        border-radius: 6px;
        color: #ccc;
        cursor: pointer;
        padding: 4px 8px;
        font-size: 0.8rem;
        transition: all 0.2s;
    }

    .btn-eliminar-pregunta:hover { border-color: #e00; color: #e00; }

    .tipo-badge {
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 3px 8px;
        border-radius: 4px;
        background: #fff0f2;
        color: var(--cetis-rojo);
        margin-bottom: 12px;
    }

    .check-correcta {
        width: 18px;
        height: 18px;
        accent-color: var(--cetis-rojo);
        cursor: pointer;
    }

    .seccion-header {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #999;
        margin-bottom: 8px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- Header --}}
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-light border fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
                <div>
                    <h4 class="fw-bold mb-0">Crear Examen</h4>
                    <small class="text-muted">Diseña tu examen con diferentes tipos de preguntas</small>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('examenes.store', $id_curso) }}" method="POST" id="formExamen">
                @csrf

                {{-- Datos generales --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-dark text-white py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-file-earmark-text me-2"></i> Información General
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Título del Examen</label>
                            <input type="text" name="nombre_cuestionario"
                                class="form-control form-control-lg border-2"
                                placeholder="Ej. Examen Parcial — Álgebra Lineal"
                                value="{{ old('nombre_cuestionario') }}" required>
                        </div>
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-secondary">Fecha de Inicio</label>
                                    <input type="date" name="fecha_examen" class="form-control border-2" 
                                        value="{{ old('fecha_examen', $config->fecha_examen ?? '') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-secondary">Fecha de Cierre</label>
                                    <input type="date" name="fecha_cierre" class="form-control border-2" 
                                        value="{{ old('fecha_cierre', $config->fecha_cierre ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold text-secondary">Hora Inicio</label>
                                <input type="time" name="hora_inicio"
                                id="hora_inicio"
                                class="form-control border-2"
                                value="{{ old('hora_inicio') }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold text-secondary">Hora Fin</label>
                                <input type="time" name="hora_fin"
                                id="hora_fin"
                                class="form-control border-2"
                                value="{{ old('hora_fin') }}" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold text-secondary">Intentos</label>
                                <select name="oportunidades" class="form-select border-2" required>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('oportunidades') == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Preguntas --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Preguntas</h5>
                    <span class="badge bg-danger rounded-pill" id="contadorPreguntas">0 preguntas</span>
                </div>

                <div id="contenedorPreguntas"></div>

                {{-- Botones agregar pregunta --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-3">
                        <p class="seccion-header mb-2">Agregar pregunta de tipo:</p>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" onclick="agregarPregunta('abierta')"
                                class="btn btn-outline-secondary btn-sm fw-bold">
                                <i class="bi bi-textarea-t me-1"></i> Abierta
                            </button>
                            <button type="button" onclick="agregarPregunta('verdadero_falso')"
                                class="btn btn-outline-secondary btn-sm fw-bold">
                                <i class="bi bi-toggle-on me-1"></i> Verdadero / Falso
                            </button>
                            <button type="button" onclick="agregarPregunta('opcion_multiple')"
                                class="btn btn-outline-secondary btn-sm fw-bold">
                                <i class="bi bi-ui-radios me-1"></i> Opción Múltiple
                            </button>
                            <button type="button" onclick="agregarPregunta('multiple_correcta')"
                                class="btn btn-outline-danger btn-sm fw-bold">
                                <i class="bi bi-ui-checks me-1"></i> Varias Correctas
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Guardar --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-light border fw-bold px-4">Cancelar</a>
                    <button type="submit" class="btn btn-danger fw-bold px-5 shadow-sm">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Publicar Examen
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
let contadorPreguntas = 0;

const tipoLabels = {
    'abierta': 'Respuesta Abierta',
    'verdadero_falso': 'Verdadero / Falso',
    'opcion_multiple': 'Opción Múltiple',
    'multiple_correcta': 'Varias Correctas'
};

function actualizarContador() {
    const total = document.querySelectorAll('.pregunta-card').length;
    document.getElementById('contadorPreguntas').textContent = total + ' pregunta' + (total !== 1 ? 's' : '');
}

function agregarPregunta(tipo) {
    const index = contadorPreguntas++;
    const container = document.getElementById('contenedorPreguntas');

    const div = document.createElement('div');
    div.className = 'pregunta-card';
    div.id = 'pregunta_' + index;

    let opcionesHTML = '';

    if (tipo === 'verdadero_falso') {
        opcionesHTML = `
            <div class="mt-3">
                <p class="seccion-header">Marca la opción correcta:</p>
                <div class="opcion-row">
                    <input type="radio" name="preguntas[${index}][correcta_vf]" value="verdadero" class="check-correcta" required>
                    <input type="hidden" name="preguntas[${index}][opciones][0][texto]" value="Verdadero">
                    <input type="hidden" name="preguntas[${index}][opciones][0][correcta]" class="correcta-vf-v" value="">
                    <span class="fw-semibold">Verdadero</span>
                </div>
                <div class="opcion-row">
                    <input type="radio" name="preguntas[${index}][correcta_vf]" value="falso" class="check-correcta">
                    <input type="hidden" name="preguntas[${index}][opciones][1][texto]" value="Falso">
                    <input type="hidden" name="preguntas[${index}][opciones][1][correcta]" class="correcta-vf-f" value="">
                    <span class="fw-semibold">Falso</span>
                </div>
            </div>`;
    } else if (tipo === 'opcion_multiple') {
        opcionesHTML = `
            <div class="mt-3">
                <p class="seccion-header">Opciones — marca la correcta con el círculo:</p>
                <div class="opciones-container" id="opciones_${index}"></div>
                <button type="button" class="btn-agregar-opcion mt-1" onclick="agregarOpcion(${index}, 'radio')">
                    <i class="bi bi-plus me-1"></i> Agregar opción
                </button>
            </div>`;
    } else if (tipo === 'multiple_correcta') {
        opcionesHTML = `
            <div class="mt-3">
                <p class="seccion-header">Opciones — marca todas las correctas:</p>
                <div class="opciones-container" id="opciones_${index}"></div>
                <button type="button" class="btn-agregar-opcion mt-1" onclick="agregarOpcion(${index}, 'checkbox')">
                    <i class="bi bi-plus me-1"></i> Agregar opción
                </button>
            </div>`;
    }

    div.innerHTML = `
        <button type="button" class="btn-eliminar-pregunta" onclick="eliminarPregunta('pregunta_${index}')">
            <i class="bi bi-trash3"></i>
        </button>
        <div class="tipo-badge">${tipoLabels[tipo]}</div>
        <input type="hidden" name="preguntas[${index}][tipo]" value="${tipo}">
        <div class="mb-2">
            <textarea
                name="preguntas[${index}][texto]"
                class="form-control border-2"
                rows="2"
                placeholder="Escribe aquí la pregunta..."
                required></textarea>
        </div>
        ${opcionesHTML}
    `;

    container.appendChild(div);

    // Verdadero/Falso: sincronizar hidden con radio
    if (tipo === 'verdadero_falso') {
        const radios = div.querySelectorAll('input[type="radio"]');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                div.querySelectorAll('.correcta-vf-v, .correcta-vf-f').forEach(h => h.value = '');
                if (this.value === 'verdadero') {
                    div.querySelector('.correcta-vf-v').value = '1';
                } else {
                    div.querySelector('.correcta-vf-f').value = '1';
                }
            });
        });
    }

    // Agregar 2 opciones por defecto
    if (tipo === 'opcion_multiple') {
        agregarOpcion(index, 'radio');
        agregarOpcion(index, 'radio');
    } else if (tipo === 'multiple_correcta') {
        agregarOpcion(index, 'checkbox');
        agregarOpcion(index, 'checkbox');
    }

    actualizarContador();
}

let opcionCounters = {};

function agregarOpcion(preguntaIndex, tipoInput) {
    if (!opcionCounters[preguntaIndex]) opcionCounters[preguntaIndex] = 0;
    const opcionIndex = opcionCounters[preguntaIndex]++;

    const container = document.getElementById('opciones_' + preguntaIndex);
    const div = document.createElement('div');
    div.className = 'opcion-row';

    const inputName = `preguntas[${preguntaIndex}][opciones][${opcionIndex}][correcta]`;
    const inputTipo = tipoInput === 'radio'
        ? `<input type="radio" name="preguntas[${preguntaIndex}][radio_correcta]" class="check-correcta" onchange="marcarCorrectaRadio(this, ${preguntaIndex}, ${opcionIndex})">`
        : `<input type="checkbox" class="check-correcta" onchange="marcarCorrectaCheck(this, '${inputName}')">`;

    div.innerHTML = `
        ${inputTipo}
        <input type="hidden" name="${inputName}" value="">
        <input type="text"
            name="preguntas[${preguntaIndex}][opciones][${opcionIndex}][texto]"
            placeholder="Opción ${opcionIndex + 1}"
            required>
        <button type="button" class="btn-eliminar-opcion" onclick="this.parentElement.remove()">
            <i class="bi bi-x-circle"></i>
        </button>
    `;

    container.appendChild(div);
}

function marcarCorrectaRadio(radioEl, preguntaIndex, opcionIndex) {
    // Desmarcar todas las opciones de este grupo
    const container = document.getElementById('opciones_' + preguntaIndex);
    container.querySelectorAll('input[type="hidden"]').forEach(h => h.value = '');
    // Marcar la seleccionada
    const hiddens = container.querySelectorAll('input[type="hidden"]');
    if (hiddens[opcionIndex]) hiddens[opcionIndex].value = '1';
}

function marcarCorrectaCheck(checkEl, inputName) {
    const hidden = checkEl.parentElement.querySelector('input[type="hidden"]');
    hidden.value = checkEl.checked ? '1' : '';
}

function eliminarPregunta(id) {
    document.getElementById(id).remove();
    actualizarContador();
}

document.getElementById('formExamen').addEventListener('submit', function(e) {
    const preguntas = document.querySelectorAll('.pregunta-card');
    if (preguntas.length === 0) {
        e.preventDefault();
        alert('Debes agregar al menos una pregunta.');
    }
});

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
    // 1. Validar preguntas
    const preguntas = document.querySelectorAll('.pregunta-card');
    if (preguntas.length === 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: '¡Atención!',
            text: 'Debes agregar al menos una pregunta.',
            confirmButtonColor: '#8C001A',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // 2. Validar fechas y horas combinadas
    if (esInvalido()) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error de horario',
            text: 'La fecha/hora de cierre debe ser posterior a la de inicio.',
            confirmButtonColor: '#8C001A',
            confirmButtonText: 'Corregir'
        }).then(() => {
            // Opcional: enfocar el campo tras cerrar la alerta
            document.getElementById('hora_fin').focus();
        });
    }
});



</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: '¡Oops!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#8C001A', // El color que usas en otros lados
                confirmButtonText: 'Entendido'
            });
        });
    </script>
@endif

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