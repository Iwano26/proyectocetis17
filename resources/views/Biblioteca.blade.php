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
                <button class="btn btn-outline-danger fw-bold me-2 btn-custom-action">MIS ARCHIVOS</button>
                <a href="{{ route('biblioteca.create') }}" class="btn btn-danger fw-bold btn-custom-danger">
                    <i class="bi bi-cloud-upload me-1"></i> SUBIR ARCHIVO
                </a>
            </div>
        </div>

        <form action="{{ route('biblioteca.index') }}" method="GET">
            <div class="mb-3">
                <div class="input-group shadow-sm">
                    <input type="text" name="buscar" class="form-control form-control-lg custom-input" 
                           placeholder="Buscar documentos..." 
                           value="{{ request('buscar') }}">
                    <button class="btn btn-danger px-4 btn-custom-danger" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <div class="seccion-filtros shadow-sm bg-white border p-3 rounded-3 mb-4">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <label class="form-label small fw-bold text-muted">Filtrar por Fecha:</label>
                        <select name="orden" class="form-select custom-select">
                            <option value="reciente" {{ request('orden') == 'reciente' ? 'selected' : '' }}>Más recientes</option>
                            <option value="antiguo" {{ request('orden') == 'antiguo' ? 'selected' : '' }}>Más antiguos</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-2 mb-md-0">
                        <label class="form-label small fw-bold text-muted">Materia Relacionada:</label>
                        <select name="materia" class="form-select custom-select">
                            <option value="">-- Todas las Materias --</option>
                            <option value="Programación" {{ request('materia') == 'Programación' ? 'selected' : '' }}>Programación</option>
                            <option value="Soporte Técnico" {{ request('materia') == 'Soporte Técnico' ? 'selected' : '' }}>Soporte Técnico</option>
                            <option value="Ofimática" {{ request('materia') == 'Ofimática' ? 'selected' : '' }}>Ofimática</option>
                            <option value="Base de Datos" {{ request('materia') == 'Base de Datos' ? 'selected' : '' }}>Base de Datos</option>
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

                            @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Asesor')
                                <a href="{{ route('biblioteca.edit', $archivo->id_biblioteca) }}" class="btn btn-warning btn-action text-white px-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('biblioteca.eliminar', $archivo->id_biblioteca) }}" method="POST" class="d-inline m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-action px-2" onclick="return confirm('¿Estás seguro?')">
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