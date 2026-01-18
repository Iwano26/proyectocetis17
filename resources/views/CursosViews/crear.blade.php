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
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Nombre del Curso:</label>
                                <input type="text" name="nombre_curso" class="form-control"  placeholder="Ej: Bases de Datos I" value="{{ old('nombre_curso') }}" required maxlength="60">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Materia:</label>
                                <input type="text" name="materia" class="form-control" placeholder="Ej: Programacion" value="{{ old('materia') }}" required maxlength="50">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small">Descripción:</label>
                                <textarea name="descripcion" class="form-control" rows="2" placeholder="Pequeña descripcion del curso" maxlength="50">{{ old('descripcion') }}</textarea>
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

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ route('cursos.index') }}" class="btn btn-light me-2 fw-bold">CANCELAR</a>
                            <button type="submit" class="btn btn-danger px-4 fw-bold shadow">PUBLICAR CURSO</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>