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
    
    public function store(Request $request)
    {
        // Verificación manual de correo duplicado
        $existe = DB::table('persona')->where('correo', $request->correo)->exists();
        
        if ($existe) {
            return redirect('/register')
                ->withInput()
                ->withErrors(['correo' => '¡Este correo ya está registrado! ¿Olvidaste tu contraseña?']);
        }

        $request->validate([
            'correo'                 => 'required|email',
            'nombre'                 => 'required|string|max:50',
            'apellidoPa'             => 'required|string|max:50',
            'apellidoMa'             => 'required|string|max:50',
            'rol'                    => 'required|in:Estudiante,Asesor',
            'telefono'               => 'required|digits:10',
            'contrasennia'           => 'required|string|min:8',
            'recontrasennia'         => 'required|same:contrasennia',
            // NUEVA VALIDACIÓN DEL CAPTCHA:
            'g-recaptcha-response'   => 'required|captcha',
        ], [
            'correo.email'           => 'El correo no tiene un format válido.',
            'telefono.digits'        => 'El teléfono debe tener exactamente 10 dígitos.',
            'contrasennia.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'recontrasennia.same'    => 'Las contraseñas no coinciden.',
            // MENSAJES DEL CAPTCHA:
            'g-recaptcha-response.required' => 'Por favor, verifica que no eres un robot.',
            'g-recaptcha-response.captcha'  => 'Error de captcha. Inténtalo de nuevo.',
        ]);

        $contraseniaCifrada = Hash::make($request->contrasennia);
        $token = Str::random(64);

        try {
            DB::table('persona')->insert([
                'correo'             => $request->correo,
                'nombre'             => $request->nombre,
                'apellidoPa'         => $request->apellidoPa,
                'apellidoMa'         => $request->apellidoMa,
                'rol'                => $request->rol,
                'telefono'           => $request->telefono,
                'pass'               => $contraseniaCifrada,
                'activo'             => 1,
                'confirmado'         => 0,
                'token_confirmacion' => $token,
            ]);

            Mail::to($request->correo)->send(new ConfirmarCuentaMailable($request->nombre, $token));

            return redirect('/register')->with('success', 'Cuenta creada. Revisa tu correo para activarla.');

        } catch (\Exception $e) {
            Log::error('Error al registrar: ' . $e->getMessage());
            return redirect('/register')
                ->withInput()
                ->with('mensaje', 'Error inesperado: ' . $e->getMessage());
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