@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="form-container shadow p-4 bg-white mt-4" style="border-radius: 15px;">
                <div class="text-center mb-4">
                    <i class="bi bi-pencil-square display-4" style="color: #8C001A;"></i>
                    <h2 class="fw-bold mt-2">Editar Documento</h2>
                    <p class="text-muted">Modifica la información del recurso digital.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('biblioteca.actualizar', $archivo->id_biblioteca) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="nombre_doc" class="form-label fw-bold">Título del Archivo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                            <input type="text" name="nombre_doc" id="nombre_doc" class="form-control" 
                                   value="{{ old('nombre_doc', $archivo->nombre_doc) }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="id_curso" class="form-label fw-bold">Curso Relacionado</label>
                        <select name="id_curso" id="id_curso" class="form-select" required>
                            <option value="" disabled>-- Selecciona un curso --</option>
                            @foreach($cursos as $curso)
                                <option value="{{ $curso->id_curso }}"
                                        data-materia="{{ $curso->materia }}"
                                        {{ old('id_curso', $archivo->id_curso) == $curso->id_curso ? 'selected' : '' }}>
                                    {{ $curso->nombre_curso }} — {{ $curso->materia }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="materia" id="materia_hidden" value="{{ old('materia', $archivo->materia) }}">

                    <div class="alert alert-warning small border-0 shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Solo puedes editar el nombre y el curso. Para cambiar el archivo físico, elimínalo y sube uno nuevo.
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-lg text-white" style="background-color: #8C001A;">
                            <i class="bi bi-save me-2"></i>Guardar Cambios
                        </button>
                        <a href="{{ route('biblioteca.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>

            <p class="text-center mt-4 text-muted small">
                © 2026 CETIS 17 | Sistema de Asesorías
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