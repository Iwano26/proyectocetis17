<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PerfilController extends Controller
{
    /**
     * Muestra la vista del perfil (Vista de Lectura)
     */
    public function index()
    {
        $user = Auth::user();
        // Concatenamos para la vista principal de perfil
        $nombreCompleto = "{$user->nombre} {$user->apellidoPa} {$user->apellidoMa}";

        return view('ModPerfilViews.perfil', [
            'nombre'   => $nombreCompleto,
            'correo'   => $user->correo,
            'telefono' => $user->telefono ?? 'No registrado'
        ]);
    }

    /**
     * Muestra el formulario para editar (Vista de Edición)
     */
    public function edit()
    {
        $user = Auth::user();
        return view('ModPerfilViews.edit', compact('user'));
    }

    /**
     * Procesa la actualización de los datos
     */
    public function update(Request $request)
{
    $request->validate([
        'nombre'     => 'required|string|max:255',
        'apellidoPa' => 'required|string|max:255',
        'apellidoMa' => 'required|string|max:255',
        'telefono'   => 'required|digits:10',
    ]);

    try {
        // Buscamos al usuario por correo
        $user = User::where('correo', Auth::user()->correo)->first();

        if ($user) {
            $user->nombre     = $request->nombre;
            $user->apellidoPa = $request->apellidoPa;
            $user->apellidoMa = $request->apellidoMa;
            $user->telefono   = $request->telefono;
            
            // Ahora que timestamps = false, esto no buscará 'updated_at'
            $user->save(); 

            return redirect()->route('perfil.index')
                ->with('mensaje', 'Perfil actualizado exitosamente.')
                ->with('sessionInsertado', 'true');
        }
    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('mensaje', 'Error al actualizar: ' . $e->getMessage())
            ->with('sessionInsertado', 'false');
    }
}
}