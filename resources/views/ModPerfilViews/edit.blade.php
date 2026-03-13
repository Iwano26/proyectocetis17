<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Perfil | CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/menuiz.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
</head>
<body>  
    <x-sidebar />
    
    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold">Actualizar Mis Datos</a>
            </div>
        </nav>

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-7">
                    
                    <div class="card card-profile shadow-sm overflow-hidden">
                        <div class="profile-header-accent">
                            <i class="bi bi-person-gear display-4"></i>
                            <h2 class="h4 mt-2 fw-bold">Editar Perfil</h2>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            @if(session('mensaje'))
                                <div class="alert {{ session('sessionInsertado') == 'true' ? 'alert-success' : 'alert-danger' }} shadow-sm">
                                    {{ session('mensaje') }}
                                </div>
                            @endif

                            <form action="{{ route('perfil.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label class="info-label">Nombre(s)</label>
                                    <input type="text" name="nombre" class="form-control form-control-lg" 
                                           value="{{ old('nombre', $user->nombre) }}" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="info-label">Apellido Paterno</label>
                                        <input type="text" name="apellidoPa" class="form-control form-control-lg" 
                                               value="{{ old('apellidoPa', $user->apellidoPa) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="info-label">Apellido Materno</label>
                                        <input type="text" name="apellidoMa" class="form-control form-control-lg" 
                                               value="{{ old('apellidoMa', $user->apellidoMa) }}" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="info-label">Teléfono de Contacto</label>
                                    <input type="text" name="telefono" class="form-control form-control-lg" 
                                           value="{{ old('telefono', $user->telefono) }}" maxlength="10" required>
                                    <small class="text-muted">Introduce los 10 dígitos sin espacios.</small>
                                </div>

                                <div class="text-center mt-5 d-grid gap-2">
                                    <button type="submit" class="btn btn-cetis-primary fw-bold shadow-sm py-3">
                                        <i class="bi bi-save2-fill me-2"></i> Actualizar Perfil
                                    </button>
                                    <a href="{{ route('perfil.index') }}" class="btn btn-light text-muted fw-bold">
                                        <i class="bi bi-x-circle me-1"></i> Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4">
        <div class="container text-center"><p class="mb-0">&copy; 2026 CETIS 17 | DGETI.</p></div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>