<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivo | CETIS 17</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/subir_archivo.css') }}">
</head>
<body>

    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('biblioteca.index') }}" style="color: #8C001A;">
                <i class="bi bi-arrow-left-circle me-2"></i> Volver a Biblioteca
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="form-container shadow p-4 bg-white" style="border-radius: 15px;">
                    <div class="text-center mb-4">
                        <i class="bi bi-cloud-arrow-up-fill display-4" style="color: #8C001A;"></i>
                        <h2 class="fw-bold mt-2">Subir Documento</h2>
                        <p class="text-muted">Completa la información para agregar un recurso a la biblioteca digital.</p>
                    </div>

                    {{-- Formulario funcional --}}
                    <form action="{{ route('biblioteca.guardar') }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        
                        <div class="mb-4">
                            <label for="nombre_doc" class="form-label fw-bold">Título del Archivo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <input type="text" name="nombre_doc" id="nombre_doc" class="form-control" placeholder="Ej: Manual de Redes I" required>
                            </div>
                            <div class="form-text">Este es el nombre que verán los alumnos.</div>
                        </div>

                        {{-- SELECCIÓN DE MATERIA REPARADA (SIN DUPLICADOS) --}}
                        <div class="mb-4">
                            <label for="materia" class="form-label fw-bold">Materia Relacionada</label>
                            <select name="materia" id="materia" class="form-select" required>
                                <option value="" selected disabled>-- Selecciona una materia --</option>
                                {{-- Bucle dinámico basado en materias únicas --}}
                                @foreach($materias as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>