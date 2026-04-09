<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class GestionUsuarioController extends Controller
{
    // El constructor con $this->middleware() se eliminó 
    // porque ahora la protección está en routes/web.php

    public function index()
    {
        try {
            // Obtenemos todos los registros de la tabla persona
            $usuarios = DB::connection('mysql')->table('persona')->get();
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
        // Validamos que los datos cumplan con las reglas del negocio
        $request->validate([
            'correo' => 'required|email|unique:persona,correo|regex:/@cetis17\.edu\.mx$/',
            'nombre' => 'required|string|max:255',
            'contrasennia' => 'required|min:8',
            'rol' => 'required|in:Administrador,Asesor,Estudiante',
            'telefono' => 'required|digits:10',
        ]);
        
        try {
            DB::table('persona')->insert([
                'correo' => $request->correo,         
                'nombre' => $request->nombre,
                'apellidoPa' => $request->apellidoPa,
                'apellidoMa' => $request->apellidoMa,
                'rol' => $request->rol, 
                'telefono' => $request->telefono,
                'pass' => Hash::make($request->contrasennia), 
                'activo' => 1,
                'confirmado' => 1 // Activado automáticamente al ser creado por admin
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
        // Regla: No permitir que un admin edite a OTRO admin (seguridad extra)
        $usuarioAEditar = DB::table('persona')->where('correo', $correo)->first();
        if ($usuarioAEditar->rol === 'Administrador' && Auth::user()->correo !== $correo) {
            return redirect()->back()->with('mensaje', 'Acceso restringido: No puedes modificar a otros administradores.')
                                     ->with('sessionInsertado', 'false');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidoPa' => 'required|string|max:255',
            'apellidoMa' => 'required|string|max:255',
            'rol' => 'required|in:Administrador,Asesor,Estudiante',
            'telefono' => 'required|digits:10',
            'contrasennia' => 'nullable|min:8',
        ]);

        $dataToUpdate = [
            'nombre' => $request->nombre,
            'apellidoPa' => $request->apellidoPa,
            'apellidoMa' => $request->apellidoMa,
            'rol' => $request->rol,
            'telefono' => $request->telefono,
        ];
        
        // Solo actualizamos la contraseña si el usuario escribió algo en el campo
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
    
    public function destroy(string $correo)
    {
        try {
            // Regla: No puedes eliminar tu propia cuenta en sesión
            if (Auth::user()->correo === $correo) {
                return redirect()->back()->with('mensaje', 'Operación inválida: No puedes eliminar tu propia cuenta.')
                                         ->with('sessionEliminado', 'false');
            }

            // Regla: No se pueden borrar otros administradores
            $target = DB::table('persona')->where('correo', $correo)->first();
            if ($target->rol === 'Administrador') {
                return redirect()->back()->with('mensaje', 'Acción protegida: No se permite eliminar perfiles administrativos.')
                                         ->with('sessionEliminado', 'false');
            }

            DB::table('persona')->where('correo', $correo)->delete();
            return redirect()->route('gestionusuario.index')
                ->with('mensaje', 'Usuario eliminado exitosamente.')
                ->with('sessionEliminado', 'true');

        } catch (\Exception $e) {
            return redirect()->back()->with('mensaje', 'Error al intentar eliminar el registro.')
                             ->with('sessionEliminado', 'false');
        }
    }
}