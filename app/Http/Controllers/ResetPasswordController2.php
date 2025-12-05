<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\cambiarcontrasenniaMailable;
use Illuminate\Support\Facades\Hash;


class ResetPasswordController2 extends Controller
{
    public function showResetForm(){

        return view('ResetPasswordViews.olvidosucontrasennia');
    }

    public function showResetFormWithToken($token){
//        echo $token;
        try{
            $res=DB::connection('mysql')
                ->table("persona")
                ->select("token_expiracion")
                ->where("token_recuperacion","=",$token)
                ->first();

            if ($res) {
                $fechaExpiracion = Carbon::parse($res->token_expiracion);
                $fechaActual = Carbon::now();

                if ($fechaExpiracion->greaterThan($fechaActual)) { //Verifica si el token aún es vigente
                    return view('ResetPasswordViews.cambiarcontrasennia', [
                        'token' => $token
                    ]);
                }
                else{
                    $MensajeError="El enlace ha expirado";
                    return redirect(route('login'))
                        ->with('sessionCambiarContrasennia', 'false')
                        ->with('mensaje', $MensajeError);
                }
            }
            else {
                $MensajeError="Enlace incorecto o ha expirado";
                return redirect(route('login'))
                    ->with('sessionCambiarContrasennia', 'false')
                    ->with('mensaje', $MensajeError);
            }
        }
        catch (\Exception $e){
            $MensajeError="Hubo un error en el servidor";
            return redirect(route('password.reset'))
                ->with('sessionCambiarContrasennia', 'false')
                ->with('mensaje', $MensajeError); //With envía en una session flash dos claves y sus valores
        }
    }

public function sendResetLinkEmail(Request $request){
    $correo=$request->correo;

    try{
        // 1. Primer intento de consulta: busca el correo y trae su estado 'activo' y 'nombre'
        $res=DB::table('persona')
            ->select("correo","nombre","activo")
            ->where("correo","=",$correo)
            ->first();

        // 2. Verificar si el correo existe
        if (!$res) {
            // El correo NO existe
            $MensajeError = "Este correo no existe";
            return redirect(route('password.request'))
                ->with('sessionRecuperarContrasennia', 'false')
                ->with('mensaje', $MensajeError);
        }

        // 3. El correo SÍ existe, ahora verifica si la cuenta está activa
        if ($res->activo != 1) {
            // La cuenta NO está activa (Activo = 0 o cualquier otro valor diferente a 1)
            $MensajeError = "Aun no confirmas tu correo";
            return redirect(route('password.request'))
                ->with('sessionRecuperarContrasennia', 'false')
                ->with('mensaje', $MensajeError);
        }
        
        // 4. El correo existe y la cuenta SÍ está activa (Activo = 1) - Procede a enviar el enlace
        $nombre=$res->nombre;
        $token = Str::uuid()->toString();

        // Calcular la fecha y hora de expiración con 10 minutos de expiración
        $expiraEn = Carbon::now()->addMinutes(10);

        // Actualizar el token y la fecha de expiración en la base de datos
        DB::connection('mysql')
            ->table('persona')
            ->where('correo', $correo)
            ->update([
                'token_recuperacion' => $token,
                'token_expiracion' => $expiraEn,
            ]);

        // Enviar el correo con el mensaje de recuperación
        Mail::to($correo)
            ->send(new cambiarcontrasenniaMailable($nombre,$token));

        $MensajeError = "¡Listo! Revisa tu correo";
        return redirect('/login')
            ->with('sessionRecuperarContrasennia', 'true')
            ->with('mensaje', $MensajeError)
            ->with('token',$token);

    }
    catch (\Exception $e){
        $MensajeError="Hubo un error en el servidor";
        // dd($e->getMessage()); // Descomenta esto para ver el error real
        return redirect(route('password.request'))
            ->with('sessionRecuperarContrasennia', 'false')
            ->with('mensaje', $MensajeError); 
    }
}

    public function resetPassword(Request $request){
        $contrasennia=$request->contrasennia;
        $contraseniaCifrada = Hash::make($contrasennia);
        $token=$request->mytoken;

//        echo "Token= $token   Contraseña= $contrasennia";

        try{
            DB::connection('mysql')
                ->table('persona')
                ->where('token_recuperacion', $token)
                ->update([
                    'pass' => $contraseniaCifrada
                ]);

            $MensajeError="Cambio de contraseña exitoso";
            return redirect(route('login'))
                ->with('sessionCambiarContrasennia', 'true')
                ->with('mensaje', $MensajeError);

       
        }
        catch (\Exception $e){
            $MensajeError="Hubo un error en el servidor";
//            dd($e->getMessage());
            return redirect(route('password.reset', ['token' => $token]))
                ->with('sessionCambiarContrasennia', 'false')
                ->with('mensaje', $MensajeError); //With envía en una session flash dos claves y sus valores
        }
    }
}
