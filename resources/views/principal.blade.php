<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asesorías | CETIS 17</title>
    <!-- Enlace al CDN de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Iconos de Bootstrap para un toque profesional -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Fuente Inter para un look moderno -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* Paleta de colores ajustada al tema institucional (rojos y neutros) */
        :root {
            --cetis-primary: #8C001A; /* Rojo oscuro/institucional */
            --cetis-secondary: #004A77; /* Azul o similar si es necesario */
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
            /* Degradado más formal que sugiere conocimiento y seriedad */
            background: linear-gradient(135deg, var(--cetis-primary), #A61E34);
            color: white;
            padding: 100px 0;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        .card {
            border-radius: 15px;
            border-left: 5px solid var(--cetis-primary); /* Toque de color institucional */
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
            background-color: #A61E34; /* Un rojo un poco más claro al pasar el mouse */
            border-color: #A61E34;
            color: white;
        }
        /* Estilo para el botón del menú flotante */
        #menu-toggle-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1040; /* Mayor que el offcanvas para que se vea */
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

        /* Estilos específicos para el Offcanvas del menú lateral */
        .offcanvas-header {
            background-color: var(--cetis-primary);
            color: white;
            border-bottom: none;
        }
        .offcanvas-header .btn-close {
            filter: invert(1); /* Pone la 'x' blanca */
        }
        .offcanvas-body .nav-link {
            color: #333;
            font-weight: 500;
            padding: 12px 15px;
            border-radius: 8px;
            transition: background-color 0.2s, color 0.2s;
        }
        .offcanvas-body .nav-link:hover {
            background-color: var(--cetis-primary);
            color: white;
        }
    </style>
</head>
<body>    
    <button id="menu-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" title="Abrir Menú">
        <i class="bi bi-list"></i>
    </button>
    
    <!-- 1. BARRA DE NAVEGACIÓN SUPERIOR (Menú para Administrador) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <!-- Título/Logo -->
            <a class="navbar-brand fw-bold" href="#">
                <img src="https://placehold.co/32x32/8C001A/ffffff?text=C17" alt="Logo" class="d-inline-block align-text-top rounded-full me-2">
                Sistema de Asesorías
            </a>
            <!-- Botón de colapso de Bootstrap (para el menú superior en móvil) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <!-- Enlaces de Administrador -->
                    <li class="nav-item">
                        <a class="nav-link fw-bold text-dark">ADMINISTACIÓN:</a> 
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/gestioncurso">Cursos</a> 
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Biblioteca</a> 
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Asesorías</a> 
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="/gestionusuario">Usuarios</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="sidebarMenuLabel">
                <i class="bi bi-person-fill me-2"></i> Menú de Usuario
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <p class="text-muted small mb-4">Módulos para Estudiantes y Asesores.</p>
            <div class="nav flex-column nav-pills">
                <!-- Opción: Agenda -->
                <a class="nav-link" href="#agenda">
                    <i class="bi bi-calendar-event me-3"></i> Agenda
                </a>
                <!-- Opción: Cursos -->
                <a class="nav-link" href="/curso">
                    <i class="bi bi-journal-bookmark me-3"></i> Cursos
                </a>
                <!-- Opción: Biblioteca -->
                <a class="nav-link" href="#biblioteca">
                    <i class="bi bi-archive me-3"></i> Biblioteca
                </a>
            </div>
            
            <hr class="my-4">
            
            <a class="nav-link btn btn-outline-secondary mt-3" href="/login">
                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
            </a>
        </div>
    </div>

    <!-- 3. Sección de Héroe (Bienvenida principal) -->
    <header class="hero-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="display-3 fw-bolder mb-3">
                        CETIS 17: Plataforma de Refuerzo Académico
                    </h1>
                    <p class="lead mb-5 opacity-75">
                        Conecta con asesores, accede a material exclusivo y resuelve tus dudas para destacar en cada semestre.
                    </p>
                    <a href="#asesorias" class="btn btn-cetis-primary btn-lg me-3 fw-bold shadow-lg">
                        Solicitar Asesoría <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#biblioteca" class="btn btn-outline-light btn-lg fw-bold shadow-lg">
                        Explorar Recursos
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- 4. Sección de Funcionalidades Clave (Tarjetas) -->
    <section id="funcionalidades" class="py-5 mt-4">
        <div class="container">
            <h2 class="text-center mb-5 display-5 fw-bold text-dark">Tu Camino al Éxito Educativo</h2>
            <div class="row g-4">
                <!-- Tarjeta 1: Cursos Personalizados -->
                <div class="col-md-6 col-lg-3">
                    <div class="card p-4 h-100 shadow-sm" id="cursos">
                        <div class="text-center">
                            <i class="feature-icon bi bi-book-half"></i>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-cetis">Cursos</h5>
                            <p class="card-text text-muted">
                                Material didáctico y asesorias enfocados en las áreas clave de tu bachillerato tecnológico.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2: Biblioteca Digital -->
                <div class="col-md-6 col-lg-3">
                    <div class="card p-4 h-100 shadow-sm" id="biblioteca">
                        <div class="text-center">
                            <i class="feature-icon bi bi-archive"></i>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-cetis">Biblioteca Digital</h5>
                            <p class="card-text text-muted">
                                Acceso rápido a libros, tesis y documentos relevantes.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 3: Asesorías Uno a Uno -->
                <div class="col-md-6 col-lg-3">
                    <div class="card p-4 h-100 shadow-sm" id="asesorias">
                        <div class="text-center">
                            <i class="feature-icon bi bi-chat-dots"></i>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-cetis">Asesorías</h5>
                            <p class="card-text text-muted">
                                Reserva un espacio con un profesor o estudiante avanzado para resolver dudas específicas.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 4: Foros de Comunidad -->
                <div class="col-md-6 col-lg-3">
                    <div class="card p-4 h-100 shadow-sm" id="foros">
                        <div class="text-center">
                            <i class="feature-icon bi bi-people"></i>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-cetis">Foros</h5>
                            <p class="card-text text-muted">
                                Participa en discusiones, comparte conocimiento y ayuda a tus compañeros de clase.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 CETIS 17 | Sistema de Asesorías Académicas. | DGETI.</p>
        </div>
    </footer>

    <!-- Script de Bootstrap (Debe ir al final del body) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>