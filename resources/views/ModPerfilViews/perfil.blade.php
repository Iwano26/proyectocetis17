@extends('layouts.app')

@section('content')
<style>
    /* Aplicamos Times New Roman a todo el contenedor del perfil */
    .profile-page-wrapper {
        font-family: 'Times New Roman', Times, serif !important;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
    }

    /* Estilo del recuadro rojo moderno */
    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        width: 100%;
        max-width: 450px;
        overflow: hidden;
    }

    .header-rojo {
        background-color: #8C001A;
        color: white;
        padding: 40px 20px;
        text-align: center;
    }

    .info-group {
        padding: 20px 30px;
        text-align: left;
    }

    .label-text {
        color: #8C001A;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.85rem;
        margin-bottom: 5px;
        display: block;
    }

    .value-text {
        font-size: 1.2rem;
        color: #333;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
    }

    .btn-custom {
        display: block;
        width: 100%;
        padding: 12px;
        border-radius: 50px;
        text-align: center;
        font-weight: bold;
        text-decoration: none;
        margin-bottom: 10px;
        transition: 0.3s;
    }
    .card-profile-modern {
        background: url("../img/salon.jpg") no-repeat center center fixed;
        background-size: cover;
        display: flex;
        flex-direction: column; /* Alineación vertical */
    }
    /* 1. Esto pone la imagen de fondo en toda la pantalla */
    body {
        background: url("{{ asset('img/salon.jpg') }}") no-repeat center center fixed !important;
        background-size: cover !important;
    }

    /* 2. El resto de tus estilos */
    .profile-page-wrapper {
        font-family: 'Times New Roman', Times, serif !important;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh; /* Ocupa toda la pantalla */
        padding: 20px;
    }

    .profile-card {
        background: white; /* La tarjeta es blanca para que se lea bien sobre el salón */
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        width: 100%;
        max-width: 450px;
        overflow: hidden;
    }


</style>

<div class="profile-page-wrapper">
    <div class="profile-card">
        {{-- Encabezado Rojo --}}
        <div class="header-rojo">
            <i class="bi bi-person-badge" style="font-size: 3rem;"></i>
            <h2 class="mt-2">Mi Perfil</h2>
            <p style="opacity: 0.8;">Información del Usuario</p>
        </div>

        {{-- Contenido --}}
        <div class="info-group">
            <span class="label-text">Nombre Completo</span>
            <div class="value-text">{{ $nombre }}</div>

            <span class="label-text">Correo Electrónico</span>
            <div class="value-text">{{ $correo }}</div>

            <span class="label-text">Teléfono de contacto</span>
            <div class="value-text">{{ $telefono }}</div>

            {{-- Botones --}}
            <a href="{{ route('perfil.edit') }}" class="btn-custom" 
               style="border: 2px solid #8C001A; color: #8C001A;">
                <i class="bi bi-gear"></i> Configurar Perfil
            </a>
            
            <a href="{{ url('/principal') }}" class="btn-custom" 
               style="background-color: #8C001A; color: white;">
                <i class="bi bi-arrow-left"></i> Volver al Inicio
            </a>
        </div>
    </div>
</div>
@endsection