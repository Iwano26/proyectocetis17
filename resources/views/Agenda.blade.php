<?php
/**
 * --- DATOS DESDE LA BASE DE DATOS ---
 */

// Ejemplo de arreglo que vendría de tu BD para la LISTA
$proximas_asesorias = [
    [
        "id" => 1,
        "titulo" => "Cálculo Integral - Derivadas",
        "fecha_texto" => "20 de Octubre, 4:00 PM",
        "lugar" => "Cubículo 4 - Edificio A"
    ]
];

// Ejemplo de arreglo para el CALENDARIO (Formato ISO para JS)
$eventos_calendario = [
    [
        "title" => "Asesoría Cálculo",
        "start" => "2026-01-20T16:00:00"
    ],
    [
        "title" => "Programación Web",
        "start" => "2026-01-22T10:00:00"
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda | CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    
    <style>
        :root { 
            --cetis-rojo: #8C001A; 
            --cetis-primary: #8C001A; 
        }
        body { background-color: #f8f9fa; }

        /* Estilos del Menú Principal */
        #menu-toggle-btn { 
            position: fixed; top: 15px; left: 20px; z-index: 1040; 
            background-color: white; color: var(--cetis-rojo); 
            border: 2px solid var(--cetis-rojo); padding: 5px 12px; 
            border-radius: 8px; font-size: 1.5rem; cursor: pointer; 
            transition: all 0.3s;
        }
        #menu-toggle-btn:hover { background-color: var(--cetis-primary); color: white; }

        .navbar { background-color: white !important; border-bottom: 1px solid #dee2e6; min-height: 70px; padding-left: 80px; }
        
        /* Estilos Offcanvas */
        .offcanvas-header { background-color: var(--cetis-primary); color: white; }
        .offcanvas-header .btn-close { filter: invert(1); }
        .offcanvas-body .nav-link { color: #333; font-weight: 500; padding: 12px 15px; border-radius: 8px; transition: all 0.2s; }
        .offcanvas-body .nav-link:hover, .offcanvas-body .nav-link.active { background-color: var(--cetis-primary); color: white !important; }
        .offcanvas-body .nav-link.active {
            background-color: #A61E34; /* Un rojo un poco más claro al pasar el mouse */
            border-color: #A61E34;
            color: white;
        }

        /* Estilo Lista Arriba */
        .tarjeta-asesoria {
            background-color: white; border-left: 5px solid var(--cetis-rojo);
            border-radius: 10px; padding: 15px; margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Estilo Calendario Abajo */
        #calendar { background-color: white; padding: 20px; border-radius: 12px; border: 1px solid #ddd; margin-top: 20px; margin-bottom: 50px; }
        .fc-event { background-color: #fff !important; border: 2px solid var(--cetis-rojo) !important; color: #000 !important; font-weight: bold !important; }
        .fc-toolbar-title { color: var(--cetis-rojo); font-weight: bold; }
    </style>
</head>
<body>

    <button id="menu-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
        <i class="bi bi-list"></i>
    </button>
    
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-danger" href="#">Sistema de Asesorías</a>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="sidebarMenuLabel">
                <i class="bi bi-person-fill me-2"></i> Menú de Usuario
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <p class="text-muted small mb-4">Módulos</p>
            <div class="nav flex-column nav-pills">
                <!-- Opción: Principal -->
                <a class="nav-link" href="/principal">
                    <i class="bi bi-calendar-event me-3"></i> Principal
                </a>
                <!-- Opción: Agenda -->
                <a class="nav-link active" href="/agenda">
                    <i class="bi bi-calendar-event me-3"></i> Agenda
                </a>
                <!-- Opción: Cursos -->
                <a class="nav-link" href="/buscarcurso">
                    <i class="bi bi-journal-bookmark me-3"></i> Cursos
                </a>
                <!-- Opción: Biblioteca -->
                <a class="nav-link" href="/biblioteca">
                    <i class="bi bi-archive me-3"></i> Biblioteca
                </a>
            </div>
            
            <hr class="my-4">
            
            <a class="nav-link btn btn-outline-secondary mt-3 text-start" href="/login">
                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
            </a>
        </div>
    </div>

    <div class="container mt-4 px-4">
        <h2 class="fw-bold mb-4">Mi Agenda</h2>

        <h5 class="text-secondary fw-bold mb-3">Próximas Asesorías</h5>
        
        <?php foreach($proximas_asesorias as $asesoria): ?>
        <div class="tarjeta-asesoria">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h5 class="fw-bold mb-1"><?php echo $asesoria['titulo']; ?></h5>
                    <p class="mb-0 text-muted small"><i class="bi bi-calendar-event me-2"></i><?php echo $asesoria['fecha_texto']; ?></p>
                    <p class="mb-0 text-muted small"><i class="bi bi-geo-alt me-2"></i><?php echo $asesoria['lugar']; ?></p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <a href="ver.php?id=<?php echo $asesoria['id']; ?>" class="btn btn-primary btn-sm mx-1">VER</a>
                    <button class="btn btn-success btn-sm mx-1">ASISTIR</button>
                    <button class="btn btn-danger btn-sm mx-1">CANCELAR</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <h5 class="text-secondary fw-bold mt-5 mb-3">Calendario Mensual</h5>
        <div id="calendar"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale: 'es',
          buttonText: { today: 'Hoy' },
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: '' 
          },
          events: <?php echo json_encode($eventos_calendario); ?>
        });
        calendar.render();
      });
    </script>
</body>
</html>