<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class GestionUsuarioController extends Controller
{
    public function index()
    {
        try {
            // Jalamos todos los usuarios de la tabla persona
            $usuarios = DB::table('persona')->get();
            return view('GestionUsuarioViews/usuarios', compact('usuarios'));
        } catch (\Exception $e) {
            Log::error('Error al cargar la gestión de usuarios: ' . $e->getMessage());
            return view('GestionUsuarioViews/usuarios', ['usuarios' => collect()])
                ->with('mensaje', 'Error al conectar con la base de datos.')
                ->with('sessionInsertado', 'false');
        }
    }

    public function store(Request $request)
    {
        // Añadidos los apellidos a la validación obligatoria
        $request->validate([
            'correo'       => 'required|email|unique:persona,correo|regex:/@cetis17\.edu\.mx$/',
            'nombre'       => 'required|string|max:255',
            'apellidoPa'   => 'required|string|max:255',
            'apellidoMa'   => 'required|string|max:255',
            'contrasennia' => 'required|min:8',
            'rol'          => 'required|in:Administrador,Asesor,Estudiante',
            'telefono'     => 'required|digits:10',
        ]);
        
        try {
            DB::table('persona')->insert([
                'correo'     => $request->correo,         
                'nombre'     => $request->nombre,
                'apellidoPa' => $request->apellidoPa,
                'apellidoMa' => $request->apellidoMa,
                'rol'        => $request->rol, 
                'telefono'   => $request->telefono,
                'pass'       => Hash::make($request->contrasennia), 
                'activo'     => 1, // Por defecto entra activo
                'confirmado' => 1 
            ]);

            return redirect()->route('gestionusuario.index')
                ->with('sessionInsertado', 'true')
                ->with('mensaje', "Usuario registrado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error al registrar usuario: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', "Error al registrar: El correo ya podría estar en uso.");
        }
    }
    
    public function update(Request $request, string $correo)
    {
        $usuarioAEditar = DB::table('persona')->where('correo', $correo)->first();

        // QUITADA la restricción de editar a otros administradores.
        // Solo protegemos que un administrador no se pueda quitar el rol de Administrador a sí mismo.
        if (Auth::user()->correo === $correo && $request->rol !== 'Administrador') {
            return redirect()->back()->with('mensaje', 'Operación denegada: No puedes revocar tu propio rol de Administrador.')
                                     ->with('sessionInsertado', 'false');
        }

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'apellidoPa'   => 'required|string|max:255',
            'apellidoMa'   => 'required|string|max:255',
            'rol'          => 'required|in:Administrador,Asesor,Estudiante',
            'telefono'     => 'required|digits:10',
            'contrasennia' => 'nullable|min:8',
        ]);

        $dataToUpdate = [
            'nombre'     => $request->nombre,
            'apellidoPa' => $request->apellidoPa,
            'apellidoMa' => $request->apellidoMa,
            'rol'        => $request->rol,
            'telefono'   => $request->telefono,
        ];
        
        if (!empty($request->contrasennia)) {
            $dataToUpdate['pass'] = Hash::make($request->contrasennia);
        }

        try {
            DB::table('persona')->where('correo', $correo)->update($dataToUpdate);
            return redirect()->route('gestionusuario.index')->with('sessionInsertado', 'true')
                ->with('mensaje', "Usuario actualizado correctamente.");
        } catch (\Exception $e) {
            return redirect()->back()->with('sessionInsertado', 'false')
                ->with('mensaje', "Error al actualizar los datos.");
        }
    }

    // NUEVO MÉTODO: Para alternar el estado Activo/Inactivo sin borrar de la BD
    public function toggleStatus(string $correo)
    {
        $usuario = DB::table('persona')->where('correo', $correo)->first();

        if (Auth::user()->correo === $correo) {
            return redirect()->back()->with('mensaje', 'Operación inválida: No puedes desactivar tu propia cuenta.');
        }

        // Si está en 1 pasa a 0, si está en 0 pasa a 1
        $nuevoEstado = $usuario->activo == 1 ? 0 : 1;

        DB::table('persona')->where('correo', $correo)->update(['activo' => $nuevoEstado]);

        $statusText = $nuevoEstado == 1 ? 'activada' : 'desactivada';
        return redirect()->route('gestionusuario.index')->with('mensaje', "La cuenta ha sido {$statusText} correctamente.");
    }
    
    public function destroy(string $correo)
    {
        try {
            if (Auth::user()->correo === $correo) {
                return redirect()->back()->with('mensaje', 'Operación inválida: No puedes eliminar tu propia cuenta.')
                                         ->with('sessionEliminado', 'false');
            }

            // AHORA SÍ permitimos borrar perfiles de otros administradores si es necesario.
            DB::table('persona')->where('correo', $correo)->delete();
            return redirect()->route('gestionusuario.index')
                ->with('mensaje', 'Usuario eliminado permanentemente.')
                ->with('sessionEliminado', 'true');

        } catch (\Exception $e) {
            return redirect()->back()->with('mensaje', 'Error al intentar eliminar el registro.')
                                     ->with('sessionEliminado', 'false');
        }
    }
}