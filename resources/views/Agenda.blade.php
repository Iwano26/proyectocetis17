@php
// Estos datos normalmente vendrían de tu Controlador en Laravel
$proximas_asesorias = [
    [
        "id" => 1,
        "titulo" => "Cálculo Integral - Derivadas",
        "fecha_texto" => "20 de Octubre, 4:00 PM",
        "lugar" => "Cubículo 4 - Edificio A"
    ]
];

$eventos_calendario = [
    ["title" => "Asesoría Cálculo", "start" => "2026-01-20T16:00:00"],
    ["title" => "Programación Web", "start" => "2026-01-22T10:00:00"]
];
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda | CETIS 17</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/agenda.css') }}">
</head>
<body>

    <x-sidebar />
    
    <nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm" style="padding-left: 70px;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-cetis">Sistema de Asesorías</a>
        </div>
    </nav>

    <div class="container mt-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-calendar3 fs-2 me-3 text-cetis"></i>
            <h2 class="fw-bold mb-0">Agenda de {{session('bienvenido') }}</h2>
        </div>
        <span class="badge bg-danger">Sesión activa como: {{ Auth::user()->rol }}</span>
    </div>
    </div>

    <div class="container mt-4 px-4">
        <div class="d-flex align-items-center mb-4">                        
        </div>

        <h5 class="text-secondary fw-bold mb-3 mt-4">Próximas Asesorías Agendadas</h5>
        
        @foreach($proximas_asesorias as $asesoria)
        <div class="tarjeta-asesoria">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h5 class="fw-bold mb-1">{{ $asesoria['titulo'] }}</h5>
                    <p class="mb-0 text-muted small"><i class="bi bi-calendar-event me-2"></i>{{ $asesoria['fecha_texto'] }}</p>
                    <p class="mb-0 text-muted small"><i class="bi bi-geo-alt me-2"></i>{{ $asesoria['lugar'] }}</p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <a href="/ver-asesoria/{{ $asesoria['id'] }}" class="btn btn-primary btn-sm px-3 shadow-sm">VER DETALLES</a>
                    <button class="btn btn-success btn-sm px-3 shadow-sm">ASISTIR</button>
                    <button class="btn btn-outline-danger btn-sm px-3">CANCELAR</button>
                </div>
            </div>
        </div>
        @endforeach

        <h5 class="text-secondary fw-bold mt-5 mb-3">Calendario de Actividades</h5>
        <div id="calendar"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

   <script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    // Validamos que los datos existan para evitar errores de JS
    var datosEventos = @json($eventos_calendario ?? []); 

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'es',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: '' 
      },
      events: datosEventos
    });
    calendar.render();
  });
</script>
</body>
</html>