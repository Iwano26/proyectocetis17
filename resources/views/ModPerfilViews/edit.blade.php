@extends('layouts.app')

@section('content')
<style>
    /* Mantenemos la consistencia con el perfil */

    body {
        background: url("{{ asset('img/salon.jpg') }}") no-repeat center center fixed !important;
        background-size: cover !important;
        min-height: 100vh;
    }
    
    .profile-page-wrapper {
        font-family: 'Times New Roman', Times, serif !important;
        display: flex;
        justify-content: center;
        padding-top: 30px; /* Ajuste para que no esté tan abajo */
    }

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
        padding: 30px 20px;
        text-align: center;
    }

    .form-group {
        padding: 20px 30px;
    }

    .label-text {
        color: #8C001A;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.8rem;
        margin-bottom: 5px;
        display: block;
    }

    .form-control {
        border: 2px solid #eee;
        border-radius: 10px;
        padding: 10px;
        font-family: 'Times New Roman', Times, serif;
        margin-bottom: 15px;
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
</style>

<div class="profile-page-wrapper">
    <div class="profile-card">
        {{-- Encabezado igual al de perfil --}}
        <div class="header-rojo">
            <i class="bi bi-pencil-square" style="font-size: 2.5rem;"></i>
            <h2 class="mt-2">Editar Datos</h2>
        </div>

        {{-- Formulario --}}
        <form action="{{ route('perfil.update') }}" method="POST" class="form-group">
            @csrf
            @method('PUT')

            <span class="label-text">Nombre</span>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $user->nombre) }}" required>

            <span class="label-text">Apellido Paterno</span>
            <input type="text" name="apellidoPa" class="form-control" value="{{ old('apellidoPa', $user->apellidoPa) }}" required>

            <span class="label-text">Apellido Materno</span>
            <input type="text" name="apellidoMa" class="form-control" value="{{ old('apellidoMa', $user->apellidoMa) }}" required>

            <span class="label-text">Teléfono (10 dígitos)</span>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $user->telefono) }}" maxlength="10" required>

            {{-- Botones --}}
            <button type="submit" class="btn-custom" style="background-color: #8C001A; color: white; border: none;">
                <i class="bi bi-check-circle"></i> Guardar Cambios
            </button>
            
            <a href="{{ route('perfil.index') }}" class="btn-custom" style="border: 2px solid #6c757d; color: #6c757d;">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
        </form>
    </div>
</div>
@endsection