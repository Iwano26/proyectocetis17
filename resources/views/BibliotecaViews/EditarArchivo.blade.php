<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Documento | CETIS 17</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root { --cetis-rojo: #8C001A; }
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .form-container {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
        }
        .btn-cetis {
            background-color: var(--cetis-rojo);
            color: white;
            font-weight: bold;
        }
        .btn-cetis:hover {
            background-color: #6a0014;
            color: white;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('biblioteca.index') }}" style="color: var(--cetis-rojo);">
                <i class="bi bi-arrow-left-circle me-2"></i> Volver a Biblioteca
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="form-container shadow">
                    <div class="text-center mb-4">
                        <i class="bi bi-pencil-square display-4" style="color: var(--cetis-rojo);"></i>
                        <h2 class="fw-bold mt-2">Editar Documento</h2>
                        <p class="text-muted">Modifica la información del recurso digital.</p>
                    </div>

                    {{-- Formulario apuntando a la ruta con id_biblioteca --}}
                    <form action="{{ route('biblioteca.actualizar', $archivo->id_biblioteca) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="nombre_doc" class="form-label fw-bold">Título del Archivo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <input type="text" name="nombre_doc" id="nombre_doc" class="form-control" 
                                       value="{{ $archivo->nombre_doc }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="id_curso" class="form-label fw-bold">Materia Relacionada</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-book"></i></span>
                                <select name="id_curso" id="id_curso" class="form-select" required>
                                    @foreach($materias as $m)
                                        <option value="{{ $m->id_curso }}" {{ $archivo->id_curso == $m->id_curso ? 'selected' : '' }}>
                                            {{ $m->materia }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-warning small border-0 shadow-sm">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Solo puedes editar el nombre y la materia. Para cambiar el PDF, debes eliminarlo y subirlo nuevamente.
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-cetis btn-lg">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>