<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class GestionUsuarioController extends Controller
{
    /**
     * Muestra la lista de todos los usuarios y la vista de gestión (CRUD READ).
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Obtener todos los usuarios de la tabla 'persona'
            $usuarios = DB::connection('mysql')->table('persona')->get();
            
            // Devolver la vista, pasando la colección de usuarios
            return view('GestionUsuarioViews/usuarios', compact('usuarios'));

        } catch (\Exception $e) {
            Log::error('Error al cargar la gestión de usuarios: ' . $e->getMessage());
            // En caso de error, pasa una colección vacía y un mensaje de error a la vista.
            return view('GestionUsuarioViews/usuarios', ['usuarios' => collect()])
                        ->with('mensaje', 'Error al conectar con la base de datos: ' . $e->getMessage())
                        ->with('sessionInsertado', 'false');
        }
    }

    /**
     * Almacena un nuevo usuario registrado por un Administrador (CRUD CREATE).
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validar datos básicos
        $request->validate([
            // Correo debe ser único y seguir el patrón institucional
            'correo' => 'required|email|unique:persona,correo|regex:/@cetis17\.edu\.mx$/',
            'nombre' => 'required|string|max:255',
            'contrasennia' => 'required|min:8',
            'rol' => 'required|in:Administrador,Asesor,Estudiante',
            'telefono' => 'required|digits:10',
        ]);
        
        $Mensaje = "";
        try{
            // Cifrar la contraseña antes de guardar
            $contraseniaCifrada = Hash::make($request->contrasennia);
            
            // 2. Insertar en la tabla 'persona'
            DB::connection('mysql')
                ->table('persona')
                ->insert([
                    'correo' => $request->correo,         
                    'nombre' => $request->nombre,
                    'apellidoPa' => $request->apellidoPa,
                    'apellidoMa' => $request->apellidoMa,
                    'rol' => $request->rol, 
                    'telefono' => $request->telefono,
                    'pass' => $contraseniaCifrada, 
                    'activo' => 1,
                ]);

            $Mensaje = "Usuario registrado exitosamente.";

            return redirect()->route('gestionusuario.index')
                ->with('sessionInsertado', 'true')
                ->with('mensaje', $Mensaje);

        } catch (\Exception $e){
            Log::error('Error al registrar usuario (Admin): ' . $e->getMessage());
            $Mensaje = "Hubo un error al registrar el usuario: " . $e->getMessage();
            
            return redirect()->back()
                ->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', $Mensaje);
        }
    }
    
    /**
     * Actualiza los datos de un usuario existente (CRUD UPDATE).
     * Ruta: PUT/PATCH /gestionusuario/{correo}
     * @param \Illuminate\Http\Request $request
     * @param string $correo Correo del usuario a actualizar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $correo)
    {
        // 1. Validar los datos de entrada
        $rules = [
            'nombre' => 'required|string|max:255',
            'apellidoPa' => 'required|string|max:255',
            'apellidoMa' => 'required|string|max:255',
            'rol' => 'required|in:Administrador,Asesor,Estudiante',
            'telefono' => 'required|digits:10',
            // La contraseña es opcional al actualizar, solo se valida si se proporciona
            'contrasennia' => 'nullable|min:8',
        ];
        
        $request->validate($rules);

        $dataToUpdate = [
            'nombre' => $request->nombre,
            'apellidoPa' => $request->apellidoPa,
            'apellidoMa' => $request->apellidoMa,
            'rol' => $request->rol,
            'telefono' => $request->telefono,
        ];
        
        // Si se proporciona una nueva contraseña, la ciframos y la incluimos
        if (!empty($request->contrasennia)) {
            $dataToUpdate['pass'] = Hash::make($request->contrasennia);
        }

        $Mensaje = "";

        try {
            // 2. Realizar la actualización
            $updated = DB::connection('mysql')
                ->table('persona')
                ->where('correo', $correo) // Usamos el correo que viene del parámetro de la ruta
                ->update($dataToUpdate);

            $Mensaje = "Usuario con correo {$correo} actualizado exitosamente.";
            
            return redirect()->route('gestionusuario.index')
                ->with('sessionInsertado', 'true') // Usamos 'sessionInsertado' para éxito general
                ->with('mensaje', $Mensaje);

        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            $Mensaje = "Hubo un error al actualizar el usuario: " . $e->getMessage();
            
            return redirect()->back()
                ->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', $Mensaje);
        }
    }
    
    /**
     * Elimina un usuario (CRUD DELETE).
     * @param string $correo Correo del usuario a eliminar (viene de la URL).
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $correo)
    {
        try {
            // Eliminar el registro donde el correo coincide
            $deleted = DB::connection('mysql')
                ->table('persona')
                ->where('correo', $correo)
                ->delete();

            if ($deleted) {
                return redirect()->route('gestionusuario.index')
                    ->with('mensaje', 'Usuario con correo ' . $correo . ' eliminado exitosamente.')
                    ->with('sessionEliminado', 'true');
            } else {
                return redirect()->route('gestionusuario.index')
                    ->with('mensaje', 'El usuario con correo ' . $correo . ' no fue encontrado para eliminar.')
                    ->with('sessionEliminado', 'false');
            }

        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return redirect()->route('gestionusuario.index')
                ->with('mensaje', 'Error al eliminar el usuario: ' . $e->getMessage())
                ->with('sessionEliminado', 'false');
        }
    }
}