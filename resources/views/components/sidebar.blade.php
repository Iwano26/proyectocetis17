<button id="menu-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" title="Abrir Menú">
    <i class="bi bi-list"></i>
</button>

<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold"> 
            <i class="bi bi-person-circle me-2"></i> {{ Auth::user()->nombre }}    
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-4">
        <div class="text-center mb-4">
            <span class="badge rounded-pill bg-danger px-3 py-2">Rol: {{ Auth::user()->rol }}</span>
        </div>
        
        <p class="text-muted small text-uppercase fw-bold mb-2">Módulos Disponibles</p>
        <div class="nav flex-column nav-pills">
            <a class="nav-link {{ request()->is('principal') ? 'active' : '' }}" href="/principal">
                <i class="bi bi-house-door me-3"></i> Inicio
            </a>
            <a class="nav-link {{ request()->is('agenda') ? 'active' : '' }}" href="/agenda">
                <i class="bi bi-calendar-event me-3"></i> Mi Agenda
            </a>
            <a class="nav-link {{ request()->is('cursos*') ? 'active' : '' }}" href="{{ route('cursos.index') }}">
                <i class="bi bi-journal-bookmark me-3"></i> Buscar Cursos
            </a>
            <a class="nav-link {{ request()->is('biblioteca*') ? 'active' : '' }}" href="/biblioteca">
                <i class="bi bi-archive me-3"></i> Biblioteca Digital
            </a>
            <a class="nav-link {{ request()->is('perfil') ? 'active' : '' }}" href="/perfil">
                <i class="bi bi-person me-3"></i> Mi perfil
            </a>
        </div>

        @if(Auth::user()->rol === 'Administrador')
            <hr class="my-4">
            <p class="text-danger small text-uppercase fw-bold mb-2">Administración</p>
            <div class="nav flex-column nav-pills">
                <a class="nav-link {{ request()->is('gestionusuario') ? 'active' : '' }}" href="/gestionusuario">
                    <i class="bi bi-people me-3"></i> Usuarios
                </a>
            </div>
        @endif
        
        <div class="mt-auto pt-5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 fw-bold">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</div>