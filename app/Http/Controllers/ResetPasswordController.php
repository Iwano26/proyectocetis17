<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\cambiarcontrasenniaMailable;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function showResetForm()
    {
        return view('ResetPasswordViews.olvidosucontrasennia');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['correo' => 'required|email']);

        // Buscamos en tu tabla 'persona'
        $persona = DB::table('persona')->where('correo', $request->correo)->first();

        if ($persona) {
            $token = Str::random(64);
            
            // Actualizamos los campos de recuperación de esa persona
            DB::table('persona')->where('correo', $request->correo)->update([
                'token_recuperacion' => $token,
                'token_expiracion' => Carbon::now()->addMinutes(15)
            ]);

            // Enviamos el correo (Aquí es donde falla por tu configuración .env)
            Mail::to($request->correo)->send(new cambiarcontrasenniaMailable($persona->nombre, $token));
        }

        return back()->with('sessionRecuperarContrasennia', 'false')
                     ->with('mensaje', 'Si el correo es institucional, recibirás un enlace pronto.');
    }

    public function showResetFormWithToken($token)
    {
        // Validamos que el token exista en la tabla persona y no haya expirado
        $persona = DB::table('persona')
            ->where('token_recuperacion', $token)
            ->where('token_expiracion', '>', Carbon::now())
            ->first();

        if (!$persona) {
            return redirect()->route('login')->with('mensaje', 'El enlace es inválido o ya expiró.');
        }

        return view('ResetPasswordViews.cambiarcontrasennia', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'mytoken' => 'required'
        ]);

        $persona = DB::table('persona')
            ->where('token_recuperacion', $request->mytoken)
            ->where('token_expiracion', '>', Carbon::now())
            ->first();

        if (!$persona) {
            return redirect()->route('login')->with('mensaje', 'Sesión de recuperación inválida.');
        }

        // Actualizamos la contraseña y limpiamos los tokens
        DB::table('persona')->where('correo', $persona->correo)->update([
            'pass' => Hash::make($request->password),
            'token_recuperacion' => null,
            'token_expiracion' => null
        ]);

        return redirect()->route('login')->with('mensaje', '¡Contraseña actualizada exitosamente!');
    }
}