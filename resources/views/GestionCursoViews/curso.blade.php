@extends('layouts.app')

@section('content')

{{-- Cargamos las hojas de estilos institucionales si no se inyectan en app.blade --}}
<link rel="stylesheet" href="{{ asset('css/menuiz.css') }}">
<link rel="stylesheet" href="{{ asset('css/home.css') }}">

<style>
    /* ========== OPTIMIZACIÓN DE ACCESIBILIDAD Y TAMAÑO DE LETRA (MAYORES DE 40 ANOS) ========== */
    
    .section-gestion-cursos {
        padding: 40px 0 80px;
        /* Aumentamos el tamaño de letra base de toda la sección */
        font-size: 1.1rem; 
    }
    
    .card-gestion {
        background: #ffffff;
        border: 1px solid rgba(140, 0, 26, 0.12);
        border-radius: 16px;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .card-header-cetis {
        background-color: var(--cetis-rojo, #8C001A) !important;
        color: white !important;
        font-family: 'Georgia', serif;
        font-weight: bold;
        font-size: 1.35rem; /* Título del formulario más grande */
        padding: 18px 24px;
        border-bottom: none;
    }

    /* Etiquetas de los campos del formulario */
    .form-label {
        font-weight: 700;
        font-size: 1.1rem; /* Texto de etiquetas grande y claro */
        color: var(--cetis-texto, #1a1a2e);
        margin-bottom: 8px;
        display: block;
    }

    /* Cajas de texto e inputs grandes y legibles */
    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid rgba(140, 0, 26, 0.25); /* Bordes ligeramente más oscuros para mejor contraste */
        padding: 0.8rem 1rem; /* Más espacio interno para que respire el texto */
        font-family: 'Inter', sans-serif;
        font-size: 1.1rem; /* Texto interno grande */
        color: #1a1a2e;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--cetis-rojo-claro, #b0001f);
        box-shadow: 0 0 0 0.25rem rgba(140, 0, 26, 0.15);
    }
    
    /* Placeholders más oscuros para que se lean bien */
    .form-control::placeholder {
        color: #72777a;
        opacity: 1;
    }

    /* ========== ESTILOS DE LA TABLA CON LETRA GRANDE ========== */
    
    /* Encabezados de la tabla */
    .table-cursos th {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 1.05rem; /* Encabezados grandes */
        color: #2c3e50;
        padding: 16px 18px;
        background-color: #f8f9fa;
        border-bottom: 3px solid rgba(140, 0, 26, 0.15);
    }

    /* Celdas de la tabla */
    .table-cursos td {
        padding: 18px 18px; /* Mayor separación por fila para que no se amontone la vista */
        font-family: 'Inter', sans-serif;
        font-size: 1.05rem; /* Texto de datos grande */
        color: #1a1a2e;
    }

    /* Nombre del Curso principal */
    .curso-title {
        font-family: 'Georgia', serif;
        font-weight: bold;
        color: var(--cetis-rojo, #8C001A); /* Color institucional para destacar */
        font-size: 1.2rem; /* Título del curso destacado */
        margin-bottom: 4px;
    }

    /* Textos secundarios de la tabla */
    .text-muted-grande {
        color: #4a5568 !important; /* Un gris más oscuro para que tenga alto contraste */
        font-size: 0.95rem;
        font-weight: 500;
    }

    .badge-estado {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 0.9rem; /* Badge más legible */
        padding: 6px 12px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    /* Botones de acción grandes y fáciles de pulsar */
    .btn-action-grande {
        padding: 10px 14px; /* Botones más amplios */
        border-radius: 8px;
        font-size: 1.05rem;
        transition: transform 0.15s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-action-grande:hover {
        transform: scale(1.05); /* Efecto de aumento sutil al pasar el mouse */
    }
    
    /* Botón de enviar formulario */
    .btn-submit-grande {
        font-size: 1.15rem;
        font-weight: 700;
        padding: 12px 28px;
        border-radius: 8px;
    }
</style>

<section class="section-gestion-cursos">
    <div class="container-fluid px-4">
        
        {{-- Encabezado Estilo Institucional --}}
        <div class="text-center mb-5">
            <h2 class="section-titulo" style="font-size: 2.3rem;">
                <i class="bi bi-journal-bookmark-fill me-2" style="color: var(--cetis-rojo);"></i>Gestión de Cursos
            </h2>
            <p class="section-subtitulo" style="font-size: 1.2rem; color: #4a5568;">Administra la oferta académica, asignación de asesores responsables y fechas de control</p>
        </div>

        <div class="row">
            {{-- Columna del Formulario (Ancho 4) --}}
            <div class="col-12 col-lg-4 mb-4">
                <div class="card card-gestion">
                    <div class="card-header card-header-cetis">
                        <span id="formTitle"><i class="bi bi-file-earmark-plus-fill me-1"></i> Registro de Curso</span>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('gestioncurso.store') }}" id="registroForm" class="needs-validation" novalidate>
                            @csrf 
                            <input type="hidden" name="_method" value="POST" id="methodField">
                            <input type="hidden" name="id_curso" id="id_cursoField">

                            <div class="mb-3">
                                <label for="correo_persona" class="form-label">Correo del Asesor Responsable</label>
                                <input type="email" name="correo_persona" id="correo_persona" placeholder="ejemplo@cetis17.edu.mx" class="form-control" value="{{ old('correo_persona') }}" maxlength="150" required>
                            </div>

                            <div class="mb-3">
                                <label for="nombre_curso" class="form-label">Nombre del Curso</label>
                                <input type="text" name="nombre_curso" id="nombre_curso" placeholder="Ej: Programación Web en Laravel" class="form-control" value="{{ old('nombre_curso') }}" maxlength="255" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="materia" class="form-label">Materia Vinculada</label>
                                <input type="text" name="materia" id="materia" placeholder="Ej: Desarrolla Aplicaciones Web" class="form-control" value="{{ old('materia') }}" maxlength="100" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha de Cierre</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="horas_disponibles" class="form-label">Horas Totales</label>
                                    <input type="number" name="horas_disponibles" id="horas_disponibles" class="form-control" value="{{ old('horas_disponibles') }}" min="1" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="estado" class="form-label">Estado actual</label>
                                    <select name="estado" id="estado" class="form-select" required>
                                        <option value="" disabled selected>Seleccione...</option>
                                        <option value="ACTIVO">ACTIVO</option>
                                        <option value="INACTIVO">INACTIVO</option>
                                        <option value="COMPLETADO">COMPLETADO</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-light d-none" id="cancelarEdicionBtn" onclick="cancelarEdicion()" style="border-radius: 8px; font-weight:700; font-size:1.1rem; padding: 12px 20px;">Cancelar</button>
                                <button type="submit" class="btn btn-cetis-primary btn-submit-grande px-4" id="submitBtn">Registrar Curso</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            {{-- Columna de la Tabla (Ancho 8 - Espaciosa y holgada) --}}
            <div class="col-12 col-lg-8">
                <div class="card card-gestion">
                    <div class="card-header card-header-cetis">
                        <i class="bi bi-list-check me-1"></i> Oferta de Cursos Vigentes
                    </div>                  
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-cursos align-middle m-0">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;" class="text-center">ID</th>
                                        <th>Detalles del Curso</th>
                                        <th>Materia</th>
                                        <th>Vigencia del Periodo</th>
                                        <th class="text-center" style="width: 130px;">Estado</th>
                                        <th class="text-center" style="width: 140px;">Acciones</th>                                        
                                    </tr>
                                </thead>
                                <tbody> 
                                    @forelse ($cursos as $curso) 
                                    <tr>
                                        <td class="text-center text-muted fw-bold" style="font-size: 1.1rem;">{{ $curso->id_curso }}</td>
                                        <td>
                                            <div class="curso-title">{{ $curso->nombre_curso }}</div>
                                            <div class="text-muted-grande mt-1">
                                                <i class="bi bi-person-badge me-1"></i><strong>Asesor:</strong> {{ $curso->correo_persona }}
                                            </div>
                                            <div class="text-muted-grande mt-1">
                                                <i class="bi bi-clock me-1"></i><strong>Duración:</strong> {{ $curso->horas_disponibles }} horas.
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold" style="color: #2c3e50;">{{ $curso->materia }}</span>
                                        </td>
                                        <td>
                                            <div class="text-nowrap">
                                                <span class="text-success fw-bold">Inicia:</span> {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}
                                            </div>
                                            <div class="text-nowrap mt-1">
                                                <span class="text-danger fw-bold">Cierra:</span> {{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-estado {{ $curso->estado == 'ACTIVO' ? 'bg-success' : ($curso->estado == 'INACTIVO' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                                {{ $curso->estado }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            {{-- Contenedor con suficiente holgura y botones grandes para evitar clics erróneos --}}
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-warning btn-sm btn-action-grande text-white" 
                                                    title="Editar Curso"
                                                    onclick="iniciarEdicion(event, '{{ route('gestioncurso.update', $curso->id_curso) }}', {{ json_encode($curso) }})">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <button class="btn btn-danger btn-sm btn-action-grande" 
                                                    title="Eliminar Curso"
                                                    onclick="mostrarAlertaEliminar(event, '{{ $curso->nombre_curso }}', '{{ route('gestioncurso.destroy', $curso->id_curso) }}')">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr> 
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5" style="font-size: 1.2rem;">
                                            <i class="bi bi-folder-x-fill display-5 d-block mb-3 text-secondary"></i>
                                            No se encuentran cursos registrados en este periodo académico.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const defaultAction = "{{ route('gestioncurso.store') }}"; 

    function iniciarEdicion(event, updateUrl, data) {
        event.preventDefault();
        
        document.getElementById('id_cursoField').value = data.id_curso;
        document.getElementById('correo_persona').value = data.correo_persona;
        document.getElementById('nombre_curso').value = data.nombre_curso;
        document.getElementById('materia').value = data.materia;
        document.getElementById('fecha_inicio').value = data.fecha_inicio;
        document.getElementById('fecha_fin').value = data.fecha_fin;
        document.getElementById('horas_disponibles').value = data.horas_disponibles;
        document.getElementById('estado').value = data.estado;
        
        document.getElementById('registroForm').action = updateUrl;
        document.getElementById('methodField').value = 'PUT';
        
        document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil-square me-1"></i> Modificar Curso ID: ' + data.id_curso;
        document.getElementById('submitBtn').innerHTML = 'Guardar Cambios';
        document.getElementById('cancelarEdicionBtn').classList.remove('d-none');
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function cancelarEdicion() {
        document.getElementById('registroForm').reset();
        document.getElementById('registroForm').action = defaultAction;
        document.getElementById('methodField').value = 'POST';
        document.getElementById('formTitle').innerHTML = '<i class="bi bi-file-earmark-plus-fill me-1"></i> Registro de Curso';
        document.getElementById('submitBtn').innerHTML = 'Registrar Curso';
        document.getElementById('cancelarEdicionBtn').classList.add('d-none');
    }

    function mostrarAlertaEliminar(event, nombre, deleteUrl) {
        event.preventDefault();
        Swal.fire({
            title: '¿Confirmar eliminación permanente?',
            text: `Esta acción removerá definitivamente el curso "${nombre}" del catálogo institucional.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#8C001A',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const method = document.createElement('input');
                method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
                form.appendChild(method);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

@if(session('mensaje'))
    <script>
        Swal.fire({
            icon: '{{ session("sessionInsertado") == "true" || session("sessionEliminado") == "true" ? "success" : "info" }}',
            title: 'Se elimino correctamente el curso',
            text: '{{ session("mensaje") }}',
            confirmButtonColor: '#8C001A'
        });
    </script>
@endif

@endsection