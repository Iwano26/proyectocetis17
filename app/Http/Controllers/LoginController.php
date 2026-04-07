<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Necesario para verificar estado pendiente

class LoginController extends Controller
{
    public function showLogin() {
        return view("LoginViews/inicio");
    }

    public function login(Request $request) {
        $request->validate([
            'correo' => 'required|email',
            'pass' => 'required'
        ]);

        // Añadimos 'confirmado' => 1 a las credenciales
        $credenciales = [
            'correo' => $request->correo,
            'password' => $request->pass,
            'confirmado' => 1 
        ];

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            $request->session()->save(); 

            return redirect()->intended('/principal'); 
        }

        // Si falla, revisamos si es porque no está confirmado para avisar al usuario
        $usuario = DB::table('persona')->where('correo', $request->correo)->first();
        if ($usuario && $usuario->confirmado == 0) {
            return back()->withErrors([
                'login' => 'Debes confirmar tu cuenta por correo antes de iniciar sesión.'
            ])->withInput($request->only('correo'));
        }

        return back()->withErrors([
            'login' => 'Correo o contraseña incorrectos.'
        ])->withInput($request->only('correo'));
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    /**
     * Versión para App Móvil (React Native)
     */
    public function loginMovil(Request $request) {
        $validator = Validator::make($request->all(), [
            'correo' => 'required|email',
            'pass' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos incompletos o formato inválido.',
                'errors' => $validator->errors()
            ], 422);
        }

        // También aplicamos el filtro de 'confirmado' aquí
        $credenciales = [
            'correo' => $request->correo,
            'password' => $request->pass,
            'confirmado' => 1
        ];

        if (Auth::attempt($credenciales)) {
            $user = Auth::user();
            return response()->json([
                'success' => true,
                'user' => [
                    'nombre' => $user->nombre,
                    'apellidoPa' => $user->apellidoPa,
                    'rol' => $user->rol,
                    'correo' => $user->correo,
                ],
                'message' => 'Acceso concedido'
            ], 200);
        }

        // Revisar si el fallo es por falta de confirmación
        $usuario = DB::table('persona')->where('correo', $request->correo)->first();
        if ($usuario && $usuario->confirmado == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor, confirma tu cuenta desde tu correo institucional.'
            ], 403);
        }

        return response()->json([
            'success' => false,
            'message' => 'El correo o la contraseña son incorrectos.'
        ], 401);
    }
}