@extends('layouts.app') {{-- O el nombre exacto de tu archivo layout base --}}

@section('content')
    <div class="container-fluid mt-4 px-4">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0 text-title-custom">Biblioteca virtual</h2>
            <div>
                {{-- Botón dinámico de Mis Archivos / Ver Todos --}}
                @if(request('mis_archivos') == 1)
                    <a href="{{ route('biblioteca.index') }}" class="btn btn-dark fw-bold me-2 btn-custom-action">
                        <i class="bi bi-files me-1"></i> VER TODOS LOS ARCHIVOS
                    </a>
                @else
                    <a href="{{ route('biblioteca.index', ['mis_archivos' => 1]) }}" class="btn btn-outline-danger fw-bold me-2 btn-custom-action">
                        MIS ARCHIVOS
                    </a>
                @endif

                {{-- RESTRICCIÓN DE ROL: Solo Asesor y Administrador ven este botón --}}
                @if(Auth::user()->rol === 'Asesor' || Auth::user()->rol === 'Administrador')
                    <a href="{{ route('biblioteca.create') }}" class="btn btn-danger fw-bold btn-custom-danger">
                        <i class="bi bi-cloud-upload me-1"></i> SUBIR ARCHIVO
                    </a>
                @endif
            </div>
        </div>

        <form action="{{ route('biblioteca.index') }}" method="GET">
            {{-- Mantenemos el estado de "Mis Archivos" si estaba activo al filtrar --}}
            @if(request('mis_archivos'))
                <input type="hidden" name="mis_archivos" value="1">
            @endif

            <div class="mb-3">
                <div class="input-group shadow-sm">
                    <input type="text" name="buscar" class="form-control form-control-lg custom-input" 
                           placeholder="Buscar por nombre de documento..." 
                           value="{{ request('buscar') }}">
                    <button class="btn btn-danger px-4 btn-custom-danger" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <div class="seccion-filtros shadow-sm bg-white border p-3 rounded-3 mb-4">
                <div class="row align-items-end">
                    {{-- Ordenar por Fecha --}}
                    <div class="col-md-4 mb-2 mb-md-0">
                        <label class="form-label small fw-bold text-muted">Filtrar por Fecha:</label>
                        <select name="orden" class="form-select custom-select">
                            <option value="reciente" {{ request('orden') == 'reciente' ? 'selected' : '' }}>Más recientes</option>
                            <option value="antiguo" {{ request('orden') == 'antiguo' ? 'selected' : '' }}>Más antiguos</option>
                        </select>
                    </div>

                    {{-- Filtrar por Cursos Existentes --}}
                    <div class="col-md-4 mb-2 mb-md-0">
                        <label class="form-label small fw-bold text-muted">Materia:</label>
                        <select name="materia" class="form-select">
                            <option value="">-- Todas las materias --</option>
                            @foreach($materiasDisponibles as $mat)
                                <option value="{{ $mat }}" {{ request('materia') == $mat ? 'selected' : '' }}>
                                    {{ $mat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark w-100 fw-bold btn-filter-apply">APLICAR FILTROS</button>
                            <a href="{{ route('biblioteca.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center px-3">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="d-flex flex-column gap-3 mb-5">
            @forelse($archivos as $archivo)
                <div class="tarjeta-archivo shadow-sm border bg-white p-3">
                    <div class="row align-items-center">
                        
                        <div class="col-lg-7 d-flex align-items-center">
                            <div class="icono-archivo shadow-sm d-flex align-items-center justify-content-center me-3">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-1 fw-bold item-title">{{ $archivo->nombre_doc }}</h4>
                                <div class="d-flex flex-wrap gap-3 text-muted small">
                                    <span><i class="bi bi-tag-fill text-danger me-1"></i>Materia: <b>{{ $archivo->materia }}</b></span>
                                    <span><i class="bi bi-calendar3 me-1"></i>Subido el: <b>{{ $archivo->created_at->format('d/m/Y') }}</b></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5 text-lg-end mt-3 mt-lg-0 d-flex justify-content-lg-end gap-2 flex-wrap">
                            <a href="{{ asset('documentos/' . $archivo->ruta_archivo) }}" target="_blank" class="btn btn-info btn-action btn-ver text-white fw-bold">
                                <i class="bi bi-eye"></i> VER
                            </a>
                            <a href="{{ asset('documentos/' . $archivo->ruta_archivo) }}" download="{{ $archivo->nombre_doc }}" class="btn btn-primary btn-action btn-entrar text-white fw-bold">
                                <i class="bi bi-download"></i> DESCARGAR
                            </a>

                            {{-- RESTRICCIÓN PROFESIONAL DE ACCIONES --}}
                            @if(Auth::user()->rol === 'Administrador' || (Auth::user()->rol === 'Asesor' && $archivo->correo_usuario === Auth::user()->correo))
                                {{-- Botón Editar --}}
                                <a href="{{ route('biblioteca.edit', $archivo->id_biblioteca) }}" class="btn btn-warning btn-action text-white px-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                
                                {{-- Botón Eliminar --}}
                                {{-- Formulario con la clase "form-eliminar" para que JavaScript lo cache --}}
                                <form action="{{ route('biblioteca.eliminar', $archivo->id_biblioteca) }}" method="POST" class="d-inline m-0 form-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-action px-2">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white border rounded-3 shadow-sm">
                    <i class="bi bi-archive text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2 mb-0">No hay archivos registrados en la biblioteca.</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Capturamos todos los formularios de eliminación
    const formularios = document.querySelectorAll('.form-eliminar');

    formularios.forEach(formulario => {
        formulario.addEventListener('submit', function (e) {
            // Detenemos el envío automático del formulario
            e.preventDefault();

            // Disparamos la alerta con textos institucionales
            Swal.fire({
                title: '¿Está seguro de eliminar este archivo?',
                text: "Esta acción no se puede deshacer y el documento se borrará permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545', // Color rojo danger
                cancelButtonColor: '#6c757d',  // Color gris secondary
                confirmButtonText: '<i class="bi bi-trash"></i> Confirmar eliminación',
                cancelButtonText: 'Cancelar',
                reverseButtons: true, // Ubica cancelar a la izquierda
                customClass: {
                    popup: 'rounded-3 shadow',
                    confirmButton: 'fw-bold px-3 btn-lg',
                    cancelButton: 'fw-bold px-3 btn-lg'
                }
            }).then((result) => {
                // Si el usuario confirma la acción, se procesa el formulario
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>