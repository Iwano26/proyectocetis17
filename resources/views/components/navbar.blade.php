<nav class="navbar-cetis">
    <div class="container-fluid d-flex align-items-center justify-content-between px-4">
        
        {{-- Botón hamburguesa --}}
        <div style="width: 56px; display: flex; align-items: center; justify-content: flex-start;">
            <button id="menu-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" style="border: none; background: none; padding: 0; position: static;">
                <i class="bi bi-list" style="font-size: 1.8rem; color: var(--cetis-primary); cursor: pointer;"></i>
            </button>
        </div>

        {{-- Logotipo y Texto Brand --}}
        <a href="/principal" class="navbar-brand-cetis">
            <img src="{{ asset('img/cetis.png') }}" alt="Logo CETIS 17" width="35" height="35" class="d-inline-block align-top">
            <span class="brand-text">Sistema de <span>asesorías</span></span>
        </a>

        {{-- Enlaces Derechos y Dropdown de Perfil --}}
        <ul class="navbar-nav-cetis">
            @if(Auth::user()->rol === 'Administrador')
                <li><a href="/gestioncurso" class="nav-link-cetis">Cursos</a></li>
                <li><a href="/gestionusuario" class="nav-link-cetis">Usuarios</a></li>
            @endif

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