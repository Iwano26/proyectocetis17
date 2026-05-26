@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="form-container shadow p-4 bg-white mt-4" style="border-radius: 15px;">
                <div class="text-center mb-4">
                    <i class="bi bi-cloud-arrow-up-fill display-4" style="color: #8C001A;"></i>
                    <h2 class="fw-bold mt-2">Subir Documento</h2>
                    <p class="text-muted">Completa la información para agregar un recurso a la biblioteca digital.</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('biblioteca.guardar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    
                    <div class="mb-4">
                        <label for="nombre_doc" class="form-label fw-bold">Título del Archivo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                            <input type="text" name="nombre_doc" id="nombre_doc" class="form-control" 
                                   placeholder="Ej: Manual de Redes I" 
                                   value="{{ old('nombre_doc') }}" required>
                        </div>
                        <div class="form-text">Este es el nombre que verán los alumnos.</div>
                    </div>

                    {{-- Reemplaza el div de materia por este --}}
                    <div class="mb-4">
                        <label for="id_curso" class="form-label fw-bold">Curso Relacionado</label>
                        <select name="id_curso" id="id_curso" class="form-select" required>
                            <option value="" selected disabled>-- Selecciona un curso --</option>
                            @foreach($cursos as $curso)
                                <option value="{{ $curso->id_curso }}"
                                        data-materia="{{ $curso->materia }}"
                                        {{ old('id_curso') == $curso->id_curso ? 'selected' : '' }}>
                                    {{ $curso->nombre_curso }} — {{ $curso->materia }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Campo oculto que se llena automático con la materia del curso --}}
                    <input type="hidden" name="materia" id="materia_hidden" value="{{ old('materia') }}">

                    <div class="mb-4">
                        <label for="archivo_pdf" class="form-label fw-bold">Seleccionar Archivo (PDF)</label>
                        <input type="file" name="archivo_pdf" id="archivo_pdf" class="form-control" accept=".pdf" required>
                        <div class="form-text text-danger">
                            <i class="bi bi-info-circle"></i> Solo se permiten archivos en formato PDF (Máx. 10MB).
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-lg text-white" style="background-color: #8C001A;">
                            <i class="bi bi-check-circle me-2"></i>Guardar en Biblioteca
                        </button>
                        <a href="{{ route('biblioteca.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>

                </form>
            </div>

            <p class="text-center mt-4 text-muted small">
                © 2026 CETIS 17 | Al subir archivos asegúrate de que no infrinjan derechos de autor.
            </p>
        </div>
    </div>
</div>

<script>
    const selectCurso = document.getElementById('id_curso');

    selectCurso.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        document.getElementById('materia_hidden').value = selected.dataset.materia;
    });

    window.addEventListener('load', function() {
        if (selectCurso.value) {
            const selected = selectCurso.options[selectCurso.selectedIndex];
            document.getElementById('materia_hidden').value = selected.dataset.materia;
        }
    });
</script>
@endsection