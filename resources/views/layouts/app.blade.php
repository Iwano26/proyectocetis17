<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asesorías - CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    {{-- Hojas de estilo separadas --}}
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

    @include('components.sidebar')
    @include('components.navbar')

    <div class="page-wrapper">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const perfilToggle = document.getElementById('perfilToggle');
        const perfilMenu   = document.getElementById('perfilMenu');
        if (perfilToggle) {
            perfilToggle.addEventListener('click', e => { e.stopPropagation(); perfilMenu.classList.toggle('show'); });
            document.addEventListener('click', () => perfilMenu.classList.remove('show'));
            perfilMenu.addEventListener('click', e => e.stopPropagation());
        }
    </script>
</body>
</html>