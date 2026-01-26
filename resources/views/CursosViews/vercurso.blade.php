<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CETIS 17 | Curso Base de Datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --cetis-rojo: #8C001A; 
            --cetis-primary: #8C001A;
        }
        body { 
            background-color: #f8f9fa; 
            font-family: 'Inter', sans-serif; 
        }

        #menu-toggle-btn {
            position: fixed; top: 15px; left: 20px; z-index: 1040; 
            background-color: white; color: var(--cetis-rojo);
            border: 2px solid var(--cetis-rojo); padding: 5px 12px;
            border-radius: 8px; font-size: 1.5rem; cursor: pointer;
            transition: all 0.3s;
        }
        #menu-toggle-btn:hover { background-color: var(--cetis-primary); color: white; }

        .navbar {
            background-color: white !important; border-bottom: 1px solid #dee2e6;
            min-height: 70px; padding-left: 80px; 
        }
        .navbar-brand { color: var(--cetis-rojo) !important; font-weight: bold; }

        .offcanvas-header { background-color: var(--cetis-primary); color: white; }
        .offcanvas-header .btn-close { filter: invert(1); }
        .offcanvas-body .nav-link { color: #333; font-weight: 500; padding: 12px 15px; border-radius: 8px; transition: all 0.2s; }
        .offcanvas-body .nav-link:hover, .offcanvas-body .nav-link.active { background-color: var(--cetis-primary); color: white !important; }

        .tarjeta-curso-interna {
            background-color: white; 
            border: 2px solid var(--cetis-rojo);
            border-radius: 12px; 
            padding: 20px; 
            margin-bottom: 15px;
        }

        .opciones-curso .nav-link {
            color: #333;
            font-weight: 700;
            border: 1px solid #dee2e6;
            margin-bottom: 8px;
            border-radius: 8px;
            padding: 15px;
            text-align: left;
            background-color: white;
        }
        .opciones-curso .nav-link.active {
            background-color: var(--cetis-rojo);
            color: white;
            border-color: var(--cetis-rojo);
        }

        .circulo-rojo-mini {
            width: 45px; height: 45px; background-color: var(--cetis-rojo);
            border-radius: 50%; display: flex; align-items: center; 
            justify-content: center; color: white; margin-bottom: 15px;
        }

        .formato-horario {
            display: inline-block;
            background-color: #eee;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <button id="menu-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
        <i class="bi bi-list"></i>
    </button>
    
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistema de Asesorías</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><span class="nav-link fw-bold text-danger">ADMINISTRACIÓN:</span></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="#">Cursos</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="#">Biblioteca</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="#">Usuarios</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold"><i class="bi bi-person-fill me-2"></i> Menú de Usuario</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4">
            <div class="nav flex-column nav-pills">
                <a class="nav-link" href="/principal"><i class="bi bi-calendar-event me-3"></i> Principal</a>
                <a class="nav-link" href="/agenda"><i class="bi bi-calendar-event me-3"></i> Agenda</a>
                <a class="nav-link active" href="/buscarcurso"><i class="bi bi-journal-bookmark me-3"></i> Cursos</a>
                <a class="nav-link" href="/biblioteca"><i class="bi bi-archive me-3"></i> Biblioteca</a>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-4 px-4">
        
        <div class="tarjeta-curso-interna shadow-sm bg-white mb-4">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2 class="fw-bold m-0 text-uppercase">ADMINISTRACIÓN DE BASES DE DATOS</h2>
                    <p class="text-muted mb-0">Impartido por: </p>
                </div>
                <div class="col-md-3 text-md-end">
                    <span class="badge bg-success shadow-sm">ESTADO: ABIERTO</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="nav flex-column opciones-curso">
                    <a href="#" class="nav-link active shadow-sm"><i class="bi bi-info-circle me-2"></i> INFORMACIÓN</a>
                    <a href="#" class="nav-link shadow-sm"><i class="bi bi-clock me-2"></i> HORARIOS</a>
                    <a href="#" class="nav-link shadow-sm"><i class="bi bi-list-task me-2"></i> ACTIVIDADES</a>
                    <a href="#" class="nav-link shadow-sm"><i class="bi bi-chat-dots me-2"></i> FORO</a>
                </div>
            </div>

            <div class="col-md-9">
                <h4 class="fw-bold mb-3">Sesiones Programadas</h4>

                <div class="tarjeta-curso-interna shadow-sm">
                    <div class="row align-items-start">
                        <div class="col-md-8">
                            <div class="circulo-rojo-mini">
                                <i class="bi bi-database-fill-gear fs-5"></i>
                            </div>
                            <h5 class="fw-bold text-uppercase mb-1">Tema: Diseño Entidad-Relación</h5>
                            <p class="text-muted small mb-0">Lugar: Centro de Cómputo B</p>
                            
                            <div class="mt-2">
                                <div class="formato-horario">Lunes-10:00</div>
                                <div class="formato-horario">Martes-09:00-11:00</div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <button class="btn btn-dark fw-bold w-100">INGRESAR</button>
                        </div>
                    </div>
                </div>

                <div class="tarjeta-curso-interna shadow-sm">
                    <div class="row align-items-start">
                        <div class="col-md-8">
                            <div class="circulo-rojo-mini">
                                <i class="bi bi-code-square fs-5"></i>
                            </div>
                            <h5 class="fw-bold text-uppercase mb-1">Tema: Consultas SQL Avanzadas (JOINS)</h5>
                            <p class="text-muted small mb-0">Lugar: Aula de Medios 2</p>
                            
                            <div class="mt-2">
                                <div class="formato-horario">Jueves-08:00</div>
                                <div class="formato-horario">Viernes-08:00-10:00</div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <button class="btn btn-dark fw-bold w-100">INGRESAR</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>