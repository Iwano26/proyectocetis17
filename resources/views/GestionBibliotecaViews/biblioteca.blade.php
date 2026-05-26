@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/menuiz.css') }}">
<link rel="stylesheet" href="{{ asset('css/home.css') }}">

<style>
    /* ========== OPTIMIZACIÓN DE ACCESIBILIDAD Y FUENTES ========== */
    .section-biblioteca { padding: 40px 0 80px; font-size: 1.1rem; }
    .card-gestion { background: #ffffff; border: 1px solid rgba(140, 0, 26, 0.12); border-radius: 16px; box-shadow: 0 6px 25px rgba(0, 0, 0, 0.05); overflow: hidden; margin-bottom: 2rem; }
    .card-header-cetis { background-color: var(--cetis-rojo, #8C001A) !important; color: white !important; font-family: 'Georgia', serif; font-weight: bold; font-size: 1.35rem; padding: 18px 24px; }
    .form-label { font-weight: 700; font-size: 1.1rem; color: #1a1a2e; margin-bottom: 8px; display: block; }
    .form-control, .form-select { border-radius: 8px; border: 1.5px solid rgba(140, 0, 26, 0.25); padding: 0.8rem 1rem; font-size: 1.1rem; color: #1a1a2e; }
    .form-control:focus, .form-select:focus { border-color: #b0001f; box-shadow: 0 0 0 0.25rem rgba(140, 0, 26, 0.15); }
    
    /* ========== DISEÑO DE TABLA PREMIUM ========== */
    .table-biblioteca th { font-family: 'Inter', sans-serif; font-weight: 700; font-size: 1.05rem; color: #2c3e50; padding: 16px 18px; background-color: #f8f9fa; border-bottom: 3px solid rgba(140, 0, 26, 0.15); }
    .table-biblioteca td { padding: 18px 18px; font-size: 1.05rem; color: #1a1a2e; }
    .documento-title { font-family: 'Georgia', serif; font-weight: bold; color: #8C001A; font-size: 1.25rem; }
    .text-muted-grande { color: #4a5568 !important; font-size: 0.95rem; font-weight: 500; }
    .btn-action-grande { padding: 10px 14px; border-radius: 8px; font-size: 1.05rem; display: inline-flex; align-items: center; justify-content: center; }
    .btn-submit-grande { font-size: 1.15rem; font-weight: 700; padding: 12px 28px; border-radius: 8px; }
    .alerta-bloqueo-archivo { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px 14px; border-radius: 8px; font-size: 0.95rem; font-weight: 600; margin-top: 8px; }
</style>

<section class="section-biblioteca">
    <div class="container-fluid px-4">
        
        <div class="text-center mb-5">
            <h2 class="section-titulo" style="font-size: 2.3rem;">
                <i class="bi bi-folder-symlink-fill me-2" style="color: #8C001A;"></i>Repositorio de Biblioteca
            </h2>
            <p class="section-subtitulo" style="font-size: 1.2rem; color: #4a5568;">Sube, distribuye y modifica el acervo documental de la institución</p>
        </div>

        <div class="row">
            {{-- Formulario Lateral --}}
            <div class="col-12 col-lg-4 mb-4">
                <div class="card card-gestion">
                    <div class="card-header card-header-cetis">
                        <span id="formTitle"><i class="bi bi-file-earmark-plus-fill me-1"></i> Subir Documento</span>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('gestionbiblioteca.store') }}" id="registroForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf 
                            <input type="hidden" name="_method" value="POST" id="methodField">

                            <div class="mb-3">
                                <label for="nombre_doc" class="form-label">Nombre del Documento</label>
                                <input type="text" name="nombre_doc" id="nombre_doc" placeholder="Ej: Manual de Redes Cisco v3" class="form-control" value="{{ old('nombre_doc') }}" required>
                            </div>
                            
                            {{-- SELECCIÓN DE MATERIA DINÁMICA DE CURSOS --}}
                            {{-- Select de CURSO --}}
                            <div class="mb-3">
                                <label for="id_curso" class="form-label">Curso Relacionado</label>
                                <select name="id_curso" id="id_curso" class="form-select" required>
                                    <option value="" disabled selected>-- Selecciona un curso --</option>
                                    @foreach($cursosDisponibles as $curso)
                                        <option value="{{ $curso->id_curso }}" data-materia="{{ $curso->materia }}">
                                            {{ $curso->nombre_curso }} — {{ $curso->materia }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Materia se llena automático --}}
                            <input type="hidden" name="materia" id="materia_hidden">

                            <div class="mb-3">
                                <label id="archivoInputLabel" for="ruta_archivo" class="form-label">Adjuntar Archivo Digital</label>
                                <input type="file" name="ruta_archivo" id="ruta_archivo" class="form-control" required>
                                
                                <div id="avisoBloqueoArchivo" class="alerta-bloqueo-archivo d-none">
                                    <i class="bi bi-exclamation-octagon-fill me-1"></i> El archivo original no puede ser cambiado en modo edición.
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-light d-none" id="cancelarEdicionBtn" onclick="cancelarEdicion()" style="border-radius: 8px; font-weight:700; font-size:1.1rem; padding: 12px 20px;">Cancelar</button>
                                <button type="submit" class="btn btn-cetis-primary btn-submit-grande px-4" id="submitBtn">Publicar Archivo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            {{-- Tabla Espaciosa (Ancho 8) --}}
            <div class="col-12 col-lg-8">
                <div class="card card-gestion">
                    <div class="card-header card-header-cetis">
                        <i class="bi bi-collection-fill me-1"></i> Documentación Resguardada
                    </div>                  
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-biblioteca align-middle m-0">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;" class="text-center">ID</th>
                                        <th>Detalles del Recurso</th>
                                        <th>Materia Asociada</th>
                                        <th class="text-center" style="width: 140px;">Acciones</th>                                        
                                    </tr>
                                </thead>
                                <tbody> 
                                    @forelse ($archivos as $archivo) 
                                    <tr>
                                        <td class="text-center text-muted fw-bold">{{ $archivo->id_biblioteca }}</td>
                                        <td>
                                            <div class="documento-title">{{ $archivo->nombre_doc }}</div>
                                            
                                            <div class="text-muted-grande mt-2 d-flex flex-column gap-1">
                                                <div>
                                                    <i class="bi bi-person-badge-fill me-1" style="color: #8C001A;"></i>
                                                    <strong>Subido por:</strong> 
                                                    <span class="text-dark fw-bold" style="font-size: 1.05rem;">
                                                        @if($archivo->usuario)
                                                            {{ $archivo->usuario->nombre }} 
                                                            {{ $archivo->usuario->apellido_paterno ?? '' }} 
                                                            {{ $archivo->usuario->apellido_materno ?? '' }}
                                                        @else
                                                            {{ $archivo->autor ?? 'Personal Autorizado' }}
                                                        @endif
                                                    </span>
                                                </div>
                                                
                                                @if($archivo->usuario || $archivo->correo_usuario)
                                                    <div style="padding-left: 22px; font-size: 0.95rem; color: #4a5568; font-weight: 500;">
                                                        <i class="bi bi-envelope-fill me-1" style="color: #6c757d;"></i>{{ $archivo->usuario->correo ?? $archivo->correo_usuario }}
                                                    </div>
                                                @endif
                                            </div>

                                            <a href="{{ asset('documentos/' . $archivo->ruta_archivo) }}" target="_blank" class="btn btn-link p-0 mt-2 fw-bold text-decoration-none" style="font-size:0.95rem; color:#004A77;">
                                                <i class="bi bi-cloud-arrow-down-fill me-1"></i>Descargar / Visualizar Recurso
                                            </a>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $archivo->materia }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-warning btn-sm btn-action-grande text-white" 
                                                    title="Editar Datos"
                                                    onclick="iniciarEdicion(event, '{{ route('gestionbiblioteca.update', $archivo->id_biblioteca) }}', {{ json_encode($archivo) }})">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <button class="btn btn-danger btn-sm btn-action-grande" 
                                                    title="Eliminar Documento"
                                                    onclick="mostrarAlertaEliminar(event, '{{ $archivo->nombre_doc }}', '{{ route('gestionbiblioteca.destroy', $archivo->id_biblioteca) }}')">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr> 
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5" style="font-size: 1.2rem;">
                                            <i class="bi bi-folder-x-fill display-5 d-block mb-3 text-secondary"></i>
                                            No se registran documentos en el acervo digital.
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const defaultAction = "{{ route('gestionbiblioteca.store') }}"; 

    function iniciarEdicion(event, updateUrl, data) {
        event.preventDefault();
        
        document.getElementById('nombre_doc').value = data.nombre_doc;
        
        // Asignar el valor de la materia al select
        document.getElementById('materia').value = data.materia;
        
        // Bloquear input del archivo al editar
        const archivoInput = document.getElementById('ruta_archivo');
        archivoInput.required = false;
        archivoInput.disabled = true; 
        
        document.getElementById('avisoBloqueoArchivo').classList.remove('d-none');
        document.getElementById('archivoInputLabel').innerHTML = "Archivo Digital (Bloqueado)";

        document.getElementById('registroForm').action = updateUrl;
        document.getElementById('methodField').value = 'PUT';
        
        document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil-square me-1"></i> Modificar Información';
        document.getElementById('submitBtn').innerHTML = 'Guardar Cambios';
        document.getElementById('cancelarEdicionBtn').classList.remove('d-none');
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function cancelarEdicion() {
        document.getElementById('registroForm').reset();
        document.getElementById('registroForm').action = defaultAction;
        document.getElementById('methodField').value = 'POST';
        
        const archivoInput = document.getElementById('ruta_archivo');
        archivoInput.required = true;
        archivoInput.disabled = false;
        
        document.getElementById('avisoBloqueoArchivo').classList.add('d-none');
        document.getElementById('archivoInputLabel').innerHTML = "Adjuntar Archivo Digital";

        document.getElementById('formTitle').innerHTML = '<i class="bi bi-file-earmark-plus-fill me-1"></i> Subir Documento';
        document.getElementById('submitBtn').innerHTML = 'Publicar Archivo';
        document.getElementById('cancelarEdicionBtn').classList.add('d-none');
    }

    function mostrarAlertaEliminar(event, nombre, deleteUrl) {
        event.preventDefault();
        Swal.fire({
            title: '¿Eliminar del acervo público?',
            text: `Esta acción borrará definitivamente el archivo "${nombre}" del servidor institucional.`,
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

    // Llenar materia automático al elegir curso
    const selectCursoAdmin = document.getElementById('id_curso');
    if (selectCursoAdmin) {
        selectCursoAdmin.addEventListener('change', function() {
            document.getElementById('materia_hidden').value = 
                this.options[this.selectedIndex].dataset.materia;
        });
    }
</script>

@if(session('mensaje'))
    <script>
        Swal.fire({
            icon: '{{ session("sessionInsertado") == "true" || session("sessionEliminado") == "true" ? "success" : "info" }}',
            title: 'Control de Biblioteca',
            text: '{{ session("mensaje") }}',
            confirmButtonColor: '#8C001A'
        });
    </script>
@endif

@endsection