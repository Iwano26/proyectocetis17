@extends('layouts.app')
@section('content')

<style>
    :root { --cetis-rojo: #8C001A; }
    .card { border: none; border-radius: 15px; }
    .btn-danger { background-color: var(--cetis-rojo); border: none; }
    .btn-danger:hover { background-color: #700014; }
</style>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold mb-0" style="color: var(--cetis-rojo);">
                        <i class="bi bi-file-earmark-plus-fill me-2"></i>CREAR NUEVO CURSO
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('cursos.store') }}" method="POST" id="formCrearCurso">
                        @csrf

                        <div class="row g-3">
                            {{-- Nombre --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Nombre del Curso:</label>
                                <input type="text" name="nombre_curso" class="form-control"
                                    placeholder="Ej: Bases de Datos I"
                                    value="{{ old('nombre_curso') }}" required maxlength="60">
                            </div>

                            {{-- Materia con select + campo libre --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Materia:</label>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Materia:</label>
                                    <input type="text" name="materia" class="form-control"
                                        placeholder="Ej: Matemáticas, MAT, Progra..."
                                        value="{{ old('materia') }}"
                                        required maxlength="50"
                                        list="sugerencias-materias">
                                    <datalist id="sugerencias-materias">
                                        <option value="Matemáticas">
                                        <option value="Física">
                                        <option value="Química">
                                        <option value="Programación">
                                        <option value="Inglés">
                                        <option value="Historia">
                                        <option value="Biología">
                                        <option value="Contabilidad">
                                        <option value="Administración">
                                    </datalist>
                                    <small class="text-muted" style="font-size:0.72rem;">
                                        Puedes escribir libremente o elegir una sugerencia.
                                    </small>
                                </div>
                            </div>

                            {{-- Descripción --}}
                            <div class="col-md-12">
                                <label class="form-label fw-bold small">Descripción del Curso:</label>
                                <textarea name="descripcion" class="form-control" rows="3"
                                    placeholder="¿De qué trata el curso?">{{ old('descripcion') }}</textarea>
                            </div>

                            {{-- Fechas y horas --}}
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">Fecha de Inicio:</label>
                                <input type="date" name="fecha_inicio" class="form-control"
                                    value="{{ old('fecha_inicio') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">Fecha de Fin:</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                                    value="{{ old('fecha_fin') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Horas Totales:</label>
                                <input type="number" name="horas_disponibles" class="form-control"
                                    placeholder="Ej: 2" value="{{ old('horas_disponibles') }}" min="1" required>
                            </div>

                            {{-- Clave de acceso con toggle --}}
                            <div class="col-md-2">
                                <label class="form-label fw-bold small d-block">Clave de acceso:</label>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="toggleClave" role="switch">
                                    <label class="form-check-label small text-muted" for="toggleClave">
                                        Requerir clave
                                    </label>
                                </div>
                                <input type="text" name="acceso" id="inputClave" class="form-control"
                                    placeholder="Ej: PRO20" maxlength="20"
                                    style="display:none;" value="{{ old('acceso') }}">
                            </div>

                            {{-- Estado --}}
                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Estado:</label>
                                <select name="estado" class="form-select" required>
                                    <option value="ACTIVO" {{ old('estado') == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                                    <option value="INACTIVO" {{ old('estado') == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                                    <option value="COMPLETADO" {{ old('estado') == 'COMPLETADO' ? 'selected' : '' }}>COMPLETADO</option>
                                </select>
                                <small class="text-muted" style="font-size:0.7rem;">
                                    INACTIVO = solo tú lo ves
                                </small>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Horarios --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold m-0 small">
                                <i class="bi bi-clock me-2"></i>GESTIONAR HORARIOS
                            </h5>
                            <button type="button" id="agregar-horario" class="btn btn-sm btn-outline-dark fw-bold">
                                <i class="bi bi-plus-lg"></i> AÑADIR OTRO DÍA
                            </button>
                        </div>

                        <div id="contenedor-horarios">
                            <div class="row g-2 mb-2 fila-horario">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Día:</label>
                                    <select name="dia[]" class="form-select" required>
                                        @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes'] as $d)
                                            <option value="{{ $d }}">{{ $d }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Hora Inicio:</label>
                                    <input type="time" name="hora_inicio[]" id="hora_inicio_0" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Hora Fin:</label>
                                    <input type="time" name="hora_fin[]" id="hora_fin_0" class="form-control" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger w-100 eliminar-fila">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('cursos.index') }}" class="btn btn-light fw-bold">CANCELAR</a>
                            <button type="submit" class="btn btn-danger px-4 fw-bold shadow">PUBLICAR CURSO</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {


    // ── Toggle clave de acceso ─────────────────────────────────────────────
    const toggleClave = document.getElementById('toggleClave');
    const inputClave  = document.getElementById('inputClave');

    // Si venía con valor (old input), activar el toggle
    if (inputClave.value) {
        toggleClave.checked = true;
        inputClave.style.display = 'block';
    }

    toggleClave.addEventListener('change', function() {
        if (this.checked) {
            inputClave.style.display = 'block';
            inputClave.focus();
        } else {
            inputClave.style.display = 'none';
            inputClave.value = '';
        }
    });

    // ── Validar fecha fin > fecha inicio ──────────────────────────────────
    document.getElementById('formCrearCurso').addEventListener('submit', function(e) {
        const inicio = document.querySelector('[name="fecha_inicio"]').value;
        const fin    = document.getElementById('fecha_fin').value;
        if (inicio && fin && fin <= inicio) {
            e.preventDefault();
            alert('La fecha de fin debe ser posterior a la fecha de inicio.');
            document.getElementById('fecha_fin').focus();
        }
    });

    // ── Horarios: agregar / eliminar ──────────────────────────────────────
    document.getElementById('agregar-horario').addEventListener('click', function() {
        const filas = document.querySelectorAll('.fila-horario');
        if (filas.length > 0) {
            const nuevaFila = filas[0].cloneNode(true);
            nuevaFila.querySelectorAll('input').forEach(i => i.value = '');
            nuevaFila.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
            document.getElementById('contenedor-horarios').appendChild(nuevaFila);
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar-fila')) {
            const filas = document.querySelectorAll('.fila-horario');
            if (filas.length > 1) {
                e.target.closest('.fila-horario').remove();
            } else {
                alert('Mínimo un día de horario es requerido.');
            }
        }
    });
});
</script>

@endsection