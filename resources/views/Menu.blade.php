<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asesorías | CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>
        :root {
            --cetis-primary: #8C001A;
            --cetis-secondary: #004A77;
            --cetis-light: #f8f9fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cetis-light);
        }
        .navbar-brand, .text-cetis {
            color: var(--cetis-primary) !important;
        }
        .hero-section {
            background: linear-gradient(135deg, var(--cetis-primary), #A61E34);
            color: white;
            padding: 100px 0;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        .card {
            border-radius: 15px;
            border-left: 5px solid var(--cetis-primary);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .feature-icon {
            font-size: 2.5rem;
            color: var(--cetis-primary);
            margin-bottom: 15px;
        }
        .btn-cetis-primary {
            background-color: var(--cetis-primary);
            border-color: var(--cetis-primary);
            color: white;
            border-radius: 50rem;
            padding: 0.75rem 1.5rem;
            transition: background-color 0.3s;
        }
        .btn-cetis-primary:hover {
            background-color: #A61E34;
            color: white;
        }
        #menu-toggle-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1040;
            font-size: 1.5rem;
            color: var(--cetis-primary);
            background-color: white;
            border: 2px solid var(--cetis-primary);
            padding: 5px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        #menu-toggle-btn:hover {
            background-color: var(--cetis-primary);
            color: white;
        }
        .offcanvas-header {
            background-color: var(--cetis-primary);
            color: white;
        }
        .offcanvas-header .btn-close {
            filter: invert(1);
        }
        .offcanvas-body .nav-link {
            color: #333;
            font-weight: 500;
            padding: 12px 15px;
            border-radius: 8px;
        }
        .offcanvas-body .nav-link:hover, .offcanvas-body .nav-link.active {
            background-color: var(--cetis-primary);
            color: white;
        }
    </style>
</head>
<body>    
    <button id="menu-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" title="Abrir Menú">
        <i class="bi bi-list"></i>
    </button>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <img src="https://placehold.co/32x32/8C001A/ffffff?text=C17" alt="Logo" class="d-inline-block align-text-top rounded-full me-2">
                Sistema de Asesorías
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-3">
                        <span class="nav-link fw-bold text-dark">
                            <i class="bi bi-person-check-fill text-success"></i> Hola, {{ $user->nombre }}
                        </span>
                    </li>

                    @if(Auth::user()->rol === 'Administrador')
                        <li class="nav-item"><a class="nav-link text-cetis fw-bold" href="#">MODO ADMIN:</a></li>
                        <li class="nav-item"><a class="nav-link" href="/gestioncurso">Cursos</a></li>
                        <li class="nav-item"><a class="nav-link" href="/gestionusuario">Usuarios</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold">
            <i class="bi bi-person-circle me-2"></i> {{ $user->nombre }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4">
            <div class="text-center mb-4">
                <span class="badge bg-danger p-2">Rol: {{ $user->rol }}</span>
            </div>
            
            <p class="text-muted small mb-2">Módulos Disponibles</p>
            <div class="nav flex-column nav-pills">
                <a class="nav-link active" href="/principal"><i class="bi bi-house-door me-3"></i> Inicio</a>
                <a class="nav-link" href="/agenda"><i class="bi bi-calendar-event me-3"></i> Mi Agenda</a>
                <a class="nav-link" href="/buscarcurso"><i class="bi bi-journal-bookmark me-3"></i> Buscar Cursos</a>
                <a class="nav-link" href="/biblioteca"><i class="bi bi-archive me-3"></i> Biblioteca Digital</a>
            </div>
            
            <hr class="my-4">
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-3 fw-bolder mb-3">¡Bienvenido, {{ $user->nombre }}!</h1>
            <p class="lead mb-5 opacity-75">Plataforma de Refuerzo Académico CETIS 17</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="/buscarcurso" class="btn btn-cetis-primary btn-lg fw-bold shadow-lg">Solicitar Asesoría</a>
                <a href="/biblioteca" class="btn btn-outline-light btn-lg fw-bold shadow-lg">Ver Biblioteca</a>
            </div>
        </div>
    </header>

    <section class="py-5 mt-4">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <a href="/agenda" class="text-decoration-none h-100">
                        <div class="card p-4 h-100 shadow-sm text-center">
                            <i class="feature-icon bi bi-calendar-event"></i>
                            <h5 class="card-title fw-bold text-cetis">Agenda</h5>
                            <p class="card-text text-muted">Organiza tus horarios y asesorías pendientes.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/buscarcurso" class="text-decoration-none h-100">
                        <div class="card p-4 h-100 shadow-sm text-center">
                            <i class="feature-icon bi bi-journal-bookmark"></i>
                            <h5 class="card-title fw-bold text-cetis">Cursos</h5>
                            <p class="card-text text-muted">Explora los temas disponibles para estudio.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/biblioteca" class="text-decoration-none h-100">
                        <div class="card p-4 h-100 shadow-sm text-center">
                            <i class="feature-icon bi bi-archive"></i>
                            <h5 class="card-title fw-bold text-cetis">Biblioteca</h5>
                            <p class="card-text text-muted">Guías y libros digitales a tu alcance.</p>
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