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