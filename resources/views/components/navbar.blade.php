{{-- ===== NAVBAR ===== --}}
<nav class="navbar-cetis">
    <div class="container d-flex align-items-center justify-content-between">

        {{-- Espacio para el botón hamburguesa --}}
        <div style="width: 56px;"></div>

        {{-- Brand --}}
        <a href="/principal" class="navbar-brand-cetis">
            <div class="brand-logo">C17</div>
            <span class="brand-text">Sistema de <span>Asesorías</span></span>
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