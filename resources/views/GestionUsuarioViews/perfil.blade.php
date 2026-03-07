<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil | CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --color-guinda: #8C2329;    /* Color principal DGETI */
            --color-guinda-hover: #731c21;
            --color-guinda-focus: rgba(140, 35, 41, 0.25);
            --color-dorado: #C4A77D;      /* Color de acento del logo */
            --color-bg: #f4f4f4;
        }

        body { 
            background-color: var(--color-bg);
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border: none;
            border-top: 5px solid var(--color-guinda);
        }

        .form-label {
            color: var(--color-dorado);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
        }

        .form-control:focus {
            border-color: var(--color-guinda);
            box-shadow: 0 0 0 0.25rem var(--color-guinda-focus);
        }

        .btn-save {
            background-color: var(--color-guinda);
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-save:hover {
            background-color: var(--color-guinda-hover);
            color: white;
            transform: translateY(-1px);
        }

        .section-title {
            color: var(--color-guinda);
            font-weight: 800;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow p-4">
                <h4 class="section-title text-center">
                    <i class="bi bi-person-check-fill me-2"></i>Actualizar Información
                </h4>
                
                <form>
                    <div class="mb-4">
                        <label for="fullName" class="form-label">Nombre Completo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" id="fullName" class="form-control border-start-0" placeholder="Ej. Juan Pérez García" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" id="email" class="form-control border-start-0" placeholder="usuario@cetis17.edu.mx" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="form-label">Teléfono de Contacto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                            <input type="tel" id="phone" class="form-control border-start-0" placeholder="248 000 0000">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-save">
                            Guardar Cambios
                        </button>
                        <button type="button" class="btn btn-link text-muted text-decoration-none">
                            Cancelar y Volver
                        </button>
                    </div>
                </form>
            </div>
            <p class="text-center mt-4 text-muted" style="font-size: 0.8rem;">
                © 2026 CETIS 17 - Dirección General de Educación Tecnológica Industrial
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>