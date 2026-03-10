<style>
    /* Estilos específicos para la vista de Perfil */
    :root {
        --cetis-primary: #8C001A;
        --cetis-secondary: #004A77;
        --cetis-light: #f8f9fa;
    }

    .profile-container {
        padding-top: 50px;
        font-family: 'Inter', sans-serif;
        background-color: var(--cetis-light);
        min-height: 80vh;
    }

    .card-profile {
        border: none;
        border-radius: 20px;
        /* Mantenemos el borde izquierdo característico de tus estilos */
        border-left: 8px solid var(--cetis-primary); 
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background-color: #ffffff;
    }

    .card-profile-header {
        background: linear-gradient(135deg, var(--cetis-primary), #A61E34);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .profile-info-label {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--cetis-primary);
        margin-bottom: 5px;
        letter-spacing: 1px;
    }

    .profile-info-value {
        font-size: 1.1rem;
        color: #333;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        margin-bottom: 20px;
    }

    .btn-regresar {
        background-color: var(--cetis-primary);
        color: white;
        border-radius: 50rem;
        padding: 10px 25px;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-block;
        border: none;
    }

    .btn-regresar:hover {
        background-color: #A61E34;
        color: white;
        box-shadow: 0 4px 12px rgba(140, 0, 26, 0.3);
        transform: translateY(-2px);
    }

    /* Evitamos interferir con el sidebar asegurando que el contenido tenga su espacio */
    .content-wrapper {
        transition: margin-left .3s;
    }
</style>

<div class="profile-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                
                <div class="card card-profile">
                    <div class="card-profile-header">
                        <i class="fas fa-user-circle fa-4x mb-3"></i>
                        <h3 class="mb-0">Perfil de Usuario</h3>
                        <p class="small opacity-75">Información registrada en el sistema</p>
                    </div>

                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="profile-info-label">Nombre Completo</div>
                                <div class="profile-info-value">{{ $nombre }}</div>
                            </div>

                            <div class="col-12">
                                <div class="profile-info-label">Correo Electrónico</div>
                                <div class="profile-info-value">{{ $correo }}</div>
                            </div>

                            <div class="col-12">
                                <div class="profile-info-label">Teléfono</div>
                                <div class="profile-info-value">{{ $telefono }}</div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ url()->previous() }}" class="btn-regresar">
                                <i class="fas fa-arrow-left me-2"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
