{{-- ===== NAVBAR ===== --}}
<nav class="navbar-cetis">
    {{-- Cambiamos 'container' por 'container-fluid' e incluimos un padding horizontal (px-3 o px-4) para controlar el despegue exacto del borde --}}
    <div class="container-fluid d-flex align-items-center justify-content-between px-4">

        {{-- Contenedor del botón hamburguesa alineado perfectamente a la izquierda --}}
        <div style="width: 56px; display: flex; align-items: center; justify-content: flex-start;">
            <button id="menu-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarCetis" aria-controls="sidebarCetis" style="border: none; background: none; padding: 0;">
                <i class="bi bi-list" style="font-size: 1.8rem; color: var(--cetis-texto); cursor: pointer;"></i>
            </button>
        </div>

        {{-- Brand --}}
        <a href="/principal" class="navbar-brand-cetis">
            <div class="brand-logo"></div>
            <span class="brand-text">Sistema de <span>asesorías</span></span>
        </a>

        {{-- Nav derecha --}}
        <ul class="navbar-nav-cetis">
            @if(Auth::user()->rol === 'Administrador')
                <li><a href="/gestioncurso" class="nav-link-cetis">Cursos</a></li>
                <li><a href="/gestionusuario" class="nav-link-cetis">Usuarios</a></li>
            @endif

            {{-- Dropdown Perfil --}}
            <li class="perfil-dropdown">
                <button class="perfil-btn" id="perfilToggle">
                    <div class="avatar"><i class="bi bi-person-fill"></i></div>
                    {{ Auth::user()->nombre }}
                    <i class="bi bi-chevron-down" style="font-size:0.7rem; opacity:0.8;"></i>
                </button>
                <div class="dropdown-menu-cetis" id="perfilMenu">
                    <div class="dropdown-header-cetis">
                        <div class="nombre">{{ Auth::user()->nombre }}</div>
                        <div class="rol">{{ Auth::user()->rol }}</div>
                    </div>
                    <a href="/perfil" class="dropdown-item-cetis">
                        <i class="bi bi-person-circle"></i> Mi Perfil
                    </a>
                    <a href="/perfil/editar" class="dropdown-item-cetis">
                        <i class="bi bi-pencil-square"></i> Editar Perfil
                    </a>
                    <hr style="margin: 6px 0; border-color: #f0f0f0;">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item-cetis danger">
                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>