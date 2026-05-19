@extends('layouts.app')

@section('content')

{{-- CSS exclusivo de agenda --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;700&display=swap');

    :root {
        --rojo: #dc3545;
        --rojo-suave: #fff0f1;
        --verde: #198754;
        --azul: #0d6efd;
        --amarillo: #ffc107;
        --gris-claro: #f8f9fa;
        --texto: #1a1a2e;
        --muted: #6c757d;
        --card-shadow: 0 2px 12px rgba(0,0,0,0.08);
        --radius: 14px;
    }

    .agenda-wrapper {
        font-family: 'DM Sans', sans-serif;
        background: #f5f5f7;
        min-height: 100vh;
        padding: 2rem 1.5rem 4rem;
    }

    /* ── HEADER ── */
    .agenda-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .agenda-header h2 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--texto);
        margin: 0;
    }
    .agenda-header h2 span { color: var(--rojo); }
    .agenda-rol-badge {
        background: var(--texto);
        color: #fff;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
    }

    /* ── STATS STRIP ── */
    .stats-strip {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    .stat-pill {
        background: #fff;
        border-radius: 999px;
        padding: 0.5rem 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--texto);
        box-shadow: var(--card-shadow);
    }
    .stat-pill .dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* ── SECCIÓN TÍTULO ── */
    .section-label {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e0e0e0;
    }

    /* ── TARJETAS DE EVENTOS ── */
    .eventos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
        margin-bottom: 2.5rem;
    }

    .evento-card {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1.5px solid transparent;
    }
    .evento-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .evento-card.asesoria { border-color: #ffd6da; }
    .evento-card.examen   { border-color: #cfe2ff; }

    .card-accent {
        height: 5px;
        width: 100%;
    }
    .asesoria .card-accent { background: var(--rojo); }
    .examen   .card-accent { background: var(--azul); }

    .card-inner {
        padding: 1.1rem 1.2rem 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-tipo {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .asesoria .card-tipo { color: var(--rojo); }
    .examen   .card-tipo { color: var(--azul); }

    .card-titulo {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--texto);
        margin-bottom: 0.6rem;
        line-height: 1.3;
    }

    .card-meta {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        font-size: 0.8rem;
        color: var(--muted);
        margin-bottom: 0.9rem;
    }
    .card-meta span { display: flex; align-items: center; gap: 0.4rem; }
    .card-meta i { font-size: 0.85rem; }

    .card-curso-tag {
        font-size: 0.72rem;
        background: var(--gris-claro);
        color: var(--muted);
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        display: inline-block;
        margin-bottom: 0.9rem;
        font-weight: 500;
    }

    .card-footer-acciones {
        margin-top: auto;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* Estado badge */
    .estado-badge {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0.22rem 0.65rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .estado-DISPONIBLE { background: #d1fae5; color: #065f46; }
    .estado-EN_CURSO   { background: #fef3c7; color: #92400e; }
    .estado-TERMINADA  { background: #e5e7eb; color: #374151; }
    .estado-CANCELADA  { background: #fee2e2; color: #991b1b; }
    .estado-ACTIVO     { background: #d1fae5; color: #065f46; }
    .estado-PENDIENTE  { background: #dbeafe; color: #1e40af; }
    .estado-CERRADO    { background: #e5e7eb; color: #374151; }

    /* Botones de acción */
    .btn-accion {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.42rem 0.9rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: filter 0.15s, transform 0.15s;
        text-decoration: none;
    }
    .btn-accion:hover { filter: brightness(0.92); transform: scale(0.98); }
    .btn-accion:disabled { opacity: 0.5; cursor: not-allowed; transform: none; filter: none; }

    .btn-unirse    { background: var(--rojo); color: #fff; }
    .btn-inscrito  { background: #d1fae5; color: #065f46; }
    .btn-examen    { background: var(--verde); color: #fff; }
    .btn-resultados{ background: var(--gris-claro); color: var(--texto); border: 1px solid #ddd; }
    .btn-pronto    { background: #dbeafe; color: #1e40af; }

    /* ── CALENDARIO ── */
    .calendario-card {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    #calendar .fc-toolbar-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
    }
    #calendar .fc-button-primary {
        background: var(--texto) !important;
        border-color: var(--texto) !important;
        border-radius: 8px !important;
        font-size: 0.8rem;
        font-weight: 600;
    }
    #calendar .fc-button-primary:not(:disabled):hover {
        background: var(--rojo) !important;
        border-color: var(--rojo) !important;
    }
    #calendar .fc-event {
        border: none !important;
        border-radius: 6px !important;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 2px 5px;
    }
    #calendar .fc-daygrid-day-number,
    #calendar .fc-col-header-cell-cushion {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--texto);
        text-decoration: none;
    }
    #calendar .fc-day-today { background: #fff8f8 !important; }
    #calendar .fc-day-today .fc-daygrid-day-number {
        background: var(--rojo);
        color: #fff;
        border-radius: 999px;
        width: 26px; height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* ── LEYENDA CALENDARIO ── */
    .leyenda {
        display: flex;
        gap: 1.2rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        font-size: 0.78rem;
        font-weight: 500;
        color: var(--muted);
    }
    .leyenda span { display: flex; align-items: center; gap: 0.35rem; }
    .leyenda-dot { width: 10px; height: 10px; border-radius: 3px; }

    /* ── EMPTY STATE ── */
    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: var(--muted);
    }
    .empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; display: block; opacity: 0.4; }
    .empty-state p { font-size: 0.9rem; margin: 0; }

    .filtro-btn {
    cursor: pointer;
    border: 2px solid transparent;
    background: white;
    transition: all 0.2s;
    }

    .filtro-btn:hover {
        border-color: var(--rojo);
        color: var(--rojo);
    }

    .filtro-btn.active {
        border-color: var(--rojo);
        background: #fff0f2;
        color: var(--rojo);
        font-weight: 700;
    }

    .evento-card {
        transition: opacity 0.25s, transform 0.25s;
    }

    .evento-card.oculto {
        display: none !important;
    }

    .seccion-agenda {
        transition: all 0.25s;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

<div class="agenda-wrapper">

    {{-- HEADER --}}
    <div class="agenda-header">
        <h2>
            <i class="bi bi-calendar3 me-2" style="color:var(--rojo)"></i>
            Agenda de <span>{{ Auth::user()->nombre }}</span>
        </h2>
        <span class="agenda-rol-badge">{{ Auth::user()->rol }}</span>
    </div>

    {{-- STATS --}}
    @php
        $totalAsesorias = $asesorias->count();
        $misAsesorias   = $asesorias->where('ya_inscrito', '>', 0)->count();
        $totalExamenes  = $examenes->count();
        $examActivos    = $examenes->where('estado', 'ACTIVO')->count();
    @endphp
    <div class="stats-strip">
        <button class="stat-pill filtro-btn active" data-filtro="todo">
            <span class="dot" style="background:#555"></span>
            Todo
        </button>
        <button class="stat-pill filtro-btn" data-filtro="asesoria">
            <span class="dot" style="background:var(--rojo)"></span>
            {{ $totalAsesorias }} asesoría{{ $totalAsesorias != 1 ? 's' : '' }}
        </button>
        @if($rol === 'Estudiante')
            <button class="stat-pill filtro-btn" data-filtro="inscrito">
                <span class="dot" style="background:#10b981"></span>
                Inscrito en {{ $misAsesorias }}
            </button>
        @endif
        <button class="stat-pill filtro-btn" data-filtro="examen">
            <span class="dot" style="background:var(--azul)"></span>
            {{ $totalExamenes }} examen{{ $totalExamenes != 1 ? 'es' : '' }}
        </button>
        @if($examActivos > 0)
            <button class="stat-pill filtro-btn" data-filtro="activo">
                <span class="dot" style="background:var(--verde)"></span>
                {{ $examActivos }} activo{{ $examActivos != 1 ? 's' : '' }} ahora
            </button>
        @endif
    </div>

    {{-- ── ASESORÍAS ── --}}
    <div id="seccion-asesorias" class="seccion-agenda">
    <div class="section-label">
        <i class="bi bi-people-fill" style="color:var(--rojo)"></i>
        Asesorías
    </div>

    @if($asesorias->isEmpty())
        <div class="empty-state mb-4">
            <i class="bi bi-calendar-x"></i>
            <p>No hay asesorías disponibles en tus cursos.</p>
        </div>
    @else
        <div class="eventos-grid">
            @foreach($asesorias as $a)
            @php
                $yaInscrito = $a->ya_inscrito > 0;
                $bloqueado  = in_array($a->estado, ['TERMINADA', 'CANCELADA', 'EN_CURSO']);
            @endphp
            <div class="evento-card asesoria" data-inscrito="{{ $a->ya_inscrito }}" data-estado="{{ $a->estado ?? 'DISPONIBLE' }}">
                

                <div class="card-accent"></div>
                <div class="card-inner">
                    <div class="card-tipo">
                        <i class="bi bi-mortarboard-fill"></i> Asesoría
                        <span class="estado-badge estado-{{ $a->estado ?? 'DISPONIBLE' }}" style="margin-left:auto">
                            {{ $a->estado ?? 'DISPONIBLE' }}
                        </span>
                    </div>
                    <div class="card-titulo">{{ $a->nombre_evento }}</div>
                    <div class="card-meta">
                        @if($a->fecha_asesoria)
                        <span>
                            <i class="bi bi-calendar-event"></i>
                            {{ \Carbon\Carbon::parse($a->fecha_asesoria)->translatedFormat('d \d\e F, Y') }}
                        </span>
                        @endif
                        @if($a->hora_inicio)
                        <span>
                            <i class="bi bi-clock"></i>
                            {{ \Carbon\Carbon::parse($a->hora_inicio)->format('h:i A') }}
                            @if($a->hora_fin)
                                — {{ \Carbon\Carbon::parse($a->hora_fin)->format('h:i A') }}
                            @endif
                        </span>
                        @endif
                        @if($a->lugar)
                        <span>
                            <i class="bi bi-geo-alt-fill" style="color:var(--rojo)"></i>
                            {{ $a->lugar }}
                        </span>
                        @endif
                    </div>
                    <span class="card-curso-tag">
                        <i class="bi bi-book me-1"></i>{{ $a->nombre_curso }}
                    </span>

                    <div class="card-footer-acciones">
                        @if($rol === 'Estudiante')
                            @if($yaInscrito)
                                <form action="{{ route('asesorias.cancelar', $a->id_evento) }}" method="POST" class="m-0 form-cancelar-agenda">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-accion btn-inscrito" @if($bloqueado) disabled @endif>
                                        <i class="bi bi-check-circle-fill"></i> Inscrito
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('asesorias.unirse', $a->id_evento) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn-accion btn-unirse" @if($bloqueado) disabled @endif>
                                        <i class="bi bi-box-arrow-in-right"></i>
                                        {{ $bloqueado ? 'No disponible' : 'Unirse' }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <span class="btn-accion btn-pronto" style="pointer-events:none; background:#f0f0f0; color:#555;">
                                <i class="bi bi-people-fill text-danger me-1"></i>
                                {{ $a->total_asistentes }} asistente(s)
                            </span>
                            <a href="{{ route('curso.eventos', $a->id_curso) }}" class="btn-accion btn-resultados">
                                <i class="bi bi-arrow-right"></i> Ver curso
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
    </div>

    {{-- ── EXÁMENES ── --}}
    <div id="seccion-examenes" class="seccion-agenda">
    <div class="section-label">
        <i class="bi bi-file-earmark-check-fill" style="color:var(--azul)"></i>
        Exámenes
    </div>

    @if($examenes->isEmpty())
        <div class="empty-state mb-4">
            <i class="bi bi-file-earmark-x"></i>
            <p>No hay exámenes publicados en tus cursos.</p>
        </div>
    @else
        <div class="eventos-grid">
            @foreach($examenes as $e)
            <div class="evento-card examen" data-estado="{{ $e->estado }}">
                <div class="card-accent"></div>
                <div class="card-inner">
                    <div class="card-tipo">
                        <i class="bi bi-pencil-square"></i> Examen
                        <span class="estado-badge estado-{{ $e->estado }}" style="margin-left:auto">
                            {{ $e->estado }}
                        </span>
                    </div>
                    <div class="card-titulo">{{ $e->nombre_evento }}</div>
                    <div class="card-meta">
                        @if($e->fecha_examen)
                        <span>
                            <i class="bi bi-calendar-event"></i>
                            {{ \Carbon\Carbon::parse($e->fecha_examen)->translatedFormat('d \d\e F, Y') }}
                        </span>
                        @endif
                        @if($e->hora_inicio)
                        <span>
                            <i class="bi bi-clock"></i>
                            {{ \Carbon\Carbon::parse($e->hora_inicio)->format('h:i A') }}
                            @if($e->hora_fin)
                                — {{ \Carbon\Carbon::parse($e->hora_fin)->format('h:i A') }}
                            @endif
                        </span>
                        @endif
                        <span>
                            <i class="bi bi-arrow-repeat" style="color:var(--azul)"></i>
                            {{ $e->intentos_hechos }}/{{ $e->oportunidades }} oportunidades usadas
                        </span>
                    </div>
                    <span class="card-curso-tag">
                        <i class="bi bi-book me-1"></i>{{ $e->nombre_curso }}
                    </span>

                    <div class="card-footer-acciones">
                        @if($rol === 'Estudiante')
                            @if($e->estado === 'ACTIVO' && $e->intentos_hechos < $e->oportunidades)
                                <a href="{{ route('examen.inicio', $e->id_cuestionario) }}" class="btn-accion btn-examen">
                                    <i class="bi bi-pencil-square"></i> Iniciar examen
                                </a>
                            @elseif($e->estado === 'PENDIENTE')
                                <span class="btn-accion btn-pronto" style="pointer-events:none">
                                    <i class="bi bi-clock"></i> Próximamente
                                </span>
                            @else
                                <a href="{{ route('examen.misResultados', $e->id_cuestionario) }}" class="btn-accion btn-resultados">
                                    <i class="bi bi-bar-chart-fill"></i> Ver mis resultados
                                </a>
                            @endif
                        @else
                            <span class="btn-accion btn-pronto" style="pointer-events:none; background:#f0f0f0; color:#555;">
                                <i class="bi bi-people-fill" style="color:#1a3a5c;"></i>
                                {{ $e->alumnos_completaron }} completaron
                            </span>
                            <a href="{{ route('examenes.resultados', $e->id_cuestionario) }}" class="btn-accion btn-examen">
                                <i class="bi bi-bar-chart-fill"></i> Resultados
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
    </div>

    {{-- ── CALENDARIO ── --}}
    <div class="section-label">
        <i class="bi bi-calendar-week-fill" style="color:var(--texto)"></i>
        Calendario de actividades
    </div>

    <div class="calendario-card">
        <div class="leyenda">
            <span><span class="leyenda-dot" style="background:var(--rojo)"></span> Asesoría disponible</span>
            <span><span class="leyenda-dot" style="background:#ffc107"></span> En curso</span>
            <span><span class="leyenda-dot" style="background:var(--azul)"></span> Examen pendiente</span>
            <span><span class="leyenda-dot" style="background:var(--verde)"></span> Examen activo</span>
            <span><span class="leyenda-dot" style="background:#6c757d"></span> Finalizado</span>
        </div>
        <div id="calendar"></div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/locales/es.global.min.js"></script>
<script>
var todosEventos = @json($eventosCalendario);
var calendarInstance = null;

document.addEventListener('DOMContentLoaded', function () {
    var eventos = @json($eventosCalendario);
    var rol = @json($rol);

    calendarInstance = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        locale: 'es',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        buttonText: { listWeek: 'Lista' },
        events: eventos,
        eventClick: function(info) {
            var p = info.event.extendedProps;
            var titulo = info.event.title;

            document.getElementById('modal-titulo').textContent = titulo;
            document.getElementById('modal-curso').textContent = p.curso ?? '—';
            document.getElementById('modal-fecha').textContent = p.fecha ?? '—';
            document.getElementById('modal-hora').textContent =
                (p.hora_inicio ? p.hora_inicio.substring(0,5) : '—') +
                (p.hora_fin ? ' — ' + p.hora_fin.substring(0,5) : '');
            document.getElementById('modal-estado').textContent = p.estado ?? '—';

            var filaLugar = document.getElementById('fila-lugar');
            var filaAsistentes = document.getElementById('fila-asistentes');
            var filaIntentos = document.getElementById('fila-intentos');
            var tipoIcon = document.getElementById('modal-tipo-icon');

            if (p.tipo === 'asesoria') {
                tipoIcon.innerHTML = '<i class="bi bi-mortarboard-fill me-1"></i> Asesoría';
                tipoIcon.className = 'badge text-white fw-bold';
                tipoIcon.style.background = '#8C001A';
                filaLugar.style.display = 'flex';
                document.getElementById('modal-lugar').textContent = p.lugar ?? 'No especificado';
                filaAsistentes.style.display = 'flex';
                document.getElementById('modal-asistentes').textContent = p.total_asistentes ?? 0;
                filaIntentos.style.display = 'none';
            } else {
                tipoIcon.innerHTML = '<i class="bi bi-file-earmark-check me-1"></i> Examen';
                tipoIcon.className = 'badge text-white fw-bold';
                tipoIcon.style.background = '#1a3a5c';
                filaLugar.style.display = 'none';
                filaAsistentes.style.display = 'none';
                filaIntentos.style.display = 'flex';
                document.getElementById('modal-intentos').textContent =
                    (p.intentos_hechos ?? 0) + ' / ' + (p.oportunidades ?? 1);
            }

            // Mostrar modal Bootstrap — destruir instancia previa primero
            var modalEl = document.getElementById('modalCalendario');
            var modalExistente = bootstrap.Modal.getInstance(modalEl);
            if (modalExistente) {
                modalExistente.dispose();
            }

            // Limpiar backdrops huérfanos
            document.querySelectorAll('.modal-backdrop').forEach(function(el) {
                el.remove();
            });
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
            document.body.style.removeProperty('overflow');

            var modal = new bootstrap.Modal(modalEl, { backdrop: true, keyboard: true });
            modal.show();
        },
        dayMaxEvents: 3,
    });
    calendarInstance.render();
});

    // =============================================
    // FILTROS DE AGENDA
    // =============================================
    const filtrosBtns = document.querySelectorAll('.filtro-btn');
    const seccionAsesorias = document.getElementById('seccion-asesorias');
    const seccionExamenes = document.getElementById('seccion-examenes');
    const tarjetasAsesoria = document.querySelectorAll('.evento-card.asesoria');
    const tarjetasExamen = document.querySelectorAll('.evento-card.examen');

    filtrosBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Marcar activo
            filtrosBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filtro = this.dataset.filtro;

            // Resetear todo
            tarjetasAsesoria.forEach(t => t.classList.remove('oculto'));
            tarjetasExamen.forEach(t => t.classList.remove('oculto'));
            seccionAsesorias.style.display = '';
            seccionExamenes.style.display = '';

            if (filtro === 'asesoria') {
                seccionExamenes.style.display = 'none';
                // Filtrar calendario
                actualizarCalendario(['asesoria']);

            } else if (filtro === 'examen') {
                seccionAsesorias.style.display = 'none';
                actualizarCalendario(['examen']);

            } else if (filtro === 'inscrito') {
                // Solo asesorías donde ya_inscrito > 0
                seccionExamenes.style.display = 'none';
                tarjetasAsesoria.forEach(t => {
                    if (!t.dataset.inscrito || t.dataset.inscrito === '0') {
                        t.classList.add('oculto');
                    }
                });
                actualizarCalendario(['asesoria'], true);

            } else if (filtro === 'activo') {
                // Solo exámenes activos
                seccionAsesorias.style.display = 'none';
                tarjetasExamen.forEach(t => {
                    if (t.dataset.estado !== 'ACTIVO') {
                        t.classList.add('oculto');
                    }
                });
                actualizarCalendario(['examen'], false, 'ACTIVO');

            } else {
                // Todo
                actualizarCalendario(['asesoria', 'examen']);
            }
        });
    });

    // Actualizar eventos del calendario según filtro
    var todosEventos = @json($eventosCalendario);
    var calendarInstance = null;

    function actualizarCalendario(tipos, soloInscritos = false, soloEstado = null) {
        if (!calendarInstance) return;

        var eventosFiltrados = todosEventos.filter(function(e) {
            if (!tipos.includes(e.extendedProps.tipo)) return false;
            if (soloInscritos && !e.extendedProps.ya_inscrito) return false;
            if (soloEstado && e.extendedProps.estado !== soloEstado) return false;
            return true;
        });

        calendarInstance.removeAllEvents();
        calendarInstance.addEventSource(eventosFiltrados);
    }
</script>

{{-- MODAL DETALLE EVENTO CALENDARIO --}}
<div class="modal fade" id="modalCalendario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-calendar-event-fill text-danger me-2"></i>
                    Detalle del Evento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">

                {{-- Tipo --}}
                <div class="mb-3">
                    <span id="modal-tipo-icon" class="badge fw-bold"></span>
                </div>

                {{-- Título --}}
                <h5 id="modal-titulo" class="fw-bold text-dark mb-3"></h5>

                {{-- Datos --}}
                <div class="d-flex flex-column gap-2">

                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-book text-danger" style="width:20px;"></i>
                        <span class="text-muted" style="font-size:0.8rem; text-transform:uppercase; font-weight:700; width:80px;">Curso</span>
                        <span id="modal-curso" class="fw-semibold"></span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-calendar3 text-danger" style="width:20px;"></i>
                        <span class="text-muted" style="font-size:0.8rem; text-transform:uppercase; font-weight:700; width:80px;">Fecha</span>
                        <span id="modal-fecha" class="fw-semibold"></span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-clock text-danger" style="width:20px;"></i>
                        <span class="text-muted" style="font-size:0.8rem; text-transform:uppercase; font-weight:700; width:80px;">Horario</span>
                        <span id="modal-hora" class="fw-semibold"></span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-circle-fill text-danger" style="width:20px; font-size:0.5rem;"></i>
                        <span class="text-muted" style="font-size:0.8rem; text-transform:uppercase; font-weight:700; width:80px;">Estado</span>
                        <span id="modal-estado" class="fw-semibold"></span>
                    </div>

                    <div id="fila-lugar" class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-danger" style="width:20px;"></i>
                        <span class="text-muted" style="font-size:0.8rem; text-transform:uppercase; font-weight:700; width:80px;">Lugar</span>
                        <span id="modal-lugar" class="fw-semibold"></span>
                    </div>

                    <div id="fila-asistentes" class="d-flex align-items-center gap-2">
                        <i class="bi bi-people-fill text-danger" style="width:20px;"></i>
                        <span class="text-muted" style="font-size:0.8rem; text-transform:uppercase; font-weight:700; width:80px;">Asistentes</span>
                        <span id="modal-asistentes" class="fw-semibold"></span>
                    </div>

                    <div id="fila-intentos" class="d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-repeat" style="color:#1a3a5c; width:20px;"></i>
                        <span class="text-muted" style="font-size:0.8rem; text-transform:uppercase; font-weight:700; width:80px;">Intentos</span>
                        <span id="modal-intentos" class="fw-semibold"></span>
                    </div>

                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm fw-bold px-3"
                        data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: @json(session('success')),
        confirmButtonColor: '#8C001A',
        timer: 3000,
        timerProgressBar: true,
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: @json(session('error')),
        confirmButtonColor: '#8C001A',
    });
@endif

document.querySelectorAll('.form-cancelar-agenda').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Cancelar asistencia?',
            text: 'Se eliminará tu registro en esta asesoría.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#8C001A',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>

@endsection