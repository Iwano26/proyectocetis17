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
    //
    public function showResetform(){
        return view('ResetPasswordViews.olvidocontrasennia');
    }

    public function showResetFormWithToken($token){
//        echo $token;
        try{
            $res=DB::connection('mysql')
                ->table("usuarios")
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
        catch (\Swift_TransportException $e){
            $MensajeError="Hubo un error con las credenciales de correo";
            return redirect(route('password.reset'))
                ->with('sessionCambiarContrasennia', 'false')
                ->with('mensaje', $MensajeError); //With envía en una session flash dos claves y sus valores
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
            $res=DB::connection('mysql')
                ->table('usuarios')
                ->where("correo_electronico","=",$correo)
                ->get();

            if (!$res->isEmpty()) {

                $res=DB::connection('mysql')
                    ->table('usuarios')
                    ->select("id","nombre_completo")
                    ->where("correo_electronico","=",$correo)
                    ->where('activo', '=', 1)
                    ->first();

                if ($res) {
                    $nombre=$res->nombre_completo;

                    $token = Str::uuid()->toString();

                    // Calcular la fecha y hora de expiración con 10 minutos de expiración
                    $expiraEn = Carbon::now()->addMinutes(10);

                    //insertar en la base de datos el token y la fecha de expiración
                    DB::connection('mysql')
                        ->table('usuarios')
                        ->where('correo_electronico', $correo)
                        ->update([
                            'token_recuperacion' => $token,
                            'token_expiracion' => $expiraEn,
                        ]);

                    //enviar  el correo con el mensaje de recuperación
                    Mail::to($correo)
                        ->send(new cambiarcontrasenniaMailable($nombre,$token));

//                    echo "Nombre: $nombre ----  Token = $token.  expira el token: $expiraEn";

                    $MensajeError = "¡Listo! Revisa tu correo";
                    return redirect('/login')
                        ->with('sessionRecuperarContrasennia', 'true')
                        ->with('mensaje', $MensajeError) //With envía en una session slash dos claves y sus valores
                        ->with('token',$token);
                }
                else{
                    $MensajeError = "Aun no confirmas tu correo";
                    return redirect(route('password.request'))
                        ->with('sessionRecuperarContrasennia', 'false')
                        ->with('mensaje', $MensajeError); //With envía en una session flash dos claves y sus valores
                }
            }
            else {
                $MensajeError = "Este correo no existe";
                return redirect(route('password.request'))
                    ->with('sessionRecuperarContrasennia', 'false')
                    ->with('mensaje', $MensajeError); //With envía en una session flash dos claves y sus valores
            }
        }
        catch (\Swift_TransportException $e){ //Esta excepción se lanza si hay un problema con la conexión al servidor de correo.
            $MensajeError="Hubo un error con las credenciales de correo";
            return redirect(route('password.request'))
                ->with('sessionRecuperarContrasennia', 'false')
                ->with('mensaje', $MensajeError); //With envía en una session flash dos claves y sus valores
        }
        catch (\Exception $e){
            $MensajeError="Hubo un error en el servidor";
//            dd($e->getMessage());
            return redirect(route('password.request'))
                ->with('sessionRecuperarContrasennia', 'false')
                ->with('mensaje', $MensajeError); //With envía en una session flash dos claves y sus valores
        }
    }


}




