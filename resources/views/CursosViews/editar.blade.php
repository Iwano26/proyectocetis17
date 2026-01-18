<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso | CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --cetis-rojo: #8C001A; }
        body { background-color: #f8f9fa; }
        .card { border: none; border-radius: 15px; }
        .btn-warning { background-color: #ffc107; border: none; color: black; font-weight: bold; }
        .fila-horario { background: #fff; padding: 15px; border-radius: 10px; border: 1px solid #dee2e6; margin-bottom: 10px; }
        .input-group-text { background-color: white; border-right: none; }
        .input-password-field { border-left: none; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>EDITAR CURSO: {{ $curso->nombre_curso }}
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('cursos.update', $curso->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Nombre del Curso:</label>
                                <input type="text" name="nombre_curso" class="form-control" value="{{ $curso->nombre_curso }}" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">Materia:</label>
                                <select name="materia" class="form-select" required>
                                    @foreach(['Programación', 'Matemáticas', 'Soporte Técnico', 'Inglés'] as $opcion)
                                        <option value="{{ $opcion }}" {{ $curso->materia == $opcion ? 'selected' : '' }}>
                                            {{ $opcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Estado:</label>
                                <select name="estado" id="selector-estado" class="form-select" required>
                                    <option value="Abierto" {{ $curso->estado == 'Abierto' ? 'selected' : '' }}>Abierto</option>
                                    <option value="Cerrado" {{ $curso->estado == 'Cerrado' ? 'selected' : '' }}>Cerrado</option>
                                </select>
                            </div>

                            <div class="col-md-3" id="contenedor-password" style="display: none;">
                                <label class="form-label fw-bold small">Contraseña de acceso:</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-lock-fill text-warning"></i>
                                    </span>
                                    <input type="password" name="password_curso" id="input-password" 
                                        class="form-control border-start-0" 
                                        value="{{ $curso->password_curso ?? '' }}" 
                                        placeholder="Clave">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold m-0 small"><i class="bi bi-clock me-2"></i>GESTIONAR HORARIOS</h5>
                            <button type="button" id="agregar-horario" class="btn btn-sm btn-outline-dark fw-bold">
                                <i class="bi bi-plus-lg"></i> AÑADIR OTRO DÍA
                            </button>
                        </div>

                        <div id="contenedor-horarios">
                            @foreach($curso->horarios as $horario)
                            <div class="row g-2 fila-horario align-items-end shadow-sm">
                                <div class="col-md-4">
                                    <label class="form-label small">Día:</label>
                                    <select name="dia[]" class="form-select" required>
                                        @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia)
                                            <option value="{{ $dia }}" {{ $horario->dia == $dia ? 'selected' : '' }}>{{ $dia }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Hora Inicio:</label>
                                    <input type="time" name="hora_inicio[]" class="form-control" value="{{ $horario->hora_inicio }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Hora Fin:</label>
                                    <input type="time" name="hora_fin[]" class="form-control" value="{{ $horario->hora_fin }}" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-light w-100 eliminar-fila">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-5 d-flex justify-content-end">
                            <a href="{{ route('cursos.index') }}" class="btn btn-light me-2 fw-bold">CANCELAR</a>
                            <button type="submit" class="btn btn-warning px-5 shadow">GUARDAR CAMBIOS</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Lógica para Contraseña y Estado
    const selectorEstado = document.getElementById('selector-estado');
    const contenedorPass = document.getElementById('contenedor-password');
    const inputPass = document.getElementById('input-password');

    function actualizarInterfazEstado() {
    if (selectorEstado.value === 'Cerrado') {
            contenedorPass.style.display = 'block'; // Esto lo muestra correctamente en la fila
            inputPass.setAttribute('required', 'required');
        } else {
            contenedorPass.style.display = 'none';
            inputPass.removeAttribute('required');
        }
    }

    selectorEstado.addEventListener('change', actualizarInterfazEstado);
    document.addEventListener('DOMContentLoaded', actualizarInterfazEstado);

    // Lógica para añadir filas de horario
    document.getElementById('agregar-horario').addEventListener('click', function() {
        let contenedor = document.getElementById('contenedor-horarios');
        let filas = document.querySelectorAll('.fila-horario');
        let nuevaFila = filas[0].cloneNode(true);
        
        nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
        nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        
        contenedor.appendChild(nuevaFila);
    });

    // Lógica para eliminar filas
    document.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar-fila')) {
            let filas = document.querySelectorAll('.fila-horario');
            if (filas.length > 1) {
                e.target.closest('.fila-horario').remove();
            } else {
                alert("El curso debe tener al menos un horario.");
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>