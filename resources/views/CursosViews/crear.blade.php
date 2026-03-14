<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Curso | CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>
        :root { --cetis-rojo: #8C001A; }
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .card { border: none; border-radius: 15px; }
        .btn-danger { background-color: var(--cetis-rojo); border: none; }
        .btn-danger:hover { background-color: #700014; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            {{-- Importante: Mostrar errores de validación de Laravel si los hay --}}
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
                    {{-- Verifica que la ruta 'gestioncurso.store' sea la correcta en tu Web.php --}}
                    <form action="{{ route('cursos.store') }}" method="POST">
                        @csrf
                        

                        {{-- Agrega esto dentro de tu formulario --}}
                        <input type="hidden" name="correo_persona" value="profe@cetis17.edu.mx">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Nombre del Curso:</label>
                                <input type="text" name="nombre_curso" class="form-control"  placeholder="Ej: Bases de Datos I" value="{{ old('nombre_curso') }}" required maxlength="60">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Materia:</label>
                                <input type="text" name="materia" class="form-control" placeholder="Ej: Programacion" value="{{ old('materia') }}" required maxlength="50">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold small">Descripción del Curso:</label>
                                <textarea name="descripcion" class="form-control" rows="3" placeholder="¿De qué trata el curso?">{{ $curso->descripcion ?? '' }}</textarea>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold small">Fecha de Inicio:</label>
                                <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold small">Fecha de Fin:</label>
                                <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Horas Totales:</label>
                                <input type="number" name="horas_disponibles" placeholder="Ej: 2" class="form-control" value="{{ old('horas_disponibles') }}" min="1" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Acceso clave (opcional):</label>
                                <input type="text" name="acceso" class="form-control" value="{{ old('acceso') }}" placeholder="Ej: PRO20" maxlength="20">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Estado:</label>
                                <select name="estado" class="form-select" required>
                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>
                                    <option value="COMPLETADO">COMPLETADO</option>
                                </select>
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
                        <div class="row g-2 mb-2 fila-horario"> 
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Día:</label>
                                <select name="dia[]" class="form-select" required>
                                    <option value="Lunes">Lunes</option>
                                    <option value="Martes">Martes</option>
                                    <option value="Miércoles">Miércoles</option>
                                    <option value="Jueves">Jueves</option>
                                    <option value="Viernes">Viernes</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Hora Inicio:</label>
                                <input type="time" name="hora_inicio[]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Hora Fin:</label>
                                <input type="time" name="hora_fin[]" class="form-control" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger w-100 eliminar-fila">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ route('cursos.index') }}" class="btn btn-light me-2 fw-bold">CANCELAR</a>
                            <button type="submit" class="btn btn-danger px-4 fw-bold shadow">PUBLICAR CURSO</button>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                        const btnAgregar = document.getElementById('agregar-horario');
                        const contenedorHorarios = document.getElementById('contenedor-horarios');

                        // Lógica para añadir horarios
                        if (btnAgregar && contenedorHorarios) {
                            btnAgregar.addEventListener('click', function() {
                                // Buscamos la fila con la clase correcta
                                const filas = document.querySelectorAll('.fila-horario');
                                
                                if (filas.length > 0) {
                                    const nuevaFila = filas[0].cloneNode(true);
                                    
                                    // Limpiar los datos de la nueva fila
                                    nuevaFila.querySelectorAll('input').forEach(i => i.value = '');
                                    nuevaFila.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
                                    
                                    // Asegurarnos de que el botón de eliminar sea visible en las nuevas filas
                                    const btnEliminar = nuevaFila.querySelector('.eliminar-fila');
                                    if(btnEliminar) btnEliminar.style.display = 'block';
                                    
                                    contenedorHorarios.appendChild(nuevaFila);
                                }
                            });
                        }

                        // Lógica para eliminar (Delegación de eventos)
                        document.addEventListener('click', function(e) {
                            // Detecta si el clic fue en el botón de eliminar o en su icono
                            if (e.target.closest('.eliminar-fila')) {
                                const filas = document.querySelectorAll('.fila-horario');
                                if (filas.length > 1) {
                                    e.target.closest('.fila-horario').remove();
                                } else {
                                    alert("Mínimo un día de horario es requerido.");
                                }
                            }
                        });
                    });
                    </script>

                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

                </div>
            </div>
        </div>
    </div>
</div>



</body>
</html>