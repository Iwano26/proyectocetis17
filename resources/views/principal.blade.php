<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asesorías | CETIS 17</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/menuiz.css') }}">
</head>
<body>  
    
    <x-sidebar />
    
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold">
                <img src="https://placehold.co/32x32/8C001A/ffffff?text=C17" alt="Logo" class="d-inline-block align-text-top rounded-full me-2">
                Sistema de Asesorías
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-3">
                        <span class="nav-link fw-bold text-dark">
                            <i class="bi bi-person-check-fill text-success"></i> Hola, {{ Auth::user()->nombre }}
                        </span>
                    </li>
                    @if(Auth::user()->rol === 'Administrador')
                        <li class="nav-item"><a class="nav-link" href="/gestioncurso">Cursos</a></li>
                        <li class="nav-item"><a class="nav-link" href="/gestionusuario">Usuarios</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-3 fw-bolder mb-3">¡Bienvenido, {{ Auth::user()->nombre }}!</h1>
            <p class="lead mb-5 opacity-75">Plataforma de Refuerzo Académico CETIS 17</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="/buscarcurso" class="btn btn-cetis-primary btn-lg fw-bold shadow-lg">Solicitar Asesoría</a>
                <a href="/biblioteca" class="btn btn-outline-light btn-lg fw-bold shadow-lg">Ver Biblioteca</a>
            </div>
        </div>
    </header>

   <section id="funcionalidades" class="py-5 mt-4">
        <div class="container">
            <h2 class="text-center mb-5 display-5 fw-bold text-dark">Tu Camino al Éxito Educativo</h2>
            <div class="row g-4 justify-content-center">
                
                <div class="col-md-6 col-lg-4">
                    <a href="/agenda" class="text-decoration-none h-100">
                        <div class="card p-4 h-100 shadow-sm">
                            <div class="text-center">
                                <i class="feature-icon bi bi-calendar-event"></i>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold text-cetis">Agenda</h5>
                                <p class="card-text text-muted">
                                    Revisa tus próximas asesorías, fechas importantes y organiza tu tiempo de estudio.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4">
                    <a href="/buscarcurso" class="text-decoration-none h-100">
                        <div class="card p-4 h-100 shadow-sm">
                            <div class="text-center">
                                <i class="feature-icon bi bi-journal-bookmark"></i>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold text-cetis">Cursos</h5>
                                <p class="card-text text-muted">
                                    Explora el material didáctico y las asesorías disponibles para tus materias.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4">
                    <a href="/biblioteca" class="text-decoration-none h-100">
                        <div class="card p-4 h-100 shadow-sm">
                            <div class="text-center">
                                <i class="feature-icon bi bi-archive"></i>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold text-cetis">Biblioteca Digital</h5>
                                <p class="card-text text-muted">
                                    Acceso rápido a libros, guías de estudio y documentos relevantes en formato digital.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 CETIS 17 | DGETI.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>