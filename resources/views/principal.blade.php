@extends('layouts.app')

@section('content')

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
            Plataforma de refuerzo académico. Encuentra asesorías, materiales de estudio y herramientas para impulsar tu aprendizaje.
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

@endsection