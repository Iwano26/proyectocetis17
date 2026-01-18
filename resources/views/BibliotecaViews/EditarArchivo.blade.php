<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Documento | CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root { 
            --cetis-rojo: #8C001A; 
        }
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }

        .form-container {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            border-left: 5px solid #ffc107; /* Color amarillo para indicar edición */
        }

        .btn-update {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('biblioteca.index') }}" style="color: var(--cetis-rojo);">
                <i class="bi bi-arrow-left-circle me-2"></i> Cancelar y Volver
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="form-container shadow">
                    <div class="text-center mb-4">
                        <i class="bi bi-pencil-square display-4 text-warning"></i>
                        <h2 class="fw-bold mt-2">Editar Información</h2>
                        <p class="text-muted">Modifica los detalles del documento seleccionado.</p>
                    </div>

                    <form action="{{ route('biblioteca.actualizar', $archivo->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- IMPORTANTE: Indica que es una actualización --}}

                        <div class="mb-4">
                            <label for="nombre_doc" class="form-label fw-bold">Título del Archivo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <input type="text" name="nombre_doc" id="nombre_doc" class="form-control" 
                                       value="{{ $archivo->nombre_doc }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="materia" class="form-label fw-bold">Materia Relacionada</label>
                            <select name="materia" id="materia" class="form-select" required>
                                <option value="Programación" {{ $archivo->materia == 'Programación' ? 'selected' : '' }}>Programación</option>
                                <option value="Soporte Técnico" {{ $archivo->materia == 'Soporte Técnico' ? 'selected' : '' }}>Soporte Técnico</option>
                                <option value="Ofimática" {{ $archivo->materia == 'Ofimática' ? 'selected' : '' }}>Ofimática</option>
                                <option value="Base de Datos" {{ $archivo->materia == 'Base de Datos' ? 'selected' : '' }}>Base de Datos</option>
                            </select>
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-2"></i>
                            El archivo físico (PDF) no se puede cambiar desde aquí para mantener la integridad. Si deseas cambiar el archivo, por favor elimínalo y sube uno nuevo.
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-update btn-lg shadow-sm">
                                <i class="bi bi-save me-2"></i>Guardar Cambios
                            </button>
                            <a href="{{ route('biblioteca.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>