<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asesorías - CETIS 17</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --cetis-rojo: #8C001A;
            --cetis-rojo-oscuro: #6b0014;
            --cetis-gris: #f0f2f5;
            --cetis-texto: #1a1a2e;
            --topbar-h: 56px;
            --sidebar-w: 280px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Georgia', 'Times New Roman', serif; background: var(--cetis-gris); color: var(--cetis-texto); }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed; top: 0; left: 0; right: 0; height: var(--topbar-h);
            background: white; border-bottom: 1px solid #ddd;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 16px; z-index: 1050;
            box-shadow: 0 1px 6px rgba(0,0,0,0.1);
        }
        .topbar-left { display: flex; align-items: center; gap: 10px; }
        .topbar-logo {
            display: flex; align-items: center; gap: 10px; text-decoration: none;
        }
        .topbar-logo-img {
            width: 36px; height: 36px; border-radius: 8px;
            object-fit: cover; display: block;
        }
        .topbar-logo-placeholder {
            width: 36px; height: 36px; background: var(--cetis-rojo); color: white;
            font-weight: 900; font-size: 0.75rem; display: flex; align-items: center;
            justify-content: center; border-radius: 8px; font-family: 'Georgia', serif;
            flex-shrink: 0;
        }
        .topbar-brand-text { font-size: 0.95rem; font-weight: 700; color: var(--cetis-texto); font-family: 'Georgia', serif; }
        .topbar-brand-text span { color: var(--cetis-rojo); }

        /* Búsqueda central estilo FB */
        .topbar-search {
            flex: 1; max-width: 240px; margin: 0 16px;
        }
        .topbar-search input {
            width: 100%; background: var(--cetis-gris); border: none; border-radius: 20px;
            padding: 8px 16px 8px 36px; font-size: 0.875rem; outline: none;
            font-family: 'Georgia', serif; color: var(--cetis-texto);
        }
        .topbar-search { position: relative; }
        .topbar-search i {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #888; font-size: 0.9rem;
        }

        /* Nav central (páginas) */
        .topbar-nav { display: flex; align-items: center; gap: 4px; }
        .topbar-nav-link {
            display: flex; align-items: center; justify-content: center;
            width: 100px; height: 44px; border-radius: 8px; text-decoration: none;
            color: #888; font-size: 1.4rem; transition: all 0.15s; position: relative;
        }
        .topbar-nav-link:hover { background: var(--cetis-gris); color: var(--cetis-rojo); }
        .topbar-nav-link.active { color: var(--cetis-rojo); }
        .topbar-nav-link.active::after {
            content: ''; position: absolute; bottom: -1px; left: 0; right: 0;
            height: 3px; background: var(--cetis-rojo); border-radius: 2px 2px 0 0;
        }

        /* Acciones derecha */
        .topbar-right { display: flex; align-items: center; gap: 8px; }
        .topbar-action-btn {
            width: 36px; height: 36px; border-radius: 50%; border: none;
            background: var(--cetis-gris); color: var(--cetis-texto); font-size: 1rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: background 0.15s; text-decoration: none;
        }
        .topbar-action-btn:hover { background: #ddd; color: var(--cetis-rojo); }

        /* Dropdown perfil */
        .perfil-dropdown { position: relative; }
        .perfil-btn {
            display: flex; align-items: center; gap: 8px; background: #fff0f2;
            color: var(--cetis-rojo); border: none; padding: 6px 12px 6px 6px;
            border-radius: 20px; font-weight: 700; font-size: 0.8rem; cursor: pointer;
            transition: background 0.2s; font-family: 'Georgia', serif;
        }
        .perfil-btn:hover { background: #ffdde2; }
        .perfil-btn .avatar {
            width: 28px; height: 28px; background: var(--cetis-rojo); color: white;
            border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;
        }
        .dropdown-menu-cetis {
            position: absolute; right: 0; top: calc(100% + 8px); background: white;
            border: 1px solid #e5e7eb; border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12); min-width: 220px; padding: 6px;
            display: none; z-index: 2000;
        }
        .dropdown-menu-cetis.show { display: block; }
        .dropdown-header-cetis { padding: 10px 12px 8px; border-bottom: 1px solid #f0f0f0; margin-bottom: 4px; }
        .dropdown-header-cetis .nombre { font-weight: 700; font-size: 0.9rem; color: var(--cetis-texto); }
        .dropdown-header-cetis .rol { font-size: 0.72rem; color: var(--cetis-rojo); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .dropdown-item-cetis {
            display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 7px;
            color: #333; text-decoration: none; font-size: 0.875rem; font-weight: 500;
            transition: background 0.15s; cursor: pointer; border: none; background: none; width: 100%; text-align: left; font-family: 'Georgia', serif;
        }
        .dropdown-item-cetis:hover { background: var(--cetis-gris); color: var(--cetis-rojo); }
        .dropdown-item-cetis.danger:hover { background: #fff0f0; color: #c0001a; }
        .dropdown-item-cetis i { font-size: 1rem; opacity: 0.7; }

        /* ── SIDEBAR ── */
        .offcanvas-cetis {
            width: var(--sidebar-w) !important; border-right: none !important;
            box-shadow: 2px 0 20px rgba(0,0,0,0.1);
        }
        .offcanvas-header-cetis {
            background: var(--cetis-rojo); padding: 16px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .sidebar-user { display: flex; align-items: center; gap: 10px; }
        .sidebar-avatar {
            width: 38px; height: 38px; background: rgba(255,255,255,0.2); border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: white;
        }
        .sidebar-nombre { font-weight: 700; font-size: 0.9rem; color: white; font-family: 'Georgia', serif; }
        .sidebar-rol { font-size: 0.65rem; color: rgba(255,255,255,0.7); font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; margin-top: 1px; }
        .sidebar-close-btn {
            background: rgba(255,255,255,0.15); border: none; color: white; width: 30px; height: 30px;
            border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s;
        }
        .sidebar-close-btn:hover { background: rgba(255,255,255,0.28); }
        .offcanvas-body-cetis { padding: 16px 12px; flex: 1; overflow-y: auto; }
        .sidebar-section-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; color: #aaa; margin-bottom: 4px; padding-left: 10px; }
        .sidebar-section-label.danger { color: var(--cetis-rojo); }
        .sidebar-nav { display: flex; flex-direction: column; gap: 2px; margin-bottom: 8px; }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px; padding: 9px 12px; border-radius: 8px;
            color: #333; text-decoration: none; font-size: 0.875rem; font-weight: 600; font-family: 'Georgia', serif; transition: all 0.18s;
        }
        .sidebar-link i { font-size: 1rem; color: var(--cetis-rojo); opacity: 0.75; width: 18px; text-align: center; }
        .sidebar-link:hover { background: #fff0f2; color: var(--cetis-rojo); }
        .sidebar-link:hover i { opacity: 1; }
        .sidebar-link.active { background: var(--cetis-rojo); color: white; }
        .sidebar-link.active i { color: white; opacity: 1; }
        .sidebar-divider { border-color: #eee; margin: 14px 0; }
        .offcanvas-footer-cetis { padding: 12px; border-top: 1px solid #eee; }
        .sidebar-logout-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%;
            padding: 9px; background: transparent; border: 2px solid var(--cetis-rojo);
            color: var(--cetis-rojo); border-radius: 8px; font-weight: 700; font-size: 0.875rem;
            font-family: 'Georgia', serif; cursor: pointer; transition: all 0.2s;
        }
        .sidebar-logout-btn:hover { background: var(--cetis-rojo); color: white; }

        /* ── BOTÓN HAMBURGUESA ── */
        #menu-toggle-btn {
            background: none; border: none; color: var(--cetis-texto); font-size: 1.3rem;
            width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center;
            justify-content: center; cursor: pointer; transition: background 0.15s;
        }
        #menu-toggle-btn:hover { background: var(--cetis-gris); color: var(--cetis-rojo); }

        /* ── LAYOUT PRINCIPAL ── */
        .page-wrapper { padding-top: var(--topbar-h); min-height: 100vh; }
    </style>
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