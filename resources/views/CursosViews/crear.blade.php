<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Curso | CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --cetis-rojo: #8C001A; }
        body { background-color: #f8f9fa; }
        .card { border: none; border-radius: 15px; }
        .btn-danger { background-color: var(--cetis-rojo); border: none; }
        .btn-outline-danger { color: var(--cetis-rojo); border-color: var(--cetis-rojo); }
        .btn-outline-danger:hover { background-color: var(--cetis-rojo); color: white; }
        .fila-horario { background: #fff; padding: 15px; border-radius: 10px; border: 1px solid #dee2e6; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold mb-0" style="color: var(--cetis-rojo);">
                        CREAR NUEVO CURSO
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('cursos.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Nombre del Curso:</label>
                                <input type="text" name="nombre_curso" class="form-control" placeholder="Ej: Cálculo Integral" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">Materia:</label>
                                <select name="materia" class="form-select" required>
                                    <option value="">-- Seleccionar --</option>
                                    <option value="Programación">Programación</option>
                                    <option value="Matemáticas">Matemáticas</option>
                                    <option value="Soporte Técnico">Soporte Técnico</option>
                                    <option value="Inglés">Inglés</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Estado:</label>
                                <select name="estado" id="selector-estado" class="form-select" required>
                                    <option value="Abierto" selected>Abierto</option>
                                    <option value="Cerrado">Cerrado</option>
                                </select>
                            </div>

                            <div class="col-md-3" id="contenedor-password" style="display: none;">
                                <label class="form-label fw-bold small">Contraseña de acceso:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_curso" id="input-password" class="form-control" placeholder="Ej: 12345">
                                </div>
                                <div class="form-text" style="font-size: 0.7rem;">Solo alumnos con clave podrán unirse.</div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold m-0 small"><i class="bi bi-calendar-check me-2"></i>HORARIOS DISPONIBLES</h5>
                            <button type="button" id="agregar-horario" class="btn btn-sm btn-outline-danger fw-bold">
                                <i class="bi bi-plus-lg"></i> AGREGAR DÍA
                            </button>
                        </div>

                        <div id="contenedor-horarios">
                            <div class="row g-2 fila-horario align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label small">Día:</label>
                                    <select name="dia[]" class="form-select" required>
                                        <option value="Lunes">Lunes</option>
                                        <option value="Martes">Martes</option>
                                        <option value="Miércoles">Miércoles</option>
                                        <option value="Jueves">Jueves</option>
                                        <option value="Viernes">Viernes</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">De:</label>
                                    <input type="time" name="hora_inicio[]" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">A:</label>
                                    <input type="time" name="hora_fin[]" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-light w-100 eliminar-fila" title="Eliminar este horario">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ route('cursos.index') }}" class="btn btn-light me-2 fw-bold">CANCELAR</a>
                            <button type="submit" class="btn btn-danger px-4 fw-bold shadow">PUBLICAR CURSO</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- LÓGICA DE ESTADO Y CONTRASEÑA ---
    const selectorEstado = document.getElementById('selector-estado');
    const contenedorPass = document.getElementById('contenedor-password');
    const inputPass = document.getElementById('input-password');

    function verificarEstado() {
        if (selectorEstado.value === 'Cerrado') {
            contenedorPass.style.display = 'block';
            inputPass.setAttribute('required', 'required');
        } else {
            contenedorPass.style.display = 'none';
            inputPass.removeAttribute('required');
            // No limpiamos el value aquí por si el usuario se equivoca al dar click, 
            // así no pierde la contraseña que ya estaba escrita.
        }
    }

    // Escuchar cambios en el select
    selectorEstado.addEventListener('change', verificarEstado);

    // Ejecutar al cargar la página (Importante para EDITAR)
    document.addEventListener('DOMContentLoaded', verificarEstado);


    // --- LÓGICA DE HORARIOS DINÁMICOS ---
    document.getElementById('agregar-horario').addEventListener('click', function() {
        let contenedor = document.getElementById('contenedor-horarios');
        // Clonamos la primera fila disponible
        let filas = document.querySelectorAll('.fila-horario');
        let nuevaFila = filas[0].cloneNode(true);
        
        // Limpiamos los valores de los inputs clonados
        nuevaFila.querySelectorAll('input').forEach(input => {
            input.value = '';
        });

        // Aseguramos que el select del día no herede la selección del anterior
        nuevaFila.querySelectorAll('select').forEach(select => {
            select.selectedIndex = 0;
        });
        
        contenedor.appendChild(nuevaFila);
    });

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