<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asesorías - CETIS 17</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght=100..900&display=swap" rel="stylesheet">
    
    {{-- La diagonal invertida fuerza a buscar desde la raíz publica sin importar la subruta --}}
    <link rel="stylesheet" href="{{ asset('/css/menuiz.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/home.css') }}">

    <style>
        /* ========== CONFIGURACIÓN DE COLORES PARA DIALOGFLOW MESSENGER ========== */
        df-messenger {
            /* 1. Color general de la barra de título (Arriba) */
            --df-messenger-titlebar-background: #8C001A;
            
            /* 2. Color del botón flotante circular (Abajo a la derecha) */
            --df-messenger-button-titlebar-color: #8C001A;
            --df-messenger-chat-bubble-background: #8C001A;
            
            /* Color secundario por si acaso */
            --df-messenger-brand-color: #8C001A; 
            
            /* Color de fondo del globo del bot (tono rojo muy suave) */
            --df-messenger-bot-message: #F9E6E9; 
            
            /* Forzar el color del texto del bot para que sea oscuro y legible */
            --df-messenger-font-color: #212529; 
            
            /* Globo de los mensajes que escribe el estudiante */
            --df-messenger-user-message: #e9ecef; 
            
            /* Altura de la ventana del chat */
            --df-messenger-chat-window-height: 500px; 
            
            z-index: 9999;
        }
    </style>
</head>
<body>

    @include('components.sidebar')
    @include('components.navbar')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="page-wrapper">
        @yield('content')
    </div>

    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger
      intent="WELCOME"
      chat-title="Asistente CETIS 17"
      agent-id="ad7c3d22-63b7-4883-abaa-b4767f0a035b"
      language-code="es"
    ></df-messenger>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const perfilToggle = document.getElementById('perfilToggle');
            const perfilMenu   = document.getElementById('perfilMenu');
            
            if (perfilToggle && perfilMenu) {
                perfilToggle.addEventListener('click', e => { 
                    e.stopPropagation(); 
                    perfilMenu.classList.toggle('show'); 
                });
                
                document.addEventListener('click', () => {
                    perfilMenu.classList.remove('show');
                });
                
                perfilMenu.addEventListener('click', e => {
                    e.stopPropagation();
                });
            }
        });
    </script>
</body>
</html>