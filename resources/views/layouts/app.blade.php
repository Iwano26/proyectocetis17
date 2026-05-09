<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asesorías - CETIS 17</title>
    
    <!-- Bootstrap e Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Tus estilos personalizados (Copia y pega aquí el <style> que me pasaste) -->
    <style>
        :root { --cetis-rojo: #8C001A; --cetis-primary: #8C001A; }
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        #menu-toggle-btn { position: fixed; top: 15px; left: 20px; z-index: 1040; background-color: white; color: var(--cetis-rojo); border: 2px solid var(--cetis-rojo); padding: 5px 12px; border-radius: 8px; font-size: 1.5rem; cursor: pointer; transition: all 0.3s; }
        .navbar { background-color: white !important; border-bottom: 1px solid #dee2e6; min-height: 70px; padding-left: 80px; }
        .offcanvas-header { background-color: var(--cetis-primary); color: white; }
        .offcanvas-body .nav-link.active { background-color: var(--cetis-primary); color: white !important; }
        .tarjeta-curso-interna { background-color: white; border: 2px solid var(--cetis-rojo); border-radius: 12px; padding: 20px; margin-bottom: 15px; }
        /* Añadimos este margen para que no choque el contenido con la navbar */
        .contenido-principal { margin-top: 30px; }
    </style>
</head>
<body>

    <!-- Llamamos a los componentes que ya creaste -->
    @include('components.sidebar')
    @include('components.navbar')

    <!-- Aquí se inyectará el código de cada vista -->
    <div class="container-fluid contenido-principal">
        @yield('content')
    </div>

    <!-- Scripts necesarios para que el menú (Offcanvas) funcione -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
