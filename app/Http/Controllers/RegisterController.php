<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
// Eliminamos: use Illuminate\Support\Facades\Mail;
// Eliminamos: use App\Mail\ConfirmarCorreoMailable;

class RegisterController extends Controller
{
    public function create(){
        // Muestra la vista del formulario de registro
        return view('RegisterViews.registrarusuario'); 
    }
    
    public function store(Request $request){
        
        $correo = $request->correo;
        $nombre = $request->nombre;
        $apellidoPa = $request->apellidoPa;
        $apellidoMa = $request->apellidoMa;
        $rol = $request->rol;
        $telefono = $request->telefono;
        $contrasennia = $request->contrasennia;
        
        // Cifrar la contraseña para la columna 'pass' (se necesita VARCHAR(255) en la DB)
        $contraseniaCifrada = Hash::make($contrasennia);
        
        $MensajeError = "";

        try{
            // Ajuste en la inserción: Si la columna 'activo' existe, la insertamos con valor 1.
            // Si la columna 'activo' NO existe, simplemente la omitimos y el registro es directo.
            DB::connection('mysql')
                ->table('persona')
                ->insert([
                    'correo' => $correo,         
                    'nombre' => $nombre,
                    'apellidoPa' => $apellidoPa,
                    'apellidoMa' => $apellidoMa,
                    'rol' => $rol,
                    'telefono' => $telefono,
                    'pass' => $contraseniaCifrada, 
                    'activo' => 1, // El usuario se activa inmediatamente al registrarse
                ]);

            $MensajeError = "Registro exitoso. Puedes iniciar sesión.";

            // ELIMINADA la lógica de envío de correo

            return redirect('/login')
                ->with('sessionInsertado', 'true')
                ->with('mensaje', $MensajeError);

        }
        catch (\Exception $e){
            Log::error('Error al registrar usuario: ' . $e->getMessage()); // Registrar el error
            $MensajeError = "Hubo un error en el servidor al intentar registrar el usuario. Por favor, intenta más tarde.";
            
            return redirect('/register')
                ->with('sessionInsertado', 'false')
                ->with('mensaje', $MensajeError);
        }
    }

    // ELIMINADA la función public function ConfirmMail($correo)

    
}