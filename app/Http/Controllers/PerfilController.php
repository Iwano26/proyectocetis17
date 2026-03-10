<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    /**
     * Muestra el perfil del usuario autenticado.
     */
    public function index()
    {
        // Obtenemos el usuario que inició sesión
        $user = Auth::user();

        // Validamos que exista una sesión activa
        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión primero.');
        }

        // Concatenamos el nombre completo para enviarlo a la vista
        $nombreCompleto = "{$user->nombre} {$user->apellidoPa} {$user->apellidoMa}";

        // Retornamos la vista en la carpeta específica que solicitaste
        return view('ModPerfilViews.perfil', [
            'nombre'   => $nombreCompleto,
            'correo'   => $user->correo,
            // Si el campo teléfono no existe en tu tabla, puedes dejarlo como 'No disponible'
            'telefono' => $user->telefono ?? 'No registrado' 
        ]);
    }
}