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
        <div class="stat-pill">
            <span class="dot" style="background:var(--rojo)"></span>
            {{ $totalAsesorias }} asesoría{{ $totalAsesorias != 1 ? 's' : '' }} disponible{{ $totalAsesorias != 1 ? 's' : '' }}
        </div>
        <div class="stat-pill">
            <span class="dot" style="background:#10b981"></span>
            Inscrito en {{ $misAsesorias }}
        </div>
        <div class="stat-pill">
            <span class="dot" style="background:var(--azul)"></span>
            {{ $totalExamenes }} examen{{ $totalExamenes != 1 ? 'es' : '' }}
        </div>
        @if($examActivos > 0)
        <div class="stat-pill">
            <span class="dot" style="background:var(--verde)"></span>
            {{ $examActivos }} activo{{ $examActivos != 1 ? 's' : '' }} ahora
        </div>
        @endif
    </div>

    {{-- ── ASESORÍAS ── --}}
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
            <div class="evento-card asesoria">
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
                        @if($yaInscrito)
                            {{-- Cancelar inscripción --}}
                            <form action="{{ route('asesorias.cancelar', $a->id_evento) }}" method="POST" class="m-0"
                                  onsubmit="return confirm('¿Cancelar tu asistencia?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-accion btn-inscrito" @if($bloqueado) disabled @endif>
                                    <i class="bi bi-check-circle-fill"></i> Inscrito
                                </button>
                            </form>
                        @else
                            {{-- Unirse --}}
                            <form action="{{ route('asesorias.unirse', $a->id_evento) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn-accion btn-unirse" @if($bloqueado) disabled @endif>
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    {{ $bloqueado ? 'No disponible' : 'Unirse' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- ── EXÁMENES ── --}}
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
            <div class="evento-card examen">
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
                        @if($e->estado === 'ACTIVO' && $e->intentos_hechos < $e->oportunidades)
                            <a href="{{ route('examen.inicio', $e->id_cuestionario) }}"
                               class="btn-accion btn-examen">
                                <i class="bi bi-pencil-square"></i> Iniciar examen
                            </a>
                        @elseif($e->estado === 'PENDIENTE')
                            <span class="btn-accion btn-pronto" style="pointer-events:none">
                                <i class="bi bi-clock"></i> Próximamente
                            </span>
                        @elseif($e->estado === 'CERRADO' || $e->intentos_hechos >= $e->oportunidades)
                            <a href="{{ route('examen.misResultados', $e->id_cuestionario) }}"
                               class="btn-accion btn-resultados">
                                <i class="bi bi-bar-chart-fill"></i> Ver mis resultados
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

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
document.addEventListener('DOMContentLoaded', function () {
    var eventos = @json($eventosCalendario);

    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
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
            var lugar = p.lugar ? '\nLugar: ' + p.lugar : '';
            alert('📅 ' + info.event.title + lugar);
        },
        dayMaxEvents: 3,
    });
    calendar.render();
});
</script>
@endsection