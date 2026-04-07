<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Mail; 
use App\Mail\ConfirmarCuentaMailable; 

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
        $token = Str::random(64);

        try {
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
                    'confirmado' => 0, 
                    'token_confirmacion' => $token,
                ]);

            // Enviamos el mailable
            Mail::to($correo)->send(new ConfirmarCuentaMailable($nombre, $token));

            return redirect('/register')->with('success', "Cuenta creada. Revisa tu correo institucional para activarla.");

            } catch (\Exception $e){
            Log::error('Error al registrar: ' . $e->getMessage()); 
        // Cambia el mensaje temporalmente para ver el error real en pantalla:
        return redirect('/register')->with('mensaje', "Error: " . $e->getMessage());
        }
    }

    public function confirmar($token) {
        $persona = DB::table('persona')->where('token_confirmacion', $token)->first();

        if (!$persona) {
            return redirect('/login')->with('mensaje', 'El enlace ya no es válido.');
        }

        DB::table('persona')->where('correo', $persona->correo)->update([
            'confirmado' => 1,
            'token_confirmacion' => null
        ]);

        // Mandamos a la vista de éxito
        return view('RegisterViews.mensajecorreoconfirmado');
    }
}