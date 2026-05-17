@extends('layouts.app')
@section('content')

<style>
    :root {
        --cetis-rojo: #8C001A;
        --cetis-rojo-oscuro: #6b0014;
        --cetis-rojo-claro: #b0001f;
        --cetis-gris: #f4f5f7;
        --cetis-texto: #1a1a2e;
    }

    * { box-sizing: border-box; }

    body {
        font-family: 'Georgia', 'Times New Roman', serif;
        background-color: #f9f9f9;
        color: var(--cetis-texto);
    }

    /* ===== NAVBAR ===== */
    .navbar-cetis {
        background: #fff;
        border-bottom: 3px solid var(--cetis-rojo);
        padding: 0.6rem 0;
        position: sticky;
        top: 0;
        z-index: 1050;
        box-shadow: 0 2px 12px rgba(140,0,26,0.08);
    }

    .navbar-brand-cetis {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .brand-logo {
        width: 38px;
        height: 38px;
        background: var(--cetis-rojo);
        color: white;
        font-weight: 900;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        letter-spacing: -0.5px;
        font-family: 'Georgia', serif;
    }

    .brand-text {
        font-size: 1rem;
        font-weight: 700;
        color: var(--cetis-texto);
        letter-spacing: 0.3px;
        font-family: 'Georgia', serif;
    }

    .brand-text span {
        color: var(--cetis-rojo);
    }

    .navbar-nav-cetis {
        display: flex;
        align-items: center;
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-link-cetis {
        color: #444;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        padding: 6px 14px;
        border-radius: 6px;
        transition: all 0.2s;
        font-family: 'Georgia', serif;
    }

    .nav-link-cetis:hover {
        background: var(--cetis-gris);
        color: var(--cetis-rojo);
    }

    /* Dropdown perfil */
    .perfil-dropdown {
        position: relative;
    }

    .perfil-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--cetis-rojo);
        color: white;
        border: none;
        padding: 7px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background 0.2s;
        font-family: 'Georgia', serif;
    }

    .perfil-btn:hover { background: var(--cetis-rojo-oscuro); }

    .perfil-btn .avatar {
        width: 26px;
        height: 26px;
        background: rgba(255,255,255,0.25);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }

    .dropdown-menu-cetis {
        position: absolute;
        right: 0;
        top: calc(100% + 8px);
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        min-width: 200px;
        padding: 6px;
        display: none;
        z-index: 2000;
    }

    .dropdown-menu-cetis.show { display: block; }

    .dropdown-header-cetis {
        padding: 10px 12px 8px;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 4px;
    }

    .dropdown-header-cetis .nombre {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--cetis-texto);
    }

    .dropdown-header-cetis .rol {
        font-size: 0.75rem;
        color: var(--cetis-rojo);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .dropdown-item-cetis {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border-radius: 7px;
        color: #333;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: background 0.15s;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
    }

    .dropdown-item-cetis:hover { background: var(--cetis-gris); color: var(--cetis-rojo); }
    .dropdown-item-cetis.danger:hover { background: #fff0f0; color: #c0001a; }
    .dropdown-item-cetis i { font-size: 1rem; opacity: 0.7; }

    /* Menu toggle */
    #menu-toggle-btn {
        position: fixed;
        top: 14px;
        left: 16px;
        z-index: 1100;
        background: white;
        border: 2px solid var(--cetis-rojo);
        color: var(--cetis-rojo);
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(140,0,26,0.15);
    }

    #menu-toggle-btn:hover {
        background: var(--cetis-rojo);
        color: white;
    }

    /* ===== HERO ===== */
    .hero-cetis {
        background: var(--cetis-rojo);
        background-image:
            radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.05) 0%, transparent 60%),
            radial-gradient(ellipse at 80% 20%, rgba(0,0,0,0.15) 0%, transparent 50%);
        color: white;
        padding: 80px 0 70px;
        position: relative;
        overflow: hidden;
    }

    .hero-cetis::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 300px;
        height: 300px;
        border: 40px solid rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .hero-cetis::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 250px;
        height: 250px;
        border: 30px solid rgba(255,255,255,0.04);
        border-radius: 50%;
    }

    .hero-eyebrow {
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        opacity: 0.75;
        margin-bottom: 12px;
        font-family: 'Georgia', serif;
    }

    .hero-titulo {
        font-size: clamp(2rem, 5vw, 3.2rem);
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 16px;
        font-family: 'Georgia', serif;
    }

    .hero-titulo span {
        color: rgba(255,255,255,0.75);
        font-style: italic;
        font-weight: 400;
    }

    .hero-subtitulo {
        font-size: 1rem;
        opacity: 0.8;
        max-width: 480px;
        margin: 0 auto 32px;
        line-height: 1.6;
    }

    .btn-hero-blanco {
        background: white;
        color: var(--cetis-rojo);
        font-weight: 700;
        padding: 12px 28px;
        border-radius: 8px;
        border: none;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(0,0,0,0.2);
        font-family: 'Georgia', serif;
    }

    .btn-hero-blanco:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.25);
        color: var(--cetis-rojo-oscuro);
    }

    .btn-hero-outline {
        background: transparent;
        color: white;
        font-weight: 700;
        padding: 12px 28px;
        border-radius: 8px;
        border: 2px solid rgba(255,255,255,0.6);
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
        font-family: 'Georgia', serif;
    }

    .btn-hero-outline:hover {
        background: rgba(255,255,255,0.12);
        border-color: white;
        color: white;
        transform: translateY(-2px);
    }

    /* Stats bar */
    .stats-bar {
        background: white;
        border-bottom: 1px solid #eee;
        padding: 20px 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .stat-item {
        text-align: center;
        padding: 0 20px;
        border-right: 1px solid #eee;
    }

    .stat-item:last-child { border-right: none; }

    .stat-numero {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--cetis-rojo);
        font-family: 'Georgia', serif;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-top: 4px;
        font-weight: 600;
    }

    /* ===== ACCESOS RÁPIDOS ===== */
    .section-titulo {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--cetis-texto);
        font-family: 'Georgia', serif;
        margin-bottom: 6px;
    }

    .section-subtitulo {
        color: #888;
        font-size: 0.9rem;
        margin-bottom: 32px;
    }

    .acceso-card {
        background: white;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        padding: 28px 24px;
        text-decoration: none;
        color: inherit;
        display: block;
        transition: all 0.25s;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .acceso-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--cetis-rojo);
        transform: scaleY(0);
        transition: transform 0.25s;
        border-radius: 0 2px 2px 0;
    }

    .acceso-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(140,0,26,0.1);
        border-color: rgba(140,0,26,0.2);
        color: inherit;
    }

    .acceso-card:hover::before { transform: scaleY(1); }

    .acceso-icono {
        width: 52px;
        height: 52px;
        background: #fff0f2;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: var(--cetis-rojo);
        margin-bottom: 16px;
        transition: background 0.25s;
    }

    .acceso-card:hover .acceso-icono {
        background: var(--cetis-rojo);
        color: white;
    }

    .acceso-titulo {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 6px;
        font-family: 'Georgia', serif;
    }

    .acceso-desc {
        font-size: 0.85rem;
        color: #888;
        line-height: 1.5;
        margin: 0;
    }

    .acceso-arrow {
        position: absolute;
        top: 24px;
        right: 20px;
        color: #ddd;
        font-size: 1rem;
        transition: all 0.25s;
    }

    .acceso-card:hover .acceso-arrow {
        color: var(--cetis-rojo);
        transform: translateX(4px);
    }

    /* ===== BANNER INSTITUCIONAL ===== */
    .banner-institucional {
        background: linear-gradient(135deg, var(--cetis-texto) 0%, #2d1b1b 100%);
        color: white;
        border-radius: 14px;
        padding: 36px 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }

    .banner-badge {
        display: inline-block;
        background: var(--cetis-rojo);
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .banner-titulo {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 6px;
        font-family: 'Georgia', serif;
    }

    .banner-subtitulo {
        font-size: 0.875rem;
        opacity: 0.7;
        margin: 0;
    }

    /* ===== FOOTER ===== */
    .footer-cetis {
        background: var(--cetis-texto);
        color: rgba(255,255,255,0.6);
        padding: 28px 0;
        margin-top: 60px;
        font-size: 0.825rem;
        text-align: center;
        border-top: 3px solid var(--cetis-rojo);
    }

    .footer-cetis strong { color: white; }
</style>



{{-- ===== HERO ===== --}}
<section class="hero-cetis text-center">
    <div class="container position-relative" style="z-index:1;">
        <p class="hero-eyebrow">
            <i class="bi bi-mortarboard-fill me-2"></i>
            Centro de Estudios Tecnológicos Industrial y de Servicios No. 17
        </p>
        <h1 class="hero-titulo">
            Bienvenido, <span>{{ Auth::user()->nombre }}</span>
        </h1>
        <p class="hero-subtitulo">
            Plataforma de Refuerzo Académico. Encuentra asesorías, materiales de estudio y herramientas para impulsar tu aprendizaje.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="/buscarcurso" class="btn-hero-blanco">
                <i class="bi bi-journal-bookmark-fill me-2"></i> Buscar Cursos
            </a>
            <a href="/biblioteca" class="btn-hero-outline">
                <i class="bi bi-archive-fill me-2"></i> Biblioteca Digital
            </a>
        </div>
    </div>
</section>

{{-- ===== ACCESOS RÁPIDOS ===== --}}
<section class="py-5">
    <div class="container">
        <div class="text-center">
            <h2 class="section-titulo">Módulos Disponibles</h2>
            <p class="section-subtitulo">Accede rápidamente a todas las herramientas de la plataforma</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <a href="/agenda" class="acceso-card">
                    <i class="bi bi-chevron-right acceso-arrow"></i>
                    <div class="acceso-icono"><i class="bi bi-calendar-event-fill"></i></div>
                    <div class="acceso-titulo">Mi Agenda</div>
                    <p class="acceso-desc">Consulta tus próximas asesorías y fechas importantes.</p>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="/buscarcurso" class="acceso-card">
                    <i class="bi bi-chevron-right acceso-arrow"></i>
                    <div class="acceso-icono"><i class="bi bi-journal-bookmark-fill"></i></div>
                    <div class="acceso-titulo">Cursos</div>
                    <p class="acceso-desc">Explora las asesorías y materiales disponibles para tus materias.</p>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="/biblioteca" class="acceso-card">
                    <i class="bi bi-chevron-right acceso-arrow"></i>
                    <div class="acceso-icono"><i class="bi bi-archive-fill"></i></div>
                    <div class="acceso-titulo">Biblioteca Digital</div>
                    <p class="acceso-desc">Libros, guías de estudio y documentos académicos en línea.</p>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="/perfil" class="acceso-card">
                    <i class="bi bi-chevron-right acceso-arrow"></i>
                    <div class="acceso-icono"><i class="bi bi-person-badge-fill"></i></div>
                    <div class="acceso-titulo">Mi Perfil</div>
                    <p class="acceso-desc">Actualiza tu información personal y contraseña.</p>
                </a>
            </div>
        </div>

        {{-- Banner institucional --}}
        <div class="banner-institucional mt-5">
            <div>
                <div class="banner-badge">CETIS 17 — DGETI</div>
                <div class="banner-titulo">Comprometidos con tu formación académica</div>
                <p class="banner-subtitulo">Sistema de Asesorías — Subsecretaría de Educación Media Superior</p>
            </div>
            <a href="/buscarcurso" class="btn-hero-blanco flex-shrink-0" style="white-space:nowrap;">
                <i class="bi bi-arrow-right me-1"></i> Solicitar Asesoría
            </a>
        </div>
    </div>
</section>

{{-- ===== FOOTER ===== --}}
<footer class="footer-cetis">
    <div class="container">
        <p class="mb-0">&copy; 2026 <strong>CETIS 17</strong> | DGETI — Subsecretaría de Educación Media Superior</p>
    </div>
</footer>

{{-- Script dropdown perfil --}}
<script>
    const perfilToggle = document.getElementById('perfilToggle');
    const perfilMenu = document.getElementById('perfilMenu');

    perfilToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        perfilMenu.classList.toggle('show');
    });

    document.addEventListener('click', function() {
        perfilMenu.classList.remove('show');
    });

    perfilMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
</script>

@endsection