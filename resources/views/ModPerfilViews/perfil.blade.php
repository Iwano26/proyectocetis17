<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | CETIS 17</title>
    
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
                <a class="navbar-brand fw-bold">
                    <img src="https://placehold.co/32x32/8C001A/ffffff?text=C17" alt="Logo" class="d-inline-block align-text-top rounded-full me-2">
                    Sistema de Asesorías
                </a>
                <div class="ms-auto">
                    <span class="fw-bold text-dark">
                        <i class="bi bi-person-circle text-cetis"></i> {{ Auth::user()->nombre }}
                    </span>
                </div>
            </div>
        </nav>

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    
                    <div class="card card-profile shadow-sm overflow-hidden">
                        <div class="profile-header-accent">
                            <i class="bi bi-person-badge display-4"></i>
                            <h2 class="h4 mt-2 fw-bold">Mi Perfil</h2>
                            <p class="mb-0 opacity-75 small">Información del Usuario</p>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            <div class="info-box">
                                <div class="info-label">Nombre Completo</div>
                                <div class="info-value">{{ $nombre }}</div>
                            </div>

                            <div class="info-box">
                                <div class="info-label">Correo Electrónico</div>
                                <div class="info-value">{{ $correo }}</div>
                            </div>

                            <div class="info-box border-bottom-0">
                                <div class="info-label">Teléfono de Contacto</div>
                                <div class="info-value">{{ $telefono }}</div>
                            </div>

                            <div class="text-center mt-4 d-grid gap-2">
                               <a href="{{ route('perfil.edit') }}" class="btn btn-outline-cetis fw-bold shadow-sm">
                                <i class="bi bi-gear-fill me-2"></i> Configurar Perfil
                               </a>
                                
                                <a href="{{ url('/principal') }}" class="btn btn-cetis-primary fw-bold shadow-sm">
                                    <i class="bi bi-arrow-left me-2"></i> Volver al Inicio
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div> <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 CETIS 17 | DGETI.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>