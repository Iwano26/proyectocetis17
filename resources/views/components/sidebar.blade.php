<button id="menu-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" title="Abrir Menú">
    <i class="bi bi-list"></i>
</button>

<div class="offcanvas offcanvas-start offcanvas-cetis" tabindex="-1" id="sidebarMenu">

    <div class="offcanvas-header-cetis">
        <div class="sidebar-user">
            <div class="sidebar-avatar"><i class="bi bi-person-fill"></i></div>
            <div>
                <div class="sidebar-nombre">{{ Auth::user()->nombre }}</div>
                <div class="sidebar-rol">{{ Auth::user()->rol }}</div>
            </div>
        </div>
        <button type="button" class="sidebar-close-btn" data-bs-dismiss="offcanvas">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="offcanvas-body-cetis">
        <p class="sidebar-section-label">Módulos Disponibles</p>
        <nav class="sidebar-nav">
            <a class="sidebar-link {{ request()->is('principal')  ? 'active' : '' }}" href="/principal">
                <i class="bi bi-house-door-fill"></i> Inicio
            </a>
            <a class="sidebar-link {{ request()->is('agenda')     ? 'active' : '' }}" href="/agenda">
                <i class="bi bi-calendar-event-fill"></i> Mi Agenda
            </a>
            <a class="sidebar-link {{ request()->is('cursos*')    ? 'active' : '' }}" href="{{ route('cursos.index') }}">
                <i class="bi bi-journal-bookmark-fill"></i> Buscar Cursos
            </a>
            <a class="sidebar-link {{ request()->is('biblioteca*') ? 'active' : '' }}" href="/biblioteca">
                <i class="bi bi-archive-fill"></i> Biblioteca Digital
            </a>
            <a class="sidebar-link {{ request()->is('perfil')     ? 'active' : '' }}" href="/perfil">
                <i class="bi bi-person-badge-fill"></i> Mi Perfil
            </a>
        </nav>

        @if(Auth::user()->rol === 'Administrador')
            <hr class="sidebar-divider">
            <p class="sidebar-section-label danger">Administración</p>
            <nav class="sidebar-nav">
                <a class="sidebar-link {{ request()->is('gestionusuario') ? 'active' : '' }}" href="/gestionusuario">
                    <i class="bi bi-people-fill"></i> Usuarios
                </a>
            </nav>
        @endif
    </div>

    <div class="offcanvas-footer-cetis">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-logout-btn">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</div>