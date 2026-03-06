<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil | CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Definición de la paleta de colores de CETIS 17 */
        :root {
            --color-guinda: #8C2329;    /* Marrón rojizo principal del logo */
            --color-guinda-hover: #731c21; /* Versión más oscura para hover de botones */
            --color-guinda-focus: rgba(140, 35, 41, 0.25); /* Color de foco para inputs */
            --color-dorado: #C4A77D;      /* Dorado claro para labels de campos */
            --color-bg: #F8F9FA;          /* Fondo gris muy claro */
            --color-card: #FFFFFF;        /* Fondo de la tarjeta */
        }

        body { background-color: var(--color-bg); }

        /* Estilos personalizados para inputs y labels */
        .form-label {
            color: var(--color-dorado) !important;
            font-weight: bold;
        }

        .form-control:focus, .input-group-text {
            border-color: var(--color-guinda);
            box-shadow: 0 0 0 0.25rem var(--color-guinda-focus);
        }

        .input-group-text {
            background-color: var(--color-guinda);
            color: #ffffff;
            border-color: var(--color-guinda);
        }

        /* Estilos personalizados para botones */
        .btn-custom {
            background-color: var(--color-guinda);
            color: white;
            border-color: var(--color-guinda);
        }
        
        .btn-custom:hover, .btn-custom:focus {
            background-color: var(--color-guinda-hover);
            color: white;
            border-color: var(--color-guinda-hover);
        }

        /* Estilos específicos para la sección de avatar */
        .avatar-upload {
            position: relative;
            max-width: 120px;
            margin: 0 auto 20px;
        }
        .avatar-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
            object-fit: cover;
        }

        /* Ajuste de color para el título del panel */
        .card-header .bi-person-gear {
            color: var(--color-guinda);
        }

        /* Ajuste de color para títulos de sección secundarios */
        h6.text-guinda {
            color: var(--color-guinda) !important;
            font-weight: bold;
        }

    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-gear me-2"></i>Edición de Perfil</h5>
                </div>
                <div class="card-body p-4">
                    <form>
                        <div class="text-center mb-4">
                            <div class="avatar-upload">
                                <img id="imgPreview" src="https://ui-avatars.com/api/?name=CETIS+17&background=8C2329&color=fff&size=128" class="avatar-preview" alt="Avatar">
                            </div>
                            <label for="fileUpload" class="btn btn-outline-guinda btn-sm">
                                <i class="bi bi-camera me-1"></i> Actualizar Foto
                            </label>
                            <input type="file" id="fileUpload" class="d-none" accept="image/*">
                        </div>

                        <hr class="my-4 text-muted">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" value="">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Matrícula o Nombre de usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Correo electrónico institucional</label>
                                <input type="email" class="form-control" value="">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Biografía o Información adicional</label>
                                <textarea class="form-control" rows="3" placeholder="Comparte algo sobre ti..."></textarea>
                            </div>
                        </div>

                        <h6 class="mt-4 mb-3 text-guinda">Actualización de Contraseña</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Contraseña actual</label>
                                <input type="password" class="form-control" placeholder="••••••••">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nueva contraseña</label>
                                <input type="password" class="form-control" placeholder="••••••••">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <button type="button" class="btn btn-light px-4">Cancelar</button>
                            <button type="submit" class="btn btn-custom px-4">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const fileInput = document.getElementById('fileUpload');
    const imgPreview = document.getElementById('imgPreview');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
</script>

</body>
</html>