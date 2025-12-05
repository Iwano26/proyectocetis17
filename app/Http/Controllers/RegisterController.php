<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function create(){
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
        
        $contraseniaCifrada = Hash::make($contrasennia);
        
        $MensajeError = "";

        try{
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
                    'activo' => 1, 
                ]);

            $MensajeExito = "Tu cuenta ha sido creada con éxito. Ahora puedes iniciar sesión.";

            // *** CAMBIO CRUCIAL: Redirige a /register con el mensaje 'success' ***
            // Esto permite que el SweetAlert se ejecute en la vista de registro.
            return redirect('/register')->with('success', $MensajeExito);

        }
        catch (\Exception $e){
            Log::error('Error al registrar usuario: ' . $e->getMessage()); 
            $MensajeError = "Hubo un error en el servidor al intentar registrar el usuario. Por favor, intenta más tarde.";
            
            return redirect('/register')
                ->with('sessionInsertado', 'false')
                ->with('mensaje', $MensajeError);
        }
    }
}