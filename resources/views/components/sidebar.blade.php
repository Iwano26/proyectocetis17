<div class="offcanvas offcanvas-start offcanvas-cetis" tabindex="-1" id="sidebarCetis" aria-labelledby="sidebarCetisLabel">
    
    <div class="offcanvas-header-cetis">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div>
                <div class="sidebar-nombre">{{ Auth::user()->nombre }}</div>
                <div class="sidebar-rol">Panel Académico</div>
            </div>
        </div>
        <button type="button" class="sidebar-close-btn" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="offcanvas-body-cetis">
        
        <div class="sidebar-section-label">Navegación</div>
        <nav class="sidebar-nav">
            <a href="/home" class="sidebar-link active">
                <i class="bi bi-house-door-fill"></i> Inicio
            </a>
            <a href="/agenda" class="sidebar-link">
                <i class="bi bi-calendar-event-fill"></i> Mi Agenda
            </a>
            <a href="/buscarcurso" class="sidebar-link">
                <i class="bi bi-journal-bookmark-fill"></i> Cursos Disponibles
            </a>
            <a href="/biblioteca" class="sidebar-link">
                <i class="bi bi-archive-fill"></i> Biblioteca Digital
            </a>
        </nav>

        <hr class="sidebar-divider">

        <div class="sidebar-section-label">Usuario</div>
        <nav class="sidebar-nav">
            <a href="/perfil" class="sidebar-link">
                <i class="bi bi-person-badge-fill"></i> Mi Perfil
            </a>
            <a href="/historial" class="sidebar-link">
                <i class="bi bi-clock-history"></i> Historial Académico
            </a>
        </nav>

        <hr class="sidebar-divider">
        
        <div class="sidebar-section-label danger">Soporte</div>
        <nav class="sidebar-nav">
            <a href="/ayuda" class="sidebar-link">
                <i class="bi bi-question-circle-fill"></i> Manual de Ayuda
            </a>
        </nav>
    </div>

    <div class="offcanvas-footer-cetis">
        <form action="{{ route('logout') }}" method="POST" class="w-100">
            @csrf
            <button type="submit" class="sidebar-logout-btn">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</div>